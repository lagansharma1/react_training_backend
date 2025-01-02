<?php
ob_start();
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';


// Sanitize if you want
$kidneypage_id = filter_input(INPUT_GET, 'kidneypage_id', FILTER_VALIDATE_INT);
$operation = filter_input(INPUT_GET, 'operation',FILTER_SANITIZE_STRING); 
($operation == 'edit') ? $edit = true : $edit = false;
 $db = getDbInstance();

//Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    //Get customer id form query string parameter.
    $kidneypage_id = filter_input(INPUT_GET, 'kidneypage_id', FILTER_SANITIZE_STRING);

    //Get input data
	
    $data_to_update = array_filter($_POST);
	
	if($_FILES["video_image"]["name"] != ''){
		
		$sl_exp = explode('.',$_FILES["video_image"]["name"]);
		$sl_image_name = $sl_exp[0].'_'.time().'.'.$sl_exp[1];
		$data_to_update['video_image'] = $sl_image_name;
		
		
		$target_dir = BASE_PATH."/assets/kidneypage/";
		$target_file = $target_dir . basename($sl_image_name);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["video_image"]["tmp_name"]);
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
			if (move_uploaded_file($_FILES["video_image"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["video_image"]["name"])). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}else{
		
		$db = getDbInstance();
		$db->where('id',$kidneypage_id);
		$stat = $db->get('kidney_page', 1, array('video_image'));
		$data_to_update['video_image'] = $stat[0]['video_image'];
	}
	$data_to_update['title'] = $_POST['title'];
	$data_to_update['video_url'] = $_POST['video_url'];
	
    $db = getDbInstance();
    $db->where('id',$kidneypage_id);
    $stat = $db->update('kidney_page', $data_to_update);
	

    if($stat)
    {
        $_SESSION['success'] = "Kidney Data updated successfully!";
        //Redirect to the listing page,
        header('location: kidneypage.php');
        //Important! Don't execute the rest put the exit/die. 
        exit();
    }
}


//If edit variable is set, we are performing the update operation.
if($edit)
{
    $db->where('id', $kidneypage_id);
    //Get data to pre-populate the form.
    $kidneypage = $db->getOne("kidney_page");
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
				<label>Video Title</label>
				<input name="title" class="form-control" type="text" value="<?php echo htmlspecialchars($edit ? $kidneypage['title'] : '', ENT_QUOTES, 'UTF-8'); ?>">
			</div>
			
			<div class="form-group">
				<label>Video URL</label>
				<input name="video_url" class="form-control" type="text" value="<?php echo htmlspecialchars($edit ? $kidneypage['video_url'] : '', ENT_QUOTES, 'UTF-8'); ?>">
			</div>
			
			<div class="form-group">
				<label>Video Image</label>
				<input name="video_image" class="form-control" type="file">
			</div>

			<div class="form-group text-center">
				<label></label>
				<button type="submit" class="btn btn-warning" >Update <span class="glyphicon glyphicon-send"></span></button>
			</div>            
		</fieldset>
		
    </form>
</div>




<?php include_once '../includes/footer.php'; ?>