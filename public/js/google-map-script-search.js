$(function () {

    // Asynchronously Load the map API 
    //--- Check Either Map Exist or Not
    // var script = document.createElement('script');
    // script.src = "//maps.googleapis.com/maps/api/js?key=AIzaSyCWp7OvMOkqzMjDTNHDstANUQatmbuWyWo&libraries=places&callback=initialize";
    //document.body.appendChild(script);

});

function initialize() {
    //--- Initialize the Search Bar if exist
    if ($('#search-input').val() != undefined)
        searchBar(map);

    if ($('.search-input-scheduler').val() != undefined)
        searchBarScheduler(map);

        var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        //mapTypeId: 'roadmap',
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    //--- Start of Curren Location
    var currentLoc = $('input[name="location"]').val();
    var currentLat = $('input[name="latitude"]').val();
    var currentLng = $('input[name="longitude"]').val();

    var place;
    var options = {
        language: 'en-GB',
        /*types: ['(cities)'],
        componentRestrictions: {country: 'us'}*/
    };
    var place = $(".search-input")[0];


    //--- Set the latitude and longitude
    locationLat = parseFloat(currentLat).toFixed(6);
    locationLng = parseFloat(currentLng).toFixed(6);

    if(!isNaN(locationLat) && !isNaN(locationLng)){
        $('input[name="latitude"]').val(locationLat);
        $('input[name="longitude"]').val(locationLng);
        $('input[name="search_location"]').val(place.value);
    
    }   
}


function searchBar(map) {
    console.log('Map Search');
    var input = $(".search-input")[0];

    //alert(input.value);

    var searchform = document.getElementById('search-form');
    var place;
    var options = {
        language: 'en-GB',
        /*types: ['(cities)'],
        componentRestrictions: {country: 'us'}*/
    };
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addDomListener(input, 'keydown', function (e) {
        console.log(e.triggered)
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
            $('input[name="address"]').val(place.formatted_address);
        }

        //--- Set the latitude and longitude
        var lat = locationLat.toFixed(6);
        var lng = locationLng.toFixed(6);
        $('input[name="latitude"]').val(lat);
        $('input[name="longitude"]').val(lng);

        //--- set location value for hided input of location
        $('input[name="search_location"]').val(input.value);


    });



    //Reset the inpout box on click
    // input.addEventListener('click', function () {
    //     input.value = "";
    // });
}

function searchBarScheduler(map) {
   // console.log('Map Search');


    var searchform = document.getElementById('search-form');
    var place;
    var options = {
        language: 'en-GB',
        /*types: ['(cities)'],
         componentRestrictions: {country: 'us'}*/
    };

    var inputs = document.getElementsByClassName('search-input-scheduler');

    for (var i = 0; i < inputs.length; i++) {

        var autocomplete = new google.maps.places.Autocomplete(inputs[i], options);
        google.maps.event.addDomListener(inputs[i], 'keydown', function (e) {
            console.log(e.triggered)
            if (e.keyCode === 13 && !e.triggered) {
                google.maps.event.trigger(this, 'keydown', {keyCode: 40});
                google.maps.event.trigger(this, 'keydown', {keyCode: 13, triggered: true})
            }
        });

        google.maps.event.addListener(inputs[i], 'place_changed', function () {

            if(inputs[i].formatted_address!=undefined){
                $('input[name="address"]').val(place.formatted_address);
            }

            //--- set location value for hided input of location
            $('input[name="search_location"]').val(inputs[i].value);


        });


    }

    //alert(input.value);

    //
    // var autocomplete = new google.maps.places.Autocomplete(input, options);
    // google.maps.event.addDomListener(input, 'keydown', function (e) {
    //     console.log(e.triggered)
    //     if (e.keyCode === 13 && !e.triggered) {
    //         google.maps.event.trigger(this, 'keydown', {keyCode: 40})
    //         google.maps.event.trigger(this, 'keydown', {keyCode: 13, triggered: true})
    //     }
    // });
    // //Add listener to detect autocomplete selection
    // google.maps.event.addListener(autocomplete, 'place_changed', function () {
    //     place = autocomplete.getPlace();
    //     var locationLat = place.geometry.location.lat();
    //     var locationLng = place.geometry.location.lng();
    //
    //     if(place.formatted_address!=undefined){
    //         $('input[name="address"]').val(place.formatted_address);
    //     }
    //
    //     //--- Set the latitude and longitude
    //     var lat = locationLat.toFixed(6);
    //     var lng = locationLng.toFixed(6);
    //     $('input[name="latitude"]').val(lat);
    //     $('input[name="longitude"]').val(lng);
    //
    //     //--- set location value for hided input of location
    //     $('input[name="search_location"]').val(input.value);
    //
    //
    // });



    //Reset the inpout box on click
    // input.addEventListener('click', function () {
    //     input.value = "";
    // });
}

