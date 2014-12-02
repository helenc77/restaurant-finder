<?php
//check id is set
if(isset($_GET['id'])){

	$updateId = intval($_GET['id']);
	$viewRestaurant = viewRestaurant($db,$updateId);
	
}
?>