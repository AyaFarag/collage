

<div id="google_map" style="width:100%; height:400px;"></div>
@if ($errors -> has("lat") || $errors -> has("lng"))
<div class="vertica-margin text-danger">The location is required</div>
@endif
<input type="hidden" name="lat" id="course_latitude" value="{{ $lat ?? old("lat") }}" />
<input type="hidden" name="lng" id="course_longitude" value="{{ $lng ?? old("lng") }}" />
<script type="text/javascript">

  var LATITUDE_ELEMENT_ID  = "course_latitude";
  var LONGITUDE_ELEMENT_ID = "course_longitude";
  var MAP_DIV_ELEMENT_ID   = "google_map";

  var DEFAULT_ZOOM_WHEN_NO_COORDINATE_EXISTS = 1;
  var DEFAULT_CENTER_LATITUDE = 30;
  var DEFAULT_CENTER_LONGITUDE = 31;
  var DEFAULT_ZOOM_WHEN_COORDINATE_EXISTS = 15;

  // This is the zoom level required to position the marker
  var REQUIRED_ZOOM = DEFAULT_ZOOM_WHEN_COORDINATE_EXISTS;

  // The google map variable
  var map = null;

  // The marker variable, when it is null no marker has been added
  var marker = null;

  function initializeGoogleMap() {
    var latitude = +document.getElementById(LATITUDE_ELEMENT_ID).value;
    var longitude = +document.getElementById(LONGITUDE_ELEMENT_ID).value;
    map = new google.maps.Map(document.getElementById(MAP_DIV_ELEMENT_ID), {
      center : {
        lat : latitude || 30,
        lng : longitude || 31
      },
      zoom : 8
    });

    if (latitude && longitude) {

      marker = new google.maps.Marker({
        position: {
          lat : latitude || 30,
          lng : longitude || 31
        },
        map: map,
        title: 'Your branch'
      });
    }
    

    map.addListener("click", googleMapClickHandler);
  }


  function googleMapClickHandler(evt) {
    if(map.getZoom() < REQUIRED_ZOOM) {
      alert("You must zoom in closer to set the position accurately!" );
      return;
    }
    if(marker == null) {
      marker = new google.maps.Marker({
        position: {
          lat : evt.latLng.lat(),
          lng : evt.latLng.lng()
        },
        map: map,
        title: 'Select your location'
      });
    } else {
      marker.setPosition(new google.maps.LatLng(evt.latLng.lat(), evt.latLng.lng()));
    }

    document.getElementById(LATITUDE_ELEMENT_ID).value = evt.latLng.lat();
    document.getElementById(LONGITUDE_ELEMENT_ID).value = evt.latLng.lng();

  }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env("GOOGLE_MAPS_API_KEY") }}&callback=initializeGoogleMap"></script>