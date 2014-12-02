<?php
$pageTitle = 'Restaurant Finder admin'; 

$pageContent = '<article>';

if(!isset($_SESSION['user'])){
	$pageContent.= '

			<article>
			<h2>Login</h2>
			<form method="post" action="'.htmlentities($_SERVER["PHP_SELF"]).'">
				<label for "username">Username: <input type="text" name="username" id="username" /></label><br />
				<label for "password">Password: <input type="password" name="password" id="password" /></label><br />
				<input type="submit" name="submit" />
			</form>
			</article>';

} else {

	$pageContent.= '	
	
			<script>
			
			jQuery.validator.addMethod("postcodeUK", function(value, element) {
			return this.optional(element) || /[A-Z]{1,2}[0-9R][0-9A-Z]? [0-9][ABD-HJLNP-UW-Z]{2}/i.test(value);
			}, "Please specify a valid Postcode");
			
			jQuery.validator.addMethod("locationRef", function(value, element) {
			return this.optional(element) || /[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/i.test(value);
			}, "Please specify a valid location reference");			
			
			$(document).ready(function () {

				$("#adminForm").validate({ // initialize the plugin
					rules: {
						name: {
							required: true,
						},
						address1: {
							required: true,
						},	
						city: {
							required: true,
						},	
						county: {
							required: true,
						},	
						postcode: {
							required: true,
						},							
						location_ref: {
							required: true,
							minlength: 5
						},
						email: {
							email: true
						}					
					}
				});

			});
			</script>
	
			<article>
				<h2>Restaurants</h2>
				<table>
					<tr>
						<th>name</th>
						<th>view</th>
						<th>edit</th>
						<th>delete</th>
					</tr>';
	
	foreach($getRestaurants as $row){
		$pageContent.= '	
				<tr>
					<td>'.$row['name'].'</td>
					<td><a href="'.htmlentities($_SERVER["PHP_SELF"]).'?action=view&amp;id='.$row['id'].'">view</a></td>
					<td><a href="'.htmlentities($_SERVER["PHP_SELF"]).'?action=edit&amp;id='.$row['id'].'">edit</a></td>
					<td><a href="'.htmlentities($_SERVER["PHP_SELF"]).'?action=delete&amp;id='.$row['id'].'">delete</a></td>
				</tr>';
	}
				
	$pageContent.= '
				</table>
			</article>
			<article>
				<h3><a href="'.htmlentities($_SERVER["PHP_SELF"]).'?action=add">Add a restaurant</a></h3>
			</article>';
			
	if(isset($updateMode)){
	
		//create the form
		$form = '<form method="post" action="'.htmlentities($_SERVER["PHP_SELF"]).'" enctype="multipart/form-data" id="adminForm">

					<fieldset>
						<legend>Basic details</legend>
						<label for="name">Restaurant name: <input type="text" name="name" id="name" value="'.$nameFormVal.'" /></label><br />
						<label for="location_ref">Location co-ordinates: <input class="locationRef" type="text" name="location_ref" id="location_ref" value="'.$locFormVal.'" /></label><br />
					</fieldset>
					<fieldset>
						<legend>Contact details</legend>
						<label for="address1">Address line 1: <input type="text" name="address1" id="address1" value="'.$address1FormVal.'" /></label><br />
						<label for="address2">Address line 2: <input type="text" name="address2" id="address2" value="'.$address2FormVal.'" /></label><br />
						<label for="city">City: <input type="text" name="city" id="city" value="'.$cityFormVal.'" /></label><br />
						<label for="county">County: <input type="text" name="county" id="county" value="'.$countyFormVal.'" /></label><br />
						<label for="postcode">Post code: <input class="postcodeUK" type="text" name="postcode" id="postcode" value="'.$postcodeFormVal.'" /></label><br />
						<label for="tel">Telephone: <input type="text" name="tel" id="tel" value="'.$telFormVal.'" /></label><br />
						<label for="email">Email: <input type="text" name="email" id="email" value="'.$emailFormVal.'" /></label><br />
						<label for="website">Website: <input type="text" name="website" id="website" value="'.$websiteFormVal.'" /></label><br />
					</fieldset>
					<fieldset>
						<legend>Uploads</legend>';	
	
		switch($updateMode){
		
			case 'view':
				if(empty($viewRestaurant)){
					$pageContent = '<p>No restaurants were found.</p>';
				} else {
					$pageContent.= '<article>
					<h2>View this restaurant</h2>
						<p>Restaurant name: '.$viewRestaurant['name'].'</p>
						<p>Location co-ordinates: '.$viewRestaurant['location_ref'].'</p>';
						
					//show menu if present	
					if($viewRestaurant['menu'] != '0'){
						$pageContent.= '<p>Menu: <a href="uploads/pdf/'.$viewRestaurant['id'].'.pdf">'.$viewRestaurant['name'].' menu</a></p>';
					} else {
						$pageContent.= '<p>Menu: none</p>';
					}
					
					//show picture if present
					if($viewRestaurant['image'] != '0'){
						$pageContent.= '<p>Image: <br /><img src="uploads/image/thumb/'.$viewRestaurant['id'].'.'.$viewRestaurant['image'].'" alt="'.$viewRestaurant['name'].'" /></p>';
					} else {
						$pageContent.= '<p>Image: none</p>';
					}
						
					$pageContent.= '<p>Address line 1: '.$viewRestaurant['address1'].'</p>
						<p>Address line 2: '.$viewRestaurant['address2'].'</p>
						<p>City: '.$viewRestaurant['city'].'</p>
						<p>County: '.$viewRestaurant['county'].'</p>
						<p>Post code: '.$viewRestaurant['postcode'].'</p>
						<p>Telephone: '.$viewRestaurant['tel'].'</p>
						<p>Email: '.$viewRestaurant['email'].'</p>
						<p>Website: '.$viewRestaurant['website'].'</p>
					</article>';
				}
				break;
				
			case 'edit':
				if(empty($viewRestaurant)){
					$pageContent = '<p>No restaurants were found.</p>';
				} else {
					$pageContent.='<article>
						<h2>Edit this restaurant</h2>';
						
					//if there is already a menu show it	
					$form.= '<br /><label for="menu">Menu: <input type="file" name="menu" id="menu" /></label><br />';
					if($menuFormVal == 1){
						$form.= '<a href="uploads/pdf/'.$idFormVal.'.pdf">'.$nameFormVal.' menu</a> 
								<label for="removeMenu">remove? <input type="checkbox" name="removeMenu" id="removeMenu" /></label>';
					} 

					//if there is already an image show it	
					$form.= '<br /><br /><label for="image">Picture: <input type="file" name="image" id="image" /></label><br />';
					if($imageFormVal != '0'){
						$form.= '<img src="uploads/image/thumb/'.$idFormVal.'.'.$imageFormVal.'" alt="'.$nameFormVal.'" />  
								<label for="removeImage">remove? <input type="checkbox" name="removeImage" id="removeImage" /></label>';
					} 
					
					$form.= '		
						<input type="hidden" name="menu" value="'.$menuFormVal.'">
						<input type="hidden" name="image" value="'.$imageFormVal.'">
					</fieldset>';
						
					$form.= '<input type="hidden" name="id" value="'.$idFormVal.'">
							<input type="hidden" name="formType" value="editRest"><br />
							<input type="submit" name="submit" value="Submit" />
						</form>
					</article>';
					$pageContent.= $form;
				}
				break;
				
			case 'add':
				$pageContent.='<article>
					<h2>Add a restaurant</h2>';
					
				$form.= '<br /><label for="menu">Menu: <input type="file" name="menu" id="menu" /></label><br />
					<br /><br /><label for="image">Picture: <input type="file" name="image" id="image" /></label><br />
				</fieldset>
					<input type="hidden" name="formType" value="addRest"><br />
					<input type="submit" name="submit" value="Submit" />
					</form>
				</article>';
				$pageContent.= $form;
				break;
			
			case 'delete':
				$pageContent.= '<article>
					<h2>Delete this restaurant</h2>
					<p>Are you sure you want to delete this restaurant?</p>
					<form method="post" action="'.htmlentities($_SERVER["PHP_SELF"]).'">
						<input type="hidden" name="id" value="'.$updateId.'">
						<input type="hidden" name="formType" value="delRest"><br />
						<input type="submit" name="submit" value="Submit" />
					</form>
				</article>';
				break;	
				
		}

	}	

}

$pageContent.= '</article>';
?>