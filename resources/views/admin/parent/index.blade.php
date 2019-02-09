@extends("admin.layout.app")

@section("navbar")
  <h4>Parents</h4>
  @include("admin.components.navbar-search", [
    "route"       => route("admin.parent.index"),
    "placeholder" => "Search by name/email"
  ])
@stop

@section("content")
  <div class="padded-container">
    <div class="card">
      <div class="card-content no-padding">
        @include("admin.components.crud-table", [
          "baseRouteName" => "admin.parent",
          "notFound"      => "No parent were found!",
          "items"         => $parent,
          "model"         => \App\Models\Parents::class,
          "columns"       => [
            "name"  => ["label" => "Name"],
            "email" => ["label" => "Email"]
          ]
        ])
      </div>
    </div>
  </div>
  @can("create", \App\Models\Parents::class)
    @include("admin.components.floating-fab", [
      "to"         => route("admin.parent.create"),
      "tooltip"    => "New Parent",
      "attributes" => [
        "id" => "create-item"
      ]
    ])
    @include("admin.components.feature-discovery", [
      "target"  => "create-item",
      "title"   => "Create a new parent",
      "color"   => "secondary",
      "content" => "You can click on this floating fab to create a new parent.<br /> You'll only see this message once!"
    ])
  @endcan
@stop