@include("admin.components.input", [
  "name" => "name",
  "label" => "Name",
  "old"  => isset($branch) ? $branch -> name : ""
])
@include("admin.components.input", [
  "name" => "address",
  "label" => "Address",
  "old"  => isset($branch) ? $branch -> address : ""
])
@include("admin.components.input", [
  "name" => "social_networks[facebook]",
  "label" => "Facebook",
  "old"  => isset($branch) ? $branch -> social_networks["facebook"] : ""
])
@include("admin.components.input", [
  "name" => "social_networks[twitter]",
  "label" => "Twitter",
  "old"  => isset($branch) ? $branch -> social_networks["twitter"] : ""
])
@include("admin.components.input", [
  "name" => "social_networks[instagram]",
  "label" => "Instagram",
  "old"  => isset($branch) ? $branch -> social_networks["instagram"] : ""
])
@include("admin.components.input", [
  "name" => "social_networks[linkedin]",
  "label" => "Linked In",
  "old"  => isset($branch) ? $branch -> social_networks["linkedin"] : ""
])

<div class="margin-bottom">
@include("admin.components.location-picker", [
  "lat" => isset($branch) ? $branch -> lat : "",
  "lng" => isset($branch) ? $branch -> lng : ""
])
</div>


<div class="removable-input-container margin-bottom">
  <h5 class="margin-bottom big">Phone Numbers</h5>
  <div class="removable-input-items">
    @foreach (empty($branch -> phone_numbers) ? [""] : $branch -> phone_numbers as $phone_number)
      <div class="flex removable-input-group">
        <div class="flex align-center margin-right">
          @include("admin.components.rounded-button", [
            "icon"    => "close",
            "tooltip" => "Remove",
            "class"   => "remove-input"
          ])
        </div>
        <div style="flex : 1">
          @include("admin.components.input", [
            "name"  => "phone_numbers[" . $loop -> index . "]",
            "label" => "Phone Number",
            "old"   => $phone_number
          ])
        </div>
      </div>
    @endforeach
  </div>
  @include("admin.components.button", [
    "notSubmit" => true,
    "label"     => "Add",
    "icon"      => "add",
    "class"     => "add-input",
    "color"     => "secondary"
  ])
</div>

