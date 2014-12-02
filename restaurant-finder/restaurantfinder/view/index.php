<?php 
$pageTitle = 'Restaurant Finder'; 
$pageContent = '

    <article>
		<div id="mapcontainer"></div>
    </article>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7jmXmGE0W3OMlzxcW1IoGWgUZ_1L4fIQ"></script>
<script>

	//set up an array for holding the distances
	var distances = new Array();
	
	//create an array for holding names
	var names = new Array();

	//calculate distances, taken from http://www.geodatasource.com/developers/php
	function distance(lat1, lon1, lat2, lon2, unit) {
		var radlat1 = Math.PI * lat1/180
		var radlat2 = Math.PI * lat2/180
		var radlon1 = Math.PI * lon1/180
		var radlon2 = Math.PI * lon2/180
		var theta = lon1-lon2
		var radtheta = Math.PI * theta/180
		var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
		dist = Math.acos(dist)
		dist = dist * 180/Math.PI
		dist = dist * 60 * 1.1515
		if (unit=="K") { dist = dist * 1.609344 }
		if (unit=="N") { dist = dist * 0.8684 }
		return dist
	}


	function success(position) {

		var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	  
		var options = {
			zoom: 15,
			center: coords,
			mapTypeControl: false,
			navigationControlOptions: {
			style: google.maps.NavigationControlStyle.SMALL
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	  
		var map = new google.maps.Map(document.getElementById("mapcontainer"), options);

		var marker = new google.maps.Marker({
			position: coords,
			map: map,
			title:"You are here!"
		});
	  
	  '.$mapMarkers.'
	  
		//fit the map to the locations
		// Make an array of the LatLngs of the markers you want to show
		var LatLngList = new Array (
			'.$boundariesStr.'
			new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
		//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds ();
		//  Go through each...
		for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
		  //  And increase the bounds to take this point
		  bounds.extend (LatLngList[i]);
		}
		//  Fit these bounds to the map
		map.fitBounds (bounds);
		';
		
	$pageContent.= '
		
		//list the restaurants in order of distance
		
		//get the current location and split into lat/long
		var here = coords.toString();
		
		//trim brackets
		here = here.replace("(","");
		here = here.replace(")","");
		
		var hereExp = here.split(",");
		var hereLat = parseFloat(hereExp[0]);
		var hereLong = parseFloat(hereExp[1]);		

		//create the array of restaurants
		'.$jsLocsObjs.'
		
		//add the distances to the objects
		for (index = 0; index < locsObjs.length; ++index) {
			
			//get the location to compare and split into lat/long
			var there = locsObjs[index].location_ref;
			var thereExp = there.split(",");
			var thereLat = parseFloat(thereExp[0]);
			var thereLong = parseFloat(thereExp[1]);
			
			thisDist = distance(hereLat, hereLong, thereLat, thereLong, "M");
			
			locsObjs[index].distance = thisDist;
		}
		
		//sort by distance
		function compare(a,b) {
		  if (a.distance < b.distance)
			 return -1;
		  if (a.distance > b.distance)
			return 1;
		  return 0;
		}

		locsObjs.sort(compare);
		
		//create a function to restrict by a radius
		function restrictRadius(locsObjsArray, miles){
			
			restrictedArray = new Array();
			
			for (index = 0; index < locsObjsArray.length; ++index) {
				if(locsObjsArray[index].distance <= miles){
					restrictedArray.push(locsObjsArray[index]);
					
				}
			}
			
			return restrictedArray;
		}
		
		//make the miles variable so we could add a feature later to let users choose radius
		//set default value
		var setMiles = 3;
		document.getElementById("noMiles").innerHTML = setMiles;
		
		//restrict to within x miles
		locsObjsRestricted = restrictRadius(locsObjs, setMiles);
		
		//list them on the page
		for (index = 0; index < locsObjsRestricted.length; ++index) {
			var listNode = document.createElement("li");
			var aNode = document.createElement("a");
			aNode.href = "restaurant.php?id=" + locsObjsRestricted[index].id;
			var textnode = document.createTextNode(locsObjsRestricted[index].name);
			listNode.appendChild(aNode);
			aNode.appendChild(textnode);
			listNode.insertAdjacentHTML("beforeend", " " + Number(locsObjsRestricted[index].distance).toFixed(2) + " miles");
			document.getElementById("restList").appendChild(listNode);
		}
			
	}

	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(success);
	} else {
	  error(\'Geo Location is not supported\');
	}

</script>
<article>
	<form>
		<h2>Your nearest restaurants within <span id="noMiles">x</span> miles...</h2>
	</form>
	<ul id="restList"></ul>
</article>';
?>