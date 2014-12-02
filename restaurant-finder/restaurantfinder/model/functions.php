<?php
//get all the restaurant ids and location refs - will be used for searching most efficiently for nearest
function getAllRestLocs($db){
	
	try {
		$stmt = $db->query('SELECT id, location_ref, name FROM restaurants WHERE deleted = 0;');

		$getRestaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		//do some simple xss prevention
		foreach($getRestaurants as $key => $arr){
			foreach($arr as $arrKey => $arrVal){
				$getRestaurants[$key][$arrKey] = filter_var($arrVal, FILTER_SANITIZE_STRING);
			}
		}
		
		return $getRestaurants;		
		
	} 
	
	catch(PDOException $ex) {
		return 0;
	}
	
}

//make some js arrays for the map
function makeMapMarkers($locsArray){

	$markersArray = array();
	
	$markersStr = '';
	$boundariesStr = '';
	$jsLocsObjs = 'var locsObjs = [];
				';
	
	foreach($locsArray as $val){
	
		$markersStr.= 'new google.maps.Marker({
				position: new google.maps.LatLng('.$val["location_ref"].'),
				map: map,
				title:"'.$val["name"].'"});';
				
		$boundariesStr.= '
			new google.maps.LatLng ('.$val["location_ref"].'),'; 
			
		//populate js objects
		$jsLocsObjs.= 'locsObjs.push({
			id:"'.$val["id"].'",
			name:"'.$val["name"].'",
			location_ref:"'.$val["location_ref"].'",
			distance:"0"
		});';
	
	}
	
	$markersArray['markersStr'] = $markersStr;
	$markersArray['boundariesStr'] = $boundariesStr;
	$markersArray['jsLocsObjs'] = $jsLocsObjs;
	
	return $markersArray;
}

//admin - show all restaurants
function getRestaurants($db){
	
	try {
		$stmt = $db->query('SELECT id, name FROM restaurants WHERE deleted = 0 ORDER BY name;');
		
		$getRestaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		//do some simple xss prevention
		foreach($getRestaurants as $key => $arr){
			foreach($arr as $arrKey => $arrVal){
				$getRestaurants[$key][$arrKey] = filter_var($arrVal, FILTER_SANITIZE_STRING);
			}
		}
		
		return $getRestaurants;
	} 
	
	catch(PDOException $ex) {
		return 0;
	}
	
}

//view restaurant
function viewRestaurant($db, $id){
	
	try {
		$stmt = $db->prepare('SELECT * FROM restaurants WHERE id = :id AND deleted = 0;');
		
		$stmt->bindParam(':id', $id);
		
		$stmt->execute();
		
		$viewRestaurant = $stmt->fetch(PDO::FETCH_ASSOC);
		
		//do some simple xss prevention
		foreach($viewRestaurant as $key => $val){
			$viewRestaurant[$key] = filter_var($val, FILTER_SANITIZE_STRING);
		}
			
		return $viewRestaurant;
	} 
	
	catch(PDOException $ex) {
		return 0;
	}
	
}


