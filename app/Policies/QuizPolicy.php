<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\Teacher;
use App\Models\Student;
use App\Models\Quiz;

class QuizPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct() {}

    public function view($user, Quiz $quiz) {
        if ($user instanceof Teacher)
            return $quiz -> session -> semesterSession -> teacher_id === $user -> id;
    }

    public function createResponse(Student $student, Quiz $quiz) {
        return $student -> canViewQuizById($quiz -> id);
    }

    public function update(Teacher $teacher, Quiz $quiz) {
        return $quiz -> session -> teacher_id === $teacher -> id;
    }

    public function delete(Teacher $teacher, Quiz $quiz) {
        return $quiz -> session -> teacher_id === $teacher -> id;
    }
}
