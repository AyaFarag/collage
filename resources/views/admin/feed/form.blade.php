@include("admin.components.input", [
  "name" => "title",
  "label" => "Title",
  "old"  => isset($feed) ? $feed -> title : ""
])
@include("admin.components.input", [
  "name" => "sub-title",
  "label" => "Sub Title",
  "old"  => isset($feed) ? $feed -> {"sub-title"} : ""  
])
@include("admin.components.input", [
  "name" => "details",
  "label" => "Details",
  "old"  => isset($feed) ? $feed -> details : ""
  
])
{{--  images  --}}
@include("admin.components.file", [
  "label" => "Image",
  "name" => "images[]",
])
@if (isset($feed))
  <div class="margin-top">
    <img src="{{ $feed -> getFirstMediaUrl("images") }}" />
  </div>
@endif

