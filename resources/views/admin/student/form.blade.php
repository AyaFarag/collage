@php
if (isset($student)) {
	$pivot = $student -> years() -> latest() -> first() -> pivot;
}
@endphp
<div class="row">
	<div class="col-md-6 col-xs-12">
		@include("admin.components.select", [
		  "label" => "Branch",
		  "name" => "branch_id",
		  "options" => $branches,
		  "old"  => isset($student) ? $student -> branch_id : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.select", [
		  "label" => "Parent",
		  "name" => "parent_id",
		  "options" => $parents,
		  "old"  => isset($student) ? $student -> parent_id : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.select", [
		  "label" => "Level - Class",
		  "name" => "class_id",
		  "options" => $classes,
		  "old"  => isset($pivot) ? $pivot -> class_id : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.select", [
		  "label" => "Year",
		  "name" => "year_id",
		  "options" => $years,
		  "old"  => isset($pivot) ? $pivot -> year_id : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
		  "name" => "name",
		  "old"  => isset($student) ? $student -> name : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
		  "name" => "phone",
		  "old"  => isset($student) ? $student -> phone : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
			"label" => "Seat number",
			"name" => "seat_number",
			"old"  => isset($pivot) ? $pivot -> seat_number : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
			"label" => "SSN",
			"name" => "ssn",
			"old"  => isset($student) ? $student -> ssn : ""
		])
	</div>
	<div class="col-xs-12">
		@include("admin.components.textarea", [
			"name" => "address",
			"old"  => isset($student) ? $student -> address : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
			"label" => "Birth Date",
			"name" => "birth_date",
			"type" => "date",
			"old"  => isset($student) ? $student -> birth_date -> toDateString() : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.select", [
		  "label" => "Gender",
		  "name" => "gender",
		  "options" => [
				"male"   => "Male",
				"female" => "Female",
				"other"  => "Other"
		  ],
		  "old"  => isset($student) ? $student -> gender : ""
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
			"name"      => "password",
			"type"      => "password",
			"dontFlash" => true
		])
	</div>
	<div class="col-md-6 col-xs-12">
		@include("admin.components.input", [
			"label"     => "Password confirmation",
			"name"      => "password_confirmation",
			"type"      => "password",
			"dontFlash" => true
		])
	</div>
	<div class="col-xs-12">
		@include("admin.components.input", [
			"name" => "nationality",
			"old"  => isset($student) ? $student -> nationality : ""
		])
	</div>
	<div class="col-xs-12">
		@include("admin.components.file", [
			"name" => "image",
			"label" => "Image"
		])
	</div>
	@isset($student)
		<div class="col-xs-12">
			<img src="{{ $student -> getFirstMediaUrl("images") }}" class="responsive-img" />
		</div>
	@endisset
</div>