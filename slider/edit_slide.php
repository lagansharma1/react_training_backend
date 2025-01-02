<?php
ob_start();
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';


// Sanitize if you want
$slide_id = filter_input(INPUT_GET, 'slide_id', FILTER_VALIDATE_INT);
$operation = filter_input(INPUT_GET, 'operation',FILTER_SANITIZE_STRING); 
($operation == 'edit') ? $edit = true : $edit = false;
 $db = getDbInstance();

//Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    //Get customer id form query string parameter.
    $slide_id = filter_input(INPUT_GET, 'slide_id', FILTER_SANITIZE_STRING);

    //Get input data
	
    $data_to_update = array_filter($_POST);
	
	if($_FILES["slide"]["name"] != ''){
		
		$sl_exp = explode('.',$_FILES["slide"]["name"]);
		$sl_image_name = $sl_exp[0].'_'.time().'.'.$sl_exp[1];
		$data_to_update['slide'] = $sl_image_name;
		
		
		$target_dir = BASE_PATH."/assets/slider/";
		$target_file = $target_dir . basename($sl_image_name);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["slide"]["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
		} else {
			if (move_uploaded_file($_FILES["slide"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["slide"]["name"])). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}else{
		
		$db = getDbInstance();
		$db->where('id',$slide_id);
		$stat = $db->get('slider', 1, array('slide'));
		$data_to_update['slide'] = $stat[0]['slide'];
	}
	$data_to_update['slide_type'] = $_POST['slide_type'];
	$data_to_update['slide_status'] = $_POST['slide_status'];
	
    $db = getDbInstance();
    $db->where('id',$slide_id);
    $stat = $db->update('slider', $data_to_update);
	

    if($stat)
    {
        $_SESSION['success'] = "Slide updated successfully!";
        //Redirect to the listing page,
        header('location: slides.php');
        //Important! Don't execute the rest put the exit/die. 
        exit();
    }
}


//If edit variable is set, we are performing the update operation.
if($edit)
{
    $db->where('id', $slide_id);
    //Get data to pre-populate the form.
    $slide = $db->getOne("slider");
}
?>


<?php
    include_once '../includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Update Slide</h2>
    </div>
    <!-- Flash messages -->
    <?php
        include('../includes/flash_messages.php')
    ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="contact_form">
        
        <fieldset>
			<div class="form-group">
				<label>Slider Image</label>
				<input name="slide" class="form-control" type="file">
			</div>
			
			<div class="form-group">
				<label>Slider Type</label>
				<select name="slide_type" class="form-control">
					<option <?php if($slide['slide_type']==0){echo 'selected';}?> value="0">English</option>
					<option <?php if($slide['slide_type']==1){echo 'selected';}?> value="1">Hindi</option>
				</select>
			</div>
			
			<div class="form-group">
				<label>Slider Status</label>
				<select name="slide_status" class="form-control">
					<option <?php if($slide['slide_status']==0){echo 'selected';}?> value="0">Active</option>
					<option <?php if($slide['slide_status']==1){echo 'selected';}?> value="1">Deactive</option>
				</select>
			</div>

			<div class="form-group text-center">
				<label></label>
				<button type="submit" class="btn btn-warning" >Update <span class="glyphicon glyphicon-send"></span></button>
			</div>            
		</fieldset>
		
    </form>
</div>




<?php include_once '../includes/footer.php'; ?>