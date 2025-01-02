<?php
ob_start();
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';


//serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    //Mass Insert Data. Keep "name" attribute in html form same as column name in mysql table.
    $data_to_store = array_filter($_POST);
	
	$sl_exp = explode('.',$_FILES["slide"]["name"]);
	$sl_image_name = $sl_exp[0].'_'.time().'.'.$sl_exp[1];
	$data_to_store['slide'] = $sl_image_name;
	
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
	

    //Insert timestamp
    $data_to_store['created_at'] = date('Y-m-d H:i:s');
    $db = getDbInstance();
    
    $last_id = $db->insert('slider', $data_to_store);

    if($last_id)
    {
    	$_SESSION['success'] = "Slide added successfully!";
    	header('location: slides.php');
    	exit();
    }
    else
    {
        echo 'insert failed: ' . $db->getLastError();
        exit();
    }
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
$edit = false;

require_once '../includes/header.php'; 
?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Add Assignment</h2>
		</div>
	</div>
    <form class="form" action="" method="post"  id="brands_form" enctype="multipart/form-data">
       <fieldset>
			<div class="form-group">
				<label>Assignment Image</label>
				<input name="slide" class="form-control" type="file">
			</div>
			
			<div class="form-group">
				<label>Assignment Type</label>
				<select name="slide_type" class="form-control">
					<option value="0">English</option>
					<option value="1">Hindi</option>
				</select>
			</div>
			
			<div class="form-group">
				<label>Assignment Status</label>
				<select name="slide_status" class="form-control">
					<option value="0">Active</option>
					<option value="1">Deactive</option>
				</select>
			</div>

			<div class="form-group text-center">
				<label></label>
				<button type="submit" class="btn btn-warning" >Save <span class="glyphicon glyphicon-send"></span></button>
			</div>            
		</fieldset>

    </form>
</div>


<script type="text/javascript">
$(document).ready(function(){
   $("#customer_form").validate({
       rules: {
            f_name: {
                required: true,
                minlength: 3
            },
            l_name: {
                required: true,
                minlength: 3
            },   
        }
    });
});
</script>

<?php include_once '../includes/footer.php'; ?>