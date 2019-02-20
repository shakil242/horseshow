<script type="text/javascript">
$(function () {
    // Asynchronously Load the map API 
    //--- Check Either Map Exist or Not
    var mapExist = $('#map_canvas');
    //console.log(typeof(mapExist));
    if (typeof (mapExist) != "object" || mapExist.html() == undefined) {

    } else {

        if ($('#search-input').val() != undefined){
            var script = document.createElement('script');
            script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyCWp7OvMOkqzMjDTNHDstANUQatmbuWyWo&libraries=places&callback=initialize";
        }
        
        document.body.appendChild(script);
        
    }

});

function initialize() {
    //--- Initialize the Search Bar if exist
    if ($('#search-input').val() != undefined)
        searchBar(map);

    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        //mapTypeId: 'roadmap',
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    // Display a map on the page
    var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);

    //--- Start of Curren Location
    var currentLoc = $('.location').val();
    var currentLat = $('.latitude').val();
    var currentLng = $('.longitude').val();

    var place;
    var options = {
        language: 'en-GB',
        /*types: ['(cities)'],
        componentRestrictions: {country: 'us'}*/
    };
    var place = document.getElementById('search-input');
    
    //--- Set the latitude and longitude
    locationLat = parseFloat(currentLat).toFixed(6);
    locationLng = parseFloat(currentLng).toFixed(6);

    if(!isNaN(locationLat) && !isNaN(locationLng)){
        $('.latitude').val(locationLat);
        $('.longitude').val(locationLng);
        $('.search_location').val(place.value);
        
        //console.log("Lat: "+locationLat + "_ Long: " + locationLng);
        generateMap(place.value, locationLat, locationLng);
    }   
}

function initialize2() {
    var latLng = new google.maps.LatLng(32.978588, -117.075142)
    var mapOptions = {
        center: latLng,
        zoom: 16,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

    var marker = new google.maps.Marker({
        position: latLng,
        title: "Hello World!",
        visible: true
    });
    marker.setMap(map);
}


function searchBar(map) {
    var input = document.getElementById('search-input');

    var searchform = document.getElementById('search-form');
    var place;
    var options = {
        language: 'en-GB',
        /*types: ['(cities)'],
        componentRestrictions: {country: 'us'}*/
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addDomListener(input, 'keydown', function (e) {
        if (e.keyCode === 13 && !e.triggered) {
            google.maps.event.trigger(this, 'keydown', {keyCode: 40})
            google.maps.event.trigger(this, 'keydown', {keyCode: 13, triggered: true})
        }
    });
    //Add listener to detect autocomplete selection
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        place = autocomplete.getPlace();
        var locationLat = place.geometry.location.lat();
        var locationLng = place.geometry.location.lng();

        if(place.formatted_address!=undefined){
            $('.address').val(place.formatted_address);
        }
        
        //--- Set the latitude and longitude
        var lat = locationLat.toFixed(6);
        var lng = locationLng.toFixed(6);
        $('.latitude').val(lat);
        $('.longitude').val(lng);

        //--- set location value for hided input of location
        $('.search_location').val(input.value);
        generateMap(place, locationLat, locationLng);
        //--- Get Zip-Code
        //getZipCode(lat, lng);
    });



    //Reset the inpout box on click
    input.addEventListener('click', function () {
        input.value = "";
    });
}


