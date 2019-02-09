@extends("admin.layout.app")

@section("navbar")
  <h4>Parents</h4>
@stop

@section("content")
  <div class="padded-container">
    <div class="card">
      <div class="card-content">
        @component("admin.components.form", [
          "method" => "post",
          "enctype" => "multipart/form-data",
          "action" => route("admin.parent.store")
        ])
          @include("admin.parent.form")
          @include("admin.components.button", [
            "icon"  => "add",
            "label" => "create"
          ])
        @endcomponent
      </div>
    </div>
  </div>
@stop