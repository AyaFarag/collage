@extends("admin.layout.app")

@section("navbar")
  <h4>Feeds</h4>
  @include("admin.components.navbar-search", [
    "route"       => route("admin.feed.index"),
    "placeholder" => "Search by name/email"
  ])
@stop

@section("content")
  <div class="padded-container">
    <div class="card">
      <div class="card-content no-padding">
        @include("admin.components.crud-table", [
          "baseRouteName" => "admin.feed",
          "notFound"      => "No feeds were found!",
          "items"         => $feed,
          "model"         => \App\Models\Feed::class,
          "columns"       => [
            "title"  => ["label" => "Title"],
            "details" => ["label" => "Details"]
          ]
        ])
      </div>
    </div>
  </div>
  @can("create", \App\Models\Feed::class)
    @include("admin.components.floating-fab", [
      "to"         => route("admin.feed.create"),
      "tooltip"    => "New Feed",
      "attributes" => [
        "id" => "create-item"
      ]
    ])
    @include("admin.components.feature-discovery", [
      "target"  => "create-item",
      "title"   => "Create a new Feed",
      "color"   => "secondary",
      "content" => "You can click on this floating fab to create a new Feed.<br /> You'll only see this message once!"
    ])
  @endcan
@stop