function generateMap(place, latitude, longitude){

        //--- Save place_id
        if(place.place_id!=""){
            $('.place_id').val(place.place_id);
            var plac = JSON.stringify(place);
            
        }

        center = new google.maps.LatLng(latitude, longitude);

        //--- Regenerate GoogleMap 15-Dec-2016
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            //mapTypeId: 'roadmap',
            zoom: 16,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        // Display a map on the page
        var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        map.setTilt(45);


        //var position = {lat: locationLat, lng: locationLng};
        var position = new google.maps.LatLng(latitude, longitude); //params
        bounds.extend(position);
            
        var marker = new google.maps.Marker({
            position: position,
            map: map,
            title: place.name
        });

        var infoWindow = new google.maps.InfoWindow(), marker;

        var name= '', address = '';
        var website = '', phone =  "";
        if($.type(place)=== 'object'){
            if(place.name!=undefined){
                name = place.name;
            }
            if(place.formatted_address!=undefined){
                address = place.formatted_address;
            }
            if(place.website!=undefined){
                website = place.website;
            }
            if(place.formatted_phone_number!=undefined){
                phone = place.formatted_phone_number
            }
        }else{
            name = place;
        }

            
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                //infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.setContent(
                    "<div class=\"popupWindow\">"+
                    "<p class=\"text-bold\">"+name+ "</p>"+
                    "<p>" + address + "</p>"+
                    "<p class=\"text-italic\">" + website + "</p>"+ 
                    "<p>" + phone + "</p></div>"
                );
                infoWindow.open(map, marker);
            }
        })(marker));
        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);


        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
            this.setZoom(16);
            google.maps.event.removeListener(boundsListener);
        });
        var geocoder = new google.maps.Geocoder;
        var infowindow = new google.maps.InfoWindow;
        //Faran. Get the exact address by clicking on map
        google.maps.event.addListener(map, 'click', function( event ){
            $('.latitude').val(event.latLng.lat());
            $('.longitude').val(event.latLng.lng());
            marker.setPosition(event.latLng);
            geocodeLatLng(geocoder, map, infowindow,event.latLng);
            //alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() ); 
        });
        
}


function initializeCluster(){
    
    //--- Initialize the Search Bar if exist
    if ($('#search-input').val() != undefined)
        searchBar(map);
    
    var currentLoc = $('.location').val();
    var currentLat = $('.latitude').val();
    var currentLng = $('.longitude').val();
    
    
    //var center = new google.maps.LatLng(32.978588, -117.075142);
    var center = new google.maps.LatLng(currentLat, currentLng);
    var map = new google.maps.Map(document.getElementById('map_canvas'), {
      zoom: 12,
      center: center,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var markersData = $('input[name="markers"]').val();
    if(markersData=="" || markersData==undefined)
        return 1;

    var markers = JSON.parse(markersData);

    var clusters = [];
    var imageUrl = 'http://chart.apis.google.com/chart?cht=mm&chs=24x32&' +
          'chco=FFFFFF,008CFF,000000&ext=.png';
    var markerImage = new google.maps.MarkerImage(imageUrl,
          new google.maps.Size(24, 32));
    
    
    for (var i = 0; i < markers.length; i++) {
        var dataPhoto = markers[i];
        var latLng = new google.maps.LatLng(dataPhoto["lat"], dataPhoto["lng"]);
        var marker = new google.maps.Marker({
            position: latLng,
            icon: markerImage,
            title : markers[i]["location"]
        });
        clusters.push(marker);
    }
    
    var markerCluster = new MarkerClusterer(map, clusters, {imagePath: '../../images/m'});
    
}


//--- AjaxCall to get zip-code against lat,lng
function getZipCode(lat, lng){
    //console.log('Run ZipCode');

    var location = {'lat' : lat, 'lng' : lng};
    var zipCode = 0;
    $.ajax({
        'dataType' : 'json',
        'type' : 'POST',
        //'async': false,
        'data' : { 'location': location},
        'success' : function(data){
          zipCode = data;
          if(zipCode==0){
            alert('Location is not service-able.');
          }
          $('input[name="zip_code"]').val(zipCode);
        },
        'error' : function(err){
            console.log(err);
        }
    });
    $('input[name="zip_code"]').val(zipCode);
}
//Reverse geo coding
function geocodeLatLng(geocoder, map, infowindow,latlng) {
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === 'OK') {
        console.log(results);
      if (results[0]) {
        //infowindow.setContent(results[1].formatted_address);
        $('#search-input').val(results[0].formatted_address);
        
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}
</script>