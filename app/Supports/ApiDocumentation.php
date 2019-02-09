<?php

namespace App\Supports;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Supports\ApiDocumentationTrait;

use App\Models\Announcement;
use App\Models\ClassModel;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\StudentTotalPoints;
use App\Models\ParentModel;
use App\Models\Branch;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Session;
use App\Models\Level;
use App\Models\Year;
use App\Models\Feed;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Video;

use App\Http\Resources\AnnouncementResource;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizSnippetResource;
use App\Http\Resources\QuizResponseResource;
use App\Http\Resources\StudentSnippetResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\SessionSnippetResource;
use App\Http\Resources\ParentResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Http\Resources\LevelResource;
use App\Http\Resources\ClassResource;
use App\Http\Resources\TopStudentResource;
use App\Http\Resources\FeedResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\VideoResource;

use App\Http\Requests\Api\Teacher\Session\SessionStartRequest;
use App\Http\Requests\Api\Teacher\Session\SessionRequest;
use App\Http\Requests\Api\Teacher\QuizRequest;
use App\Http\Requests\Api\Teacher\QuizResponseAwardRequest;
use App\Http\Requests\Api\Teacher\AnnouncementRequest;
use App\Http\Requests\Api\Teacher\StudentPointRequest;
use App\Http\Requests\Api\Student\QuizResponseRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\SuggestionRequest;


use App\Services\StudentService;

class ApiDocumentation {
  use ApiDocumentationTrait;

  const DEFAULT_HEADERS = [
    "Content-Type" => "application/json",
    "Accept"       => "application/json",
    "x-api-key"    => "MUaJWwkyZOZKnbvZczGoFDt0sLyeS0eCkoKXtam00nobixvPC2BV2rcP3TKJSLYU"
  ];
  const AUTHENTICATED_HEADERS = ["Authorization" => "Bearer {token}"];

  const CATEGORIES = [
    "students",
    "parents",
    "teachers",
    "general"
  ];

  public function all($categories = null) {
    $endpoints = [];
    if ($categories) {
      foreach ($categories as $category) {
        $endpoints = array_merge($endpoints, $this -> {$category}());
      }
      return $endpoints;
    }

    foreach (self::CATEGORIES as $category) {
      $endpoints = array_merge($endpoints, $this -> {$category}());
    }
    return $endpoints;
  }

  public function get($route, $method) {
    foreach (self::CATEGORIES as $category) {
      foreach ($this -> {$category}() as $endpoint) {
        if ($endpoint["url"] === $route && $endpoint["method"] === $method) {
          return $endpoint;
        }
      }
    }
    return [];
  }

