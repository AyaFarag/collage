@extends("admin.layout.app")

@section("navbar")
  <h4>{{ $suggestion -> user -> name }} suggested {{ $suggestion -> title }}</h4>
@stopw

@section("content")
  <div class="padded-container">
    <div class="card">
      <div class="card-content">
        <div class="flex margin-top align-center">
          <div class="flex align-center">
            <i class="material-icons margin-right" style="font-size : 50px">person</i>
            <h3>Info</h3>
          </div>
        </div>
        <div class="vertical-margin margin-left">
          <div class="flex margin-top align-center">
            <strong class="margin-right small" style="font-size : 18px">Name :</strong>
            <span class="washed-out">{{ $suggestion -> user -> name }}</span>
          </div>
          <div class="flex margin-top align-center">
            <strong class="margin-right small" style="font-size : 18px">Type :</strong>
            <span class="washed-out">{{ $suggestion -> user instanceof \App\Models\ParentModel ? "Parent" : $suggestion -> user instanceof \App\Models\Student ? "Student" : "Teacher" }}</span>
          </div>
          <div class="flex margin-top align-center">
            <strong class="margin-right small" style="font-size : 18px">Title :</strong>
            <span class="washed-out">{{ $suggestion -> title }}</span>
          </div>
          <div class="flex margin-top align-center">
            <strong class="margin-right small" style="font-size : 18px">Description</strong>
          </div>
          <div class="flex margin-top align-center">
            {{ $suggestion -> description }}
          </div>
        </div>
      </div>
    </div>
  </div>
@stop