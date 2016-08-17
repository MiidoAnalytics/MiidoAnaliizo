
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
    	locationContainer.latitude = 0;
    	locationContainer.longitude= 0;
    	locationContainer.status   = "error";
        //alert("Geolocation is not supported by this browser.");

    }
}

function showPosition(position) {
    locationContainer.latitude = position.coords.latitude;
    locationContainer.longitude= position.coords.longitude;
    locationContainer.status   = "ok"
    //alert("Latitud: "+locationContainer.latitude+"\n"+"Longitud: "+locationContainer.longitude+"\n"+"Estado: "+locationContainer.status+"\n");
}