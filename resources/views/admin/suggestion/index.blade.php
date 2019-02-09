@extends("admin.layout.app")

@section("navbar")
  <h4>Suggestions</h4>
  @include("admin.components.navbar-search", [
    "route"       => route("admin.subject.index"),
    "placeholder" => "Search by name"
  ])
@stop

@section("content")
  <div class="padded-container">
    <div class="card">
      <div class="card-content no-padding">
        @include("admin.components.crud-tablev2", [
          "notFound"      => "No suggestions were found!",
          "items"         => $suggestions,
          "columns"       => [
            "title" => ["label" => "Title"],
            "type" => ["label" => "User type", "transform" => function ($value, $item) {
              if ($item -> user instanceof \App\Models\Student) {
                return "Student";
              } else if ($item -> user instanceof \App\Models\ParentModel) {
                return "Parent";
              } else if ($item -> user instanceof \App\Models\Teacher) {
                return "Teacher";
              }
            }],
            "user" => ["label" => "User Name", "transform" => function ($value) {
              return $value -> name;
            }]
          ],
          "actions" => [
            [
              "route" => function ($item) { return route("admin.suggestion.show", $item -> id); },
              "icon" => "open_in_new",
              "tooltip" => "Show suggestion"
            ],
            [
              "route" => function ($item) { return route("admin.suggestion.destroy", $item -> id); },
              "method" => "delete",
              "icon" => "delete",
              "tooltip" => "Delete"
            ]
          ],
        ])
      </div>
    </div>
  </div>
@stop