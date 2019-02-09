@include("admin.components.input", [
  "name" => "name",
  "old"  => isset($parent) ? $parent -> name : ""
])
@include("admin.components.input", [
  "name" => "email",
  "old"  => isset($parent) ? $parent -> email : ""
])
@include("admin.components.input", [
  "name" => "phone",
  "old"  => isset($parent) ? $parent -> phone : ""
])
@include("admin.components.input", [
  "name" => "password",
  "type" => "password",
  "dontFlash" => true
])
@include("admin.components.input", [
  "name" => "password_confirmation",
  "type" => "password",
  "label" => "Password Confirmation",
  "dontFlash" => true
])

<div class="col-xs-12">
  @include("admin.components.file", [
    "name" => "image",
    "label" => "Image"
  ])
</div>
@isset($parent)
  <div class="col-xs-12">
    <img src="{{ $parent -> getFirstMediaUrl("images") }}" class="responsive-img" />
  </div>
@endisset