  public function students() {
    return [
      [
        "name"        => "Login (student)",
        "headers"     => array_merge(self::DEFAULT_HEADERS),
        "url"         => "/api/student/login",
        "method"      => "post",
        "description" => "Login as a student using the ssn and password",
        "parameters"  => function () { return $this -> postParameters(new LoginRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new StudentResource(Student::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Change password (student)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/change-password",
        "method"      => "post",
        "description" => "Change account's password for students",
        "parameters"  => function () { return $this -> postParameters(new ChangePasswordRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.password_changed_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Forget password (student)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/forget",
        "method"      => "post",
        "description" => "Forget password",
        "parameters"  => function () { return [["name" => "ssn", "validation" => "required", "type" => "post"]]; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.sent_successfully")
              ]
            ],

            [
              "code" => 404,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Generate password reset token (student)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/forget/token",
        "method"      => "post",
        "description" => "Generate password reset token",
        "parameters"  => function () { return [["name" => "ssn", "validation" => "required", "type" => "post"], ["name" => "code", "validation" => "required", "type" => "post"]]; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "token" => str_random(50)
              ]
            ],
            [
              "code" => 403,
              "data" => [
                "error" => trans("api.invalid_code")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Reset forgotten password (student)",
        "headers"     => array_merge(self::DEFAULT_HEADERS),
        "url"         => "/api/student/forget/reset/{token}",
        "method"      => "post",
        "description" => "Generate password reset token",
        "parameters"  => function () { return array_merge($this -> getParameters("token"), $this -> postParameters(new ResetPasswordRequest())); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.reset_successfully")
              ]
            ],
            [
              "code" => 403,
              "data" => [
                "error" => trans("api.invalid_token")
              ]
            ],
            [
              "code" => 404,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get daily schedule",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/schedule",
        "method"      => "get",
        "description" => "Get the daily schedule for the logged in student",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ScheduleResource(ClassModel::first() -> semesterSessions)) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get contact info",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/contact",
        "method"      => "get",
        "description" => "Get the school's contact info and social networks",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ContactResource(Branch::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "List top students",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/top-student/{type}",
        "method"      => "get",
        "description" => "List the top students by class/level",
        "parameters"  => function () {
          return [[
            "name"       => "type",
            "validation" => "required|in:" . StudentTotalPoints::TYPE_LEVEL . "," . StudentTotalPoints::TYPE_CLASS,
            "type"       => "get"
          ]];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => TopStudentResource::collection(StudentTotalPoints::with("student.classes.level") -> where("points", ">", 0) -> get()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get students profiles",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/top-student/student/{student}",
        "method"      => "get",
        "description" => "Get the profile for a student by id",
        "parameters"  => function () {
          return $this -> getParameters("student");
        },
        "responses"   => function () {
          $student = Student::first();

          $service = new StudentService($student, Year::current());

          return [
            [
              "code" => 200,
              "data" => [
                "data" => [
                  "id"             => $student -> id,
                  "name"           => $student -> name,
                  "class"          => new ClassResource($student -> class),
                  "quiz"           => $service -> totalQuizPoints(),
                  "other"          => $service -> totalOtherPoints(),
                  "student_of_day" => $service -> studentOfDayCount() * config("defaults.student_of_day_multiplier")
                ]
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "List session quizzes",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/{session}/quiz/{type}",
        "method"      => "get",
        "description" => "Get all the quizzes for a specific session",
        "parameters"  => function () {
          return array_merge(
            $this -> getParameters("session"),
            [[
              "name"       => "type",
              "validation" => "required|in:" . Quiz::TYPE_NEW . "," . Quiz::TYPE_OLD,
              "type"       => "get"
            ]]
          );
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => QuizResource::collection(Quiz::paginate()) -> toArray(request())
              ]
            ],
            [
              "code" => 403,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get quiz",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/quiz/{quiz}",
        "method"      => "get",
        "description" => "Get the data for a specific quiz by id",
        "parameters"  => function () {
          return $this -> getParameters("quiz");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new QuizResource(Quiz::first())) -> toArray(request())
              ]
            ],
            [
              "code" => 403,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Respond to a quiz (answer)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/quiz/{quiz}/response",
        "method"      => "post",
        "description" => "Get the data for a specific quiz",
        "parameters"  => function () {
          return array_merge(
            $this -> getParameters("quiz"),
            $this -> postParameters(new QuizResponseRequest())
          );
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.added_successfully")
              ]
            ],
            [
              "code" => 403,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get sessions",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/student/session",
        "method"      => "get",
        "description" => "Get all the teaching sessions for the logged in student",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => SessionSnippetResource::collection(Session::paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ]
    ];
  }

  public function parents() {
    return [

      [
        "name"        => "Login (parent)",
        "headers"     => array_merge(self::DEFAULT_HEADERS),
        "url"         => "/api/parent/login",
        "method"      => "post",
        "description" => "Login as a parent using the ssn and password",
        "parameters"  => function () { return $this -> postParameters(new LoginRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ParentResource(ParentModel::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Change password (parent)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/change-password",
        "method"      => "post",
        "description" => "Change account's password for parents",
        "parameters"  => function () { return $this -> postParameters(new ChangePasswordRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.password_changed_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Forget password (parent)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/forget",
        "method"      => "post",
        "description" => "Forget password",
        "parameters"  => function () { return [["name" => "phone", "validation" => "required", "type" => "post"]]; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.sent_successfully")
              ]
            ],
            [
              "code" => 404,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Generate password reset token (parent)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/forget/token",
        "method"      => "post",
        "description" => "Generate password reset token",
        "parameters"  => function () { return [["name" => "phone", "validation" => "required", "type" => "post"], ["name" => "code", "validation" => "required", "type" => "post"]]; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "token" => str_random(50)
              ]
            ],
            [
              "code" => 403,
              "data" => [
                "error" => trans("api.invalid_code")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Reset forgotten password (parent)",
        "headers"     => array_merge(self::DEFAULT_HEADERS),
        "url"         => "/api/parent/forget/reset/{token}",
        "method"      => "post",
        "description" => "Generate password reset token",
        "parameters"  => function () { return array_merge($this -> getParameters("token"), $this -> postParameters(new ResetPasswordRequest())); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.reset_successfully")
              ]
            ],
            [
              "code" => 403,
              "data" => [
                "error" => trans("api.invalid_token")
              ]
            ],
            [
              "code" => 404,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "List all students (children)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/children",
        "method"      => "get",
        "description" => "List all the children (students) for the logged in parent.",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => StudentSnippetResource::collection(ParentModel::first() -> children) -> toArray(request())
              ]
            ],
            [
              "code" => 400,
              "data" => []
            ]
          ];
        }
      ],
      [
        "name"        => "Get the weekly schedule of a student",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/{studentId}/schedule",
        "method"      => "get",
        "description" => "Get the weekly schedule for a student",
        "parameters"  => function () {
          return $this -> getParameters("studentId");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ScheduleResource(ClassModel::first() -> semesterSessions)) -> toArray(request())
              ]
            ],
            [
              "code" => 400,
              "data" => []
            ]
          ];
        }
      ],
      
      [
        "name"        => "Get contact info",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/{studentId}/contact",
        "method"      => "get",
        "description" => "Get the school's contact info and social networks",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ContactResource(Branch::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "List top students",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/{student}/top-student/{type}",
        "method"      => "get",
        "description" => "List the top students by class/level",
        "parameters"  => function () {
          return array_merge(
            $this -> getParameters("student")
            ,[[
            "name"       => "type",
            "validation" => "required|in:" . StudentTotalPoints::TYPE_LEVEL . "," . StudentTotalPoints::TYPE_CLASS,
            "type"       => "get"
          ]]);
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => TopStudentResource::collection(StudentTotalPoints::with("student.classes.level") -> where("points", ">", 0) -> get()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get students profiles",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/top-student/student/{student}",
        "method"      => "get",
        "description" => "Get the profile for a student by id",
        "parameters"  => function () {
          return $this -> getParameters("student");
        },
        "responses"   => function () {
          $student = Student::first();

          $service = new StudentService($student, Year::current());

          return [
            [
              "code" => 200,
              "data" => [
                "data" => [
                  "id"             => $student -> id,
                  "name"           => $student -> name,
                  "class"          => new ClassResource($student -> class),
                  "quiz"           => $service -> totalQuizPoints(),
                  "other"          => $service -> totalOtherPoints(),
                  "student_of_day" => $service -> studentOfDayCount() * config("defaults.student_of_day_multiplier")
                ]
              ]
            ]
          ];
        }
      ],
    ];
  }

  public function teachers() {
    return [

      [
        "name"        => "Login (teacher)",
        "headers"     => array_merge(self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/login",
        "method"      => "post",
        "description" => "Login as a teacher using the ssn and password",
        "parameters"  => function () { return $this -> postParameters(new LoginRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new TeacherResource(Teacher::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Change password (teacher)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/change-password",
        "method"      => "post",
        "description" => "Change account's password for teachers",
        "parameters"  => function () { return $this -> postParameters(new ChangePasswordRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.password_changed_successfully")
              ]
            ]
          ];
        }
      ],
       [
        "name"        => "Forget password (teacher)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/forget",
        "method"      => "post",
        "description" => "Forget password",
        "parameters"  => function () { return [["name" => "phone", "validation" => "required", "type" => "post"]]; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.sent_successfully")
              ]
            ],
            [
              "code" => 404,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Generate password reset token (teacher)",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/forget/token",
        "method"      => "post",
        "description" => "Generate password reset token",
        "parameters"  => function () { return [["name" => "phone", "validation" => "required", "type" => "post"], ["name" => "code", "validation" => "required", "type" => "post"]]; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "token" => str_random(50)
              ]
            ],
            [
              "code" => 403,
              "data" => [
                "error" => trans("api.invalid_code")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Reset forgotten password (teacher)",
        "headers"     => array_merge(self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/forget/reset/{token}",
        "method"      => "post",
        "description" => "Generate password reset token",
        "parameters"  => function () { return array_merge($this -> getParameters("token"), $this -> postParameters(new ResetPasswordRequest())); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.reset_successfully")
              ]
            ],
            [
              "code" => 403,
              "data" => [
                "error" => trans("api.invalid_token")
              ]
            ],
            [
              "code" => 404,
              "data" => [
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get contact info",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/contact",
        "method"      => "get",
        "description" => "Get the school's contact info and social networks",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ContactResource(Branch::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get the daily schedule",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/schedule",
        "method"      => "get",
        "description" => "Get the daily schedule for the logged in teacher",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new ScheduleResource(ClassModel::first() -> semesterSessions)) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get all the sessions",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/session",
        "method"      => "get",
        "description" => "Get all the sessions for a specific teaching session",
        "parameters"  => function () {
          return $this -> getParameters("semester_session");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => SessionSnippetResource::collection(Session::limit(10) -> get()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Start teaching session",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/session",
        "method"      => "post",
        "description" => "Start the teaching session",
        "parameters"  => function () {
          return $this -> postParameters(new SessionStartRequest());
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.session_started"),
                "data" => [
                  "id" => 3
                ]
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get the data of a teaching session",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/session/{session}",
        "method"      => "get",
        "description" => "Get the data for a specific teaching session",
        "parameters"  => function () {
          return $this -> getParameters("session");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new SessionResource(Session::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Update teaching session",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/session/{session}",
        "method"      => "put",
        "description" => "Update the teaching session | Set absence/student of day",
        "parameters"  => function () {
          return array_merge(
            $this -> postParameters(new SessionRequest()),
            $this -> getParameters("session")
          );
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.updated_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "List quizzes history",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{session}/quiz",
        "method"      => "get",
        "description" => "List quizzes history for a session",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => QuizSnippetResource::collection(Session::first() -> quizzes() -> paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Create a quiz",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{session}/quiz",
        "method"      => "post",
        "description" => "Create a new quiz",
        "parameters"  => function () {
          return $this -> postParameters(new QuizRequest());
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new QuizResource(Quiz::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get a quiz",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{session}/quiz/{quiz}",
        "method"      => "get",
        "description" => "Get a specific quiz by id",
        "parameters"  => function () {
          return $this -> getParameters("quiz");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new QuizResource(Quiz::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Update a quiz",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{session}/quiz/{quiz}",
        "method"      => "put",
        "description" => "Update a specific quiz by id",
        "parameters"  => function () {
          return array_merge(
            $this -> postParameters(new QuizRequest()),
            $this -> getParameters("quiz")
          );
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => (new QuizResource(Quiz::first())) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Delete a quiz",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{session}/quiz/{quiz}",
        "method"      => "delete",
        "description" => "Delete a specific quiz by id",
        "parameters"  => function () {
          return $this -> getParameters("quiz");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.deleted_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get quiz responses",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/quiz/{quiz}/response",
        "method"      => "get",
        "description" => "Get all the responses for a specific quiz",
        "parameters"  => function () {
          return $this -> getParameters("quiz");
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => QuizResponseResource::collection(QuizResponse::paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Give points to a quiz responsee",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/quiz/{quiz}/response/{quiz_response}",
        "method"      => "post",
        "description" => "Give points to a specific quiz response",
        "parameters"  => function () {
          return array_merge(
            $this -> getParameters("quiz", "quiz_response"),
            [
              [
                "name"       => "points",
                "type"       => "post",
                "validation" => "required|numeric|min:1|max:quiz's grade"
              ]
            ]
          );
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.awarded_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Delete a quiz response",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/quiz/{quiz}/response/{quiz_response}",
        "method"      => "delete",
        "description" => "Delete a quiz response",
        "parameters"  => function () {
          return array_merge(
            $this -> getParameters("quiz", "quiz_response"),
            [
              [
                "name"       => "points",
                "type"       => "post",
                "validation" => "required|numeric|min:1|max:quiz's grade"
              ]
            ]
          );
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.awarded_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get session's announcements",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{sessionId}/announcement",
        "method"      => "get",
        "description" => "Get all the announcements for a specific session",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => AnnouncementResource::collection(Announcement::paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Create a new announcement",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{sessionId}/announcement",
        "method"      => "post",
        "description" => "Create a new announcement",
        "parameters"  => function () {
          return $this -> postParameters(new AnnouncementRequest());
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.added_successfully")
              ]
            ]
          ];
        }
      ],

      [
        "name"        => "Update an existing announcement",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{sessionId}/announcement/{announcementId}",
        "method"      => "put",
        "description" => "Update an existing announcement",
        "parameters"  => function () {
          return $this -> postParameters(new AnnouncementRequest());
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.updated_successfully")
              ]
            ]
          ];
        }
      ],

      [
        "name"        => "Delete announcement",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/{sessionId}/announcement/{announcementId}",
        "method"      => "delete",
        "description" => "Delete an existing announcement",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.deleted_successfully")
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get levels",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/level",
        "method"      => "get",
        "description" => "Get all the levels and their classes for the logged in teacher",
        "parameters"  => function () { return []; },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => LevelResource::collection(Level::with("classes") -> paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Give custom points",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/teacher/point",
        "method"      => "post",
        "description" => "Give custom points to a student",
        "parameters"  => function () { return $this -> postParameters(new StudentPointRequest()); },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.added_successfully")
              ]
            ]
          ];
        }
      ],

      [
        "name"        => "List top students",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/top-student/{level}",
        "method"      => "get",
        "description" => "List the top students by level id",
        "parameters"  => function () {
          return [[
            "name"       => "type",
            "validation" => "required|exists:levels,id",
            "type"       => "get"
          ]];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => TopStudentResource::collection(StudentTotalPoints::with("student.classes.level") -> where("points", ">", 0) -> get()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get students profiles",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/parent/top-student/student/{student}",
        "method"      => "get",
        "description" => "Get the profile for a student by id",
        "parameters"  => function () {
          return $this -> getParameters("student");
        },
        "responses"   => function () {
          $student = Student::first();

          $service = new StudentService($student, Year::current());

          return [
            [
              "code" => 200,
              "data" => [
                "data" => [
                  "id"             => $student -> id,
                  "name"           => $student -> name,
                  "class"          => new ClassResource($student -> class),
                  "quiz"           => $service -> totalQuizPoints(),
                  "other"          => $service -> totalOtherPoints(),
                  "student_of_day" => $service -> studentOfDayCount() * config("defaults.student_of_day_multiplier")
                ]
              ]
            ]
          ];
        }
      ],
    ];
  }

  public function general() {
    return [
      [
        "name"        => "Get news feed",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/feed",
        "method"      => "get",
        "description" => "Get news feeds",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => FeedResource::collection(Feed::paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Get news gallery images",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/gallery",
        "method"      => "get",
        "description" => "Get galllery images",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => GalleryResource::collection(Gallery::paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      
      [
        "name"        => "Get about us text",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/about-us",
        "method"      => "get",
        "description" => "Get about us data",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => Setting::first() -> about_us
              ]
            ]
          ];
        }
      ],
      
      [
        "name"        => "Get videos",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/video",
        "method"      => "get",
        "description" => "Get all the videos",
        "parameters"  => function () {
          return [];
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "data" => VideoResource::collection(Video::paginate()) -> toArray(request())
              ]
            ]
          ];
        }
      ],
      [
        "name"        => "Create suggestions",
        "headers"     => array_merge(self::AUTHENTICATED_HEADERS, self::DEFAULT_HEADERS),
        "url"         => "/api/suggest",
        "method"      => "get",
        "description" => "Create a new suggestion",
        "parameters"  => function () {
          return $this -> postParameters(new SuggestionRequest());
        },
        "responses"   => function () {
          return [
            [
              "code" => 200,
              "data" => [
                "message" => trans("api.added_successfully")
              ]
            ]
          ];
        }
      ],
    ];
  }
}