//admin - add restaurant
function addRestaurant($postVals, $filesPost, $db, $user){
	
	//were any files uploaded?
	if($filesPost['menu']['name'] != ''){
		$menuVal = 1;
	} else {
		$menuVal = 0;
	}
	
	if($filesPost['image']['name'] != ''){
	
		//get the file extension
		$extnArr = explode('.', $filesPost['image']['name']);
		$extn = strtolower($extnArr[1]);
		$imageVal = $extn;
		
	} else {
		$imageVal = 0;
	}
	
	try {
		$stmt = $db->prepare('INSERT INTO restaurants (name,location_ref,menu,image,address1,address2,city,county,postcode,tel,email,website,user) 
		VALUES (:name,:location_ref,:menu,:image,:address1,:address2,:city,:county,:postcode,:tel,:email,:website,:user)');

		$stmt->bindParam(':name', $postVals['name']);
		$stmt->bindParam(':location_ref', $postVals['location_ref']);
		$stmt->bindParam(':menu', $menuVal);
		$stmt->bindParam(':image', $imageVal);
		$stmt->bindParam(':address1', $postVals['address1']);
		$stmt->bindParam(':address2', $postVals['address2']);
		$stmt->bindParam(':city', $postVals['city']);
		$stmt->bindParam(':county', $postVals['county']);
		$stmt->bindParam(':postcode', $postVals['postcode']);
		$stmt->bindParam(':tel', $postVals['tel']);
		$stmt->bindParam(':email', $postVals['email']);
		$stmt->bindParam(':website', $postVals['website']);
		
		$stmt->bindParam(':user', $user);

		$stmt->execute();
		
		//get the last inserted id and return it for the file uploads
		$id = $db->lastInsertId();
		
		return $id;
	}
	
	catch(PDOException $ex) {
		return 0;
	}
	

}

//admin - edit restaurant
function editRestaurant($postVals, $filesPost, $db, $user){

	//were any pdf files uploaded or is there already a menu?
	if($filesPost['menu']['name'] != '' || $postVals['menu'] == '1'){
		$menuVal = 1;
	} else {
		$menuVal = 0;
	}
	
	//were any image files uploaded or is there already an image?
	if($filesPost['image']['name'] != ''){
	
		//get the file extension
		$extnArr = explode('.', $filesPost['image']['name']);
		$extn = strtolower($extnArr[1]);
		$imageVal = $extn;
		
	} else if($postVals['image'] != '0'){
	
		$imageVal = $postVals['image'];
	
	} else {
		$imageVal = 0;
	}
	
	//are any files being removed?
	if(isset($postVals['removeMenu'])){
		$menuVal = 0;
	}	
	if(isset($postVals['removeImage'])){
		$imageVal = 0;
	}
	
	
	try {
		$stmt = $db->prepare('UPDATE restaurants 
		SET name = :name
		,location_ref = :location_ref
		,menu = :menu
		,image = :image
		,address1 = :address1
		,address2 = :address2
		,city = :city
		,county = :county
		,postcode = :postcode
		,tel = :tel
		,email = :email
		,website = :website
		,user = :user
		WHERE id = :id');

		$stmt->bindParam(':id', $postVals['id']);
		$stmt->bindParam(':name', $postVals['name']);
		$stmt->bindParam(':location_ref', $postVals['location_ref']);
		$stmt->bindParam(':menu', $menuVal);
		$stmt->bindParam(':image', $imageVal);
		$stmt->bindParam(':address1', $postVals['address1']);
		$stmt->bindParam(':address2', $postVals['address2']);
		$stmt->bindParam(':city', $postVals['city']);
		$stmt->bindParam(':county', $postVals['county']);
		$stmt->bindParam(':postcode', $postVals['postcode']);
		$stmt->bindParam(':tel', $postVals['tel']);
		$stmt->bindParam(':email', $postVals['email']);
		$stmt->bindParam(':website', $postVals['website']);
		
		$stmt->bindParam(':user', $user);

		$stmt->execute();
		
		return 1;
	}
	
	catch(PDOException $ex) {
		return 0;
	}

}

//admin - delete restaurant
function deleteRestaurant($postVals, $db){
	
	try {
		$stmt = $db->prepare('UPDATE restaurants SET deleted = 1 WHERE id = :id');

		$stmt->bindParam(':id', $postVals['id']);

		$stmt->execute();
		
		return 1;
	} 
	
	catch(PDOException $ex) {
		return 0;
	}
	
}

function uploadFile($filesPost,$fileType,$restId){

	$uploaded = 1;
	
	//check something has been uploaded
	if($filesPost['name'] != ''){
	
		//set up filepath
		$uploadDir = 'uploads/'.$fileType . '/';

		//limit file size
		if ($filesPost['size'] > 10000000) {
			$uploaded = 0;
		}

		//handle pdfs and images differently
		if($fileType == 'pdf'){
		
			//restrict file types
			if (!($filesPost['type'] == 'application/pdf')) {
				$uploaded = 0;
			}
			
			//upload the file
			$uploadDir = $uploadDir.$restId.'.pdf';
			if($uploaded == 1){
				if (move_uploaded_file($filesPost["tmp_name"], $uploadDir)) {
					$uploaded = 1;
				} else {
					$uploaded = 0;
				}
			}
			
		} else if($fileType == 'image'){
		
			//restrict file types
			$allowedTypes = array('image/jpeg','image/gif','image/png');
			if (!in_array($filesPost['type'],$allowedTypes)) {
				$uploaded = 0;
			}
			
			//resize images
			// Get image size
			list($width, $height) = getimagesize($filesPost["tmp_name"]);

			//do height ratios (main - 500px × variable px, page - 150px × variable px)
			$main_width = 500;
			$main_height = ($main_width/$width) * $height;
			$thumb_width = 150;
			$thumb_height = ($thumb_width/$width) * $height;

			//Load
			if(substr($filesPost['name'], -3)=='jpg'){$source = imagecreatefromjpeg($filesPost["tmp_name"]);}
			if(substr($filesPost['name'], -3)=='gif'){$source = imagecreatefromgif($filesPost["tmp_name"]);}
			if(substr($filesPost['name'], -3)=='png'){$source = imagecreatefrompng($filesPost["tmp_name"]);}

			$main = imagecreatetruecolor($main_width, $main_height);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

			// Resize
			imagecopyresampled($main, $source, 0, 0, 0, 0, $main_width, $main_height, $width, $height);
			imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);

			// Save the images
			if(substr($filesPost['name'], -3)=='jpg'){
				imagejpeg($main, $uploadDir.'main/'.$restId.'.jpg', 85);
				imagejpeg($thumb, $uploadDir.'thumb/'.$restId.'.jpg', 85);
			}
			if(substr($filesPost['name'], -3)=='png'){
				imagepng($main, $uploadDir.'main/'.$restId.'.png', 0);
				imagepng($thumb, $uploadDir.'thumb/'.$restId.'.png', 0);
			}
			if(substr($filesPost['name'], -3)=='gif'){
				imagegif($main, $uploadDir.'main/'.$restId.'.gif');
				imagegif($thumb, $uploadDir.'thumb/'.$restId.'.gif');
			}

			// Free up memory
			imagedestroy($main);
			imagedestroy($thumb);
			
		}
	
	} else {
		$uploaded = 0;
	}

	return $uploaded;
}

//admin - check user login
function validUser($userName, $pwd, $db){
	
	//built in php function
	//echo password_hash("e4teries", PASSWORD_DEFAULT)."\n";
	//created $2y$10$oLq6vntGbA.FKzdIL.abH.59ud.lw0YT3ojDpI/xz2F/Ug81iLbpW

	try {
		$stmt = $db->prepare('SELECT pwdhash FROM users WHERE name = :username LIMIT 1');

		$stmt->bindParam(':username', $userName);

		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		//print_r($result);
	}
	
	catch(PDOException $ex) {
		return 0;
	}

	$validUser = 0;
	
	if (password_verify($pwd, $result["pwdhash"])) {
	
		$validUser = 1;
		
		//set a session for this logged in user
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$_SESSION["user"] = $userName;
		
	} 
	
	return $validUser;

}

//enforce SSL
//if the environment is set in the apache virtual host file...
//or could do it by checking url (less secure as not controlled from the web server)
function requireSSL(){
  if($_SERVER['SERVER_PORT'] != '443' && defined(APPLICATION_ENV) && APPLICATION_ENV != 'local') {
    header('Location: https:// <https:///> ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
  }
}
//TODO: throws a notice if APPLICATION_ENV not set, needs some more work before implementation
//requireSSL();
?>