
@include("admin.components.input", [
  "label" => "Caption",
  "name" => "caption",
  "old" => isset($video) ? $video -> caption : ""
])



@include("admin.components.input", [
  "label" => "URL",
  "name" => "url",
  "old" => isset($video) ? $video -> url : ""
])
