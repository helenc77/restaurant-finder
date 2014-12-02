<?php 
//check id is set and returned a result
if(isset($_GET['id']) && !empty($viewRestaurant)){

	$pageTitle = 'Restaurant Finder - '.$viewRestaurant['name']; 

	$pageContent = '<article>
					<h2>'.$viewRestaurant['name'].'</h2>';

	if($viewRestaurant['image'] != '0'){
		$pageContent.= '<img src="uploads/image/main/'.$viewRestaurant['id'].'.'.$viewRestaurant['image'].'" alt="'.$viewRestaurant['name'].'" />';	
	}
						
	$pageContent.= '<p>'.$viewRestaurant['address1'].'<br />';
					
	if($viewRestaurant['address2'] != ''){					
		$pageContent.= $viewRestaurant['address2'].'<br />';
	}
	
	$pageContent.= $viewRestaurant['city'].'<br />
						'.$viewRestaurant['county'].'<br />
						'.$viewRestaurant['postcode'].'</p>';
	
	if($viewRestaurant['tel'] != ''){	
		$pageContent.= '<p>'.$viewRestaurant['tel'].'</p>';
	}
	if($viewRestaurant['email'] != ''){	
		$pageContent.= '<p><a href="mailto:'.$viewRestaurant['email'].'">'.$viewRestaurant['email'].'</a></p>';
	}
	if($viewRestaurant['website'] != ''){	
		$pageContent.= '<p><a href="http://'.$viewRestaurant['website'].'">'.$viewRestaurant['website'].'</a></p>';
	}				
					
	if($viewRestaurant['menu'] == 1){
		$pageContent.= '<p><a href="uploads/pdf/'.$viewRestaurant['id'].'.pdf">Download the '.$viewRestaurant['name'].' menu (PDF)</a></p>';	
	}				
					
	$pageContent.= '</article><h3>How to find '.$viewRestaurant['name'].'</h3>

    <article>
		<div id="mapcontainer"></div>
    </article>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7jmXmGE0W3OMlzxcW1IoGWgUZ_1L4fIQ"></script>
	<script src="js/mapfunctions.js"></script>
<script>

	function success(position) {

		var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		var restCoords = new google.maps.LatLng('.$viewRestaurant['location_ref'].');
	  
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
		
		var markerRest = new google.maps.Marker({
			position: restCoords,
			map: map,
			title:"'.$viewRestaurant['name'].'"
		});
		
		//fit the map to the locations
		// Make an array of the LatLngs of the markers you want to show
		var LatLngList = new Array (
			new google.maps.LatLng('.$viewRestaurant['location_ref'].'),
			new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
		//Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds ();
		//  Go through each...
		for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
		  //  And increase the bounds to take this point
		  bounds.extend (LatLngList[i]);
		}
		//  Fit these bounds to the map
		map.fitBounds (bounds);
		
	}
	  
	';
		
	$pageContent.= '

	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(success);
	} else {
	  error(\'Geo Location is not supported\');
	}

</script>';		
					
} else {
	$pageTitle = 'No restaurant found'; 
	$pageContent = '<p>No restaurants were found.</p>';
}
?>