<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
	session_regenerate_id();
}

//print_r($_SESSION);
//session_unset(); 
//session_destroy(); 

//user requested to view, edit or delete a restaurant?
if(isset($_GET['action'])){

	if(isset($_GET['id'])){
		$updateId = intval($_GET['id']);
	}

	switch($_GET['action']){
		case 'view':
			$updateMode = 'view';
			$viewRestaurant = viewRestaurant($db,$updateId);
			break;
		case 'edit':
			$updateMode = 'edit';
			$viewRestaurant = viewRestaurant($db,$updateId);
			break;	
		case 'add':
			$updateMode = 'add';
			break;	
		case 'delete':
			$updateMode = 'delete';
			break;
	}
	
}

//set up values for the form
if(isset($updateMode)){
	
	if(isset($viewRestaurant)){
	
		$nameFormVal = $viewRestaurant['name'];
		$locFormVal = $viewRestaurant['location_ref'];
		$menuFormVal = $viewRestaurant['menu'];
		$imageFormVal = $viewRestaurant['image'];
		$address1FormVal = $viewRestaurant['address1'];
		$address2FormVal = $viewRestaurant['address2'];
		$cityFormVal = $viewRestaurant['city'];
		$countyFormVal = $viewRestaurant['county'];
		$postcodeFormVal = $viewRestaurant['postcode'];
		$telFormVal = $viewRestaurant['tel'];
		$emailFormVal = $viewRestaurant['email'];
		$websiteFormVal = $viewRestaurant['website'];
		$idFormVal = $viewRestaurant['id'];
		
	} else {
	
		$nameFormVal = '';
		$locFormVal = '';
		$menuFormVal = '';
		$imageFormVal = '';
		$address1FormVal = '';
		$address2FormVal = '';;
		$cityFormVal = '';
		$countyFormVal = '';
		$postcodeFormVal = '';
		$telFormVal = '';
		$emailFormVal = '';
		$websiteFormVal = '';
		$idFormVal = '';
	
	}
}

//has a form been submitted?
if(!empty($_POST)){

	//echo('<pre>');
	//print_r($_POST);
	//echo('</pre>');
	
	//user login form submitted
	if(isset($_POST["username"])){
	
		//echo('the login form was submitted');
		//validate user
		$validUser = validUser($_POST["username"], $_POST["password"], $db);
	
	}
	
	//add restaurant
	if(isset($_POST["formType"]) && $_POST["formType"] == 'addRest'){
		$addRest = addRestaurant($_POST, $_FILES, $db, $_SESSION["user"]);
		$uploadImage = uploadFile($_FILES["image"],'image',$addRest);
		$uploadPdf = uploadFile($_FILES["menu"],'pdf',$addRest);		
	}
	
	//edit restaurant
	if(isset($_POST["formType"]) && $_POST["formType"] == 'editRest'){
		$editRest = editRestaurant($_POST, $_FILES, $db, $_SESSION["user"]);
		$uploadImage = uploadFile($_FILES["image"],'image',$_POST['id']);
		$uploadPdf = uploadFile($_FILES["menu"],'pdf',$_POST['id']);		
	}
	
	//delete restaurant
	if(isset($_POST["formType"]) && $_POST["formType"] == 'delRest'){
		$delRest = deleteRestaurant($_POST, $db);
	}

}

//if a user is logged in, get all the restaurants
if(isset($_SESSION['user'])){
	$getRestaurants = getRestaurants($db);
}
?>