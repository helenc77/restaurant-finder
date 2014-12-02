<?php
$getAllRestLocs = getAllRestLocs($db);
//echo('<pre>');
//print_r($getAllRestLocs);
//echo('</pre>');

//make a js array for the map
$mapMarkersArray = makeMapMarkers($getAllRestLocs);
$mapMarkers = $mapMarkersArray['markersStr'];
$boundariesStr = $mapMarkersArray['boundariesStr'];
$jsLocsObjs = $mapMarkersArray['jsLocsObjs'];
//echo($mapMarkers);
?>