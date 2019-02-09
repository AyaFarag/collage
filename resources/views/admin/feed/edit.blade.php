@extends("admin.layout.app")

@section("navbar")
  <h4>Feeds</h4>
@stop

@section("content")
  <div class="padded-container">
    <div class="card">
      <div class="card-content">
        @component("admin.components.form", [
          "method" => "put",
          "enctype" => "multipart/form-data",
          "action" => route("admin.feed.update", ["feed" => $feed -> id])
        ])
          @include("admin.Feed.form")
          @include("admin.components.button", [
            "icon"  => "replay",
            "label" => "update"
          ])
        @endcomponent
      </div>
    </div>
  </div>
@stop