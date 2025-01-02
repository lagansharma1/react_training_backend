<?php
ob_start();
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';


// Sanitize if you want
$brand_id = filter_input(INPUT_GET, 'brand_id', FILTER_VALIDATE_INT);
$operation = filter_input(INPUT_GET, 'operation',FILTER_SANITIZE_STRING); 
($operation == 'edit') ? $edit = true : $edit = false;
 $db = getDbInstance();

//Handle update request. As the form's action attribute is set to the same script, but 'POST' method, 
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    //Get customer id form query string parameter.
    $brand_id = filter_input(INPUT_GET, 'brand_id', FILTER_SANITIZE_STRING);

    //Get input data
    $data_to_update = array_filter($_POST);
	
	if($_FILES["brand_image"]["name"] != ''){
		
		$data_to_update['brand_image'] = $_FILES["brand_image"]["name"];
		
		
		$target_dir = BASE_PATH."/assets/brands/";
		$target_file = $target_dir . basename($_FILES["brand_image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
			$check = getimagesize($_FILES["brand_image"]["tmp_name"]);
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
			if (move_uploaded_file($_FILES["brand_image"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars( basename( $_FILES["brand_image"]["name"])). " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
	}else{
		
		$db = getDbInstance();
		$db->where('id',$brand_id);
		$stat = $db->get('brands', 1, array('brand_image'));
		$data_to_update['brand_image'] = $stat[0]['brand_image'];
	}
	
	
    $db = getDbInstance();
    $db->where('id',$brand_id);
    $stat = $db->update('brands', $data_to_update);
	

    if($stat)
    {
        $_SESSION['success'] = "Brand updated successfully!";
        //Redirect to the listing page,
        header('location: brands.php');
        //Important! Don't execute the rest put the exit/die. 
        exit();
    }
}


//If edit variable is set, we are performing the update operation.
if($edit)
{
    $db->where('id', $brand_id);
    //Get data to pre-populate the form.
    $brand = $db->getOne("brands");
}
?>


<?php
    include_once '../includes/header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <h2 class="page-header">Update Vedio</h2>
    </div>
    <!-- Flash messages -->
    <?php
        include('../includes/flash_messages.php')
    ?>

    <form class="" action="" method="post" enctype="multipart/form-data" id="contact_form">
        
        <fieldset>
			<div class="form-group">
				<label for="f_name">Brand video *</label>
				<input type="text" name="brand_name" value="<?php echo htmlspecialchars($edit ? $brand['brand_name'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Brand Name" class="form-control" required="required" id="brand_name" >
			</div> 
			
			<div class="form-group">
				<label>video Image</label>
				<input name="brand_image" class="form-control" type="file">
			</div>

			<div class="form-group text-center">
				<label></label>
				<button type="submit" class="btn btn-warning" >Update <span class="glyphicon glyphicon-send"></span></button>
			</div>            
		</fieldset>
		
    </form>
</div>




<?php include_once '../includes/footer.php'; ?>