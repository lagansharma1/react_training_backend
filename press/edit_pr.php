<?php
ob_start();
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';

// Sanitize if you want
$pr_id = filter_input(INPUT_GET, 'pr_id', FILTER_VALIDATE_INT);
$operation = filter_input(INPUT_GET, 'operation',FILTER_SANITIZE_STRING); 
($operation == 'edit') ? $edit = true : $edit = false;
$db = getDbInstance();
$brands = $db->get('brands',10);

//serve POST method, After successful insert, redirect to customers.php page.
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	/* echo "<pre>";
	print_r($_POST);
	print_r($_FILES); */
    //Insert timestamp
	$data_to_update['title'] = $_POST['title'];
	$data_to_update['brand_id'] = $_POST['brand_id'];
	$data_to_update['pr_date'] = $_POST['pr_date'];
    $data_to_update['created_at'] = date('Y-m-d H:i:s');
    $db = getDbInstance();
    /* $last_id = $db->insert('pr', $data_to_store); */
	$db->where('id',$pr_id);
    $stat = $db->update('pr', $data_to_update);
	
	/*====== For YT =======*/
	
	$y_cnt = count($_POST['video_url']);
	for($y=0; $y<$y_cnt; $y++){
		$data_to_update_yt['yt_url'] = $_POST['video_url'][$y];
		
		if(!empty($_FILES["video_thumbnail"]["name"][$y])){
			$yt_exp = explode('.',$_FILES["video_thumbnail"]["name"][$y]);
			$yt_image_name = $yt_exp[0].'_'.time().'.'.$yt_exp[1];
			$data_to_update_yt['yt_thumbnail'] = $yt_image_name;
			
			$target_dir = BASE_PATH."/assets/youtube/";
			$target_file = $target_dir . basename($yt_image_name);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["video_thumbnail"]["tmp_name"][$y]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
			
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			} else {
				if (move_uploaded_file($_FILES["video_thumbnail"]["tmp_name"][$y], $target_file)) {
					echo "The file ". htmlspecialchars( basename( $_FILES["video_thumbnail"]["name"][$y])). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}else{
			$data_to_update_yt['yt_thumbnail'] = $_POST['video_yt'][$y];
		}
		
		/* echo "<pre>";print_r($data_to_update_yt); */
		$db->where('yt_id',$_POST['video_yt_id'][$y]);
		$db->where('pr_id',$pr_id);
		$db->update('youtube_data', $data_to_update_yt);
	}
	
	/*====== For News =======*/
	
	$n_cnt = count($_POST['newspaper_url']);
	for($n=0; $n<$n_cnt; $n++){
		$data_to_update_np['np_url'] = $_POST['newspaper_url'][$n];
		
		if(!empty($_FILES["newspaper_thumbnail"]["name"][$n])){
			$np_exp = explode('.',$_FILES["newspaper_thumbnail"]["name"][$n]);
			$np_image_name = $np_exp[0].'_'.time().'.'.$np_exp[1];
			$data_to_update_np['np_thumbnail'] = $np_image_name;
			
			$target_dir = BASE_PATH."/assets/newspaper/";
			$target_file = $target_dir . basename($np_image_name);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["newspaper_thumbnail"]["tmp_name"][$n]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
			
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			} else {
				if (move_uploaded_file($_FILES["newspaper_thumbnail"]["tmp_name"][$n], $target_file)) {
					echo "The file ". htmlspecialchars( basename( $_FILES["newspaper_thumbnail"]["name"][$n])). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}else{
			$data_to_update_np['np_thumbnail'] = $_POST['video_np'][$n];
		}
		
		/* echo "<pre>";print_r($data_to_update_yt); */
		$db->where('np_id',$_POST['video_np_id'][$n]);
		$db->where('pr_id',$pr_id);
		$db->update('newspaper_data', $data_to_update_np);
		echo $db->getLastQuery();
	}
	
	/*====== For confrence =======*/
	
	$c_cnt = count($_POST['cf_url']);
	for($c=0; $c<$c_cnt; $c++){
		$data_to_update_cf['cf_url'] = $_POST['cf_url'][$c];
		
		if(!empty($_FILES["cf_thumbnail"]["name"][$c])){
			$cf_exp = explode('.',$_FILES["cf_thumbnail"]["name"][$c]);
			$cf_image_name = $cf_exp[0].'_'.time().'.'.$cf_exp[1];
			$data_to_update_cf['cf_thumbnail'] = $cf_image_name;
			
			$target_dir = BASE_PATH."/assets/confrence/";
			$target_file = $target_dir . basename($cf_image_name);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["cf_thumbnail"]["tmp_name"][$c]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
			
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			} else {
				if (move_uploaded_file($_FILES["cf_thumbnail"]["tmp_name"][$c], $target_file)) {
					echo "The file ". htmlspecialchars( basename( $_FILES["cf_thumbnail"]["name"][$c])). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}else{
			$data_to_update_cf['cf_thumbnail'] = $_POST['video_cf'][$c];
		}
		
		/* echo "<pre>";print_r($data_to_update_yt); */
		$db->where('cf_id',$_POST['video_cf_id'][$c]);
		$db->where('pr_id',$pr_id);
		$db->update('confrence_data', $data_to_update_cf);
	}
	
	
    if($stat)
    {
    	$_SESSION['success'] = "PR Updated successfully!";
    	header('location: pr.php');
    	exit();
    }
    else
    {
        echo 'insert failed: ' . $db->getLastError();
        exit();
    }
}

//We are using same form for adding and editing. This is a create form so declare $edit = false.
if($edit)
{
    $db->where('id', $pr_id);
    $pr = $db->getOne("pr");
	
	$db->where('pr_id', $pr_id);
    $pr_yt = $db->get("youtube_data");
	
	$db->where('pr_id', $pr_id);
    $pr_np = $db->get("newspaper_data");
	
	$db->where('pr_id', $pr_id);
    $pr_cf = $db->get("confrence_data");
	
	/* echo '<pre>';print_R($pr_yt); */
}

require_once '../includes/header.php'; 
?>
<script type="text/javascript">
$(document).ready(function(){
	
	/*=================================== Youtube ========================================================*/
	
    var maxField = 10;
    var addButton = $('.add_button');
    var wrapper = $('.field_wrapper.video');
    var fieldHTML = '<div><input type="text" name="video_url[]" value="" placeholder="Add Video URL" class="form-control"/><label for="f_name">Add Videos Thumbnail *</label><input type="file" name="video_thumbnail[]" class="form-control"/><a href="javascript:void(0);" class="remove_button"><img src="<?php echo BASEURL;?>/assets/img/remove-icon.png"/></a></div>';
    var x = 1;
    
    $(addButton).click(function(){
        if(x < maxField){ 
            x++;
            $(wrapper).append(fieldHTML);
        }
    });
    
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    });
	
	/*=================================== News ========================================================*/
	
	var maxField_news = 10;
	var addButton_news = $('.add_button_news');
    var wrapper_news = $('.field_wrapper.news');
    var fieldHTML_news = '<div><input type="text" name="newspaper_url[]" value="" placeholder="Add Newspaper URL" class="form-control"/><label for="f_name">Add Newspaper Thumbnail *</label><input type="file" name="newspaper_thumbnail[]" class="form-control"/><a href="javascript:void(0);" class="remove_button_news"><img src="<?php echo BASEURL;?>/assets/img/remove-icon.png"/></a></div>';
    var y = 1;
    
    $(addButton_news).click(function(){
        if(y < maxField_news){ 
            y++;
            $(wrapper_news).append(fieldHTML_news);
        }
    });
    
    $(wrapper_news).on('click', '.remove_button_news', function(e){
        e.preventDefault();
        $(this).parent('div').remove();
        y--;
    });
	
	/*=================================== Confrence Images ========================================================*/	
	
	var maxField_cf = 10;
	var addButton_cf = $('.add_button_cf');
    var wrapper_cf = $('.field_wrapper.conf');
    var fieldHTML_cf = '<div><input type="text" name="cf_url[]" value="" placeholder="Add Confrence Image URL" class="form-control"/><label for="f_name">Add Confrence Thumbnail *</label><input type="file" name="cf_thumbnail[]" class="form-control"/><a href="javascript:void(0);" class="remove_button_cf"><img src="<?php echo BASEURL;?>/assets/img/remove-icon.png"/></a></div>';
    var z = 1;
    
    $(addButton_cf).click(function(){
        if(z < maxField_cf){ 
            z++;
            $(wrapper_cf).append(fieldHTML_cf);
        }
    });
    
    $(wrapper_cf).on('click', '.remove_button_cf', function(e){
        e.preventDefault();
        $(this).parent('div').remove();
        z--;
    });
	
});
</script>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Edit Pr</h2>
		</div>
	</div>
    <form class="form" action="" method="post"  id="customer_form" enctype="multipart/form-data">
       <fieldset>
			<div class="form-group">
				<label for="f_name">Title *</label>
				<input type="text" name="title" value="<?php echo htmlspecialchars($edit ? $pr['title'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Title" class="form-control" required="required" id = "title" >
			</div>
			
			<div class="form-group">
				<label for="f_name">Select Brand *</label>
				<select name="brand_id" class="form-control" required="required">
					<option>--Select Brand--</option>
					<?php if(!empty($brands)){
						foreach($brands as $brand){
						?>
					<option <?php if(!empty($brand) && $pr['brand_id'] == $brand['id']){echo 'selected';}?> value="<?php echo $brand['id']; ?>"><?php echo $brand['brand_name']; ?></option>
					<?php } } ?>
				</select>
			</div>
			
<hr>			
			<div class="form-group field_wrapper video">
				<?php if(!empty($pr_yt)){
					foreach($pr_yt as $pr_yts){
					?>
				<div>
					<label for="f_name">Add Videos *</label>
					<input type="text" name="video_url[]" value="<?php echo htmlspecialchars($edit ? $pr_yts['yt_url'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Add Video URL" class="form-control"/>
					<label for="f_name">Add Videos Thumbnail *</label>
					<input type="file" name="video_thumbnail[]" class="form-control"/>
					<input type="hidden" name="video_yt[]" value="<?php echo htmlspecialchars($edit ? $pr_yts['yt_thumbnail'] : '', ENT_QUOTES, 'UTF-8'); ?>" />
					<input type="hidden" name="video_yt_id[]" value="<?php echo $pr_yts['yt_id']; ?>" />
				</div>
				<?php } }else{?>
					<div>
					<label for="f_name">Add Videos *</label>
					<input type="text" name="video_url[]" value="" placeholder="Add Video URL" class="form-control"/>
					<label for="f_name">Add Videos Thumbnail *</label>
					<input type="file" name="video_thumbnail[]" class="form-control"/>
				</div>					
				<?php } ?>
				<a href="javascript:void(0);" class="add_button" title="Add field"><img src="<?php echo BASEURL;?>/assets/img/add-icon.png"/></a>
				
			</div>
<hr>
			<div class="form-group field_wrapper news">
				<?php if(!empty($pr_np)){
					foreach($pr_np as $pr_nps){
					?>
				<div>
					<label for="f_name">Add Newspaper *</label>
					<input type="text" name="newspaper_url[]" value="<?php echo htmlspecialchars($edit ? $pr_nps['np_url'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Add Newspaper URL" class="form-control"/>
					<label for="f_name">Add Newspaper Thumbnail *</label>
					<input type="file" name="newspaper_thumbnail[]" class="form-control"/>
					<input type="hidden" name="video_np[]" value="<?php echo htmlspecialchars($edit ? $pr_nps['np_thumbnail'] : '', ENT_QUOTES, 'UTF-8'); ?>" />
					<input type="hidden" name="video_np_id[]" value="<?php echo $pr_nps['np_id']; ?>" />
				</div>
				<?php } }else{?>
				<div>
					<label for="f_name">Add Newspaper *</label>
					<input type="text" name="newspaper_url[]" value="" placeholder="Add Newspaper URL" class="form-control"/>
					<label for="f_name">Add Newspaper Thumbnail *</label>
					<input type="file" name="newspaper_thumbnail[]" class="form-control"/>
					
				</div>					
				<?php } ?>
				<a href="javascript:void(0);" class="add_button_news" title="Add field"><img src="<?php echo BASEURL;?>/assets/img/add-icon.png"/></a>
			</div>

<hr>
			<div class="form-group field_wrapper conf">
				<?php if(!empty($pr_cf)){
					foreach($pr_cf as $pr_cfs){
					?>
				<div>
					<label for="f_name">Add Confrence Images *</label>
					<input type="text" name="cf_url[]" value="<?php echo htmlspecialchars($edit ? $pr_cfs['cf_url'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Add Confrence Images URL" class="form-control"/>
					<label for="f_name">Add Confrence Images Thumbnail *</label>
					<input type="file" name="cf_thumbnail[]" class="form-control"/>
					<input type="hidden" name="video_cf[]" value="<?php echo htmlspecialchars($edit ? $pr_cfs['cf_thumbnail'] : '', ENT_QUOTES, 'UTF-8'); ?>" />
					<input type="hidden" name="video_cf_id[]" value="<?php echo $pr_cfs['cf_id']; ?>" />
				</div>
				<?php } }else{?>
				<div>
					<label for="f_name">Add Confrence Images *</label>
					<input type="text" name="cf_url[]" value="" placeholder="Add Confrence Images URL" class="form-control"/>
					<label for="f_name">Add Confrence Images Thumbnail *</label>
					<input type="file" name="cf_thumbnail[]" class="form-control"/>
					
				</div>
				<?php } ?>
				<a href="javascript:void(0);" class="add_button_cf" title="Add field"><img src="<?php echo BASEURL;?>/assets/img/add-icon.png"/></a>
			</div>		 
<hr>
			<div class="form-group">
				<label>PR Date</label>
				<input name="pr_date" value="<?php echo htmlspecialchars($edit ? $pr['pr_date'] : '', ENT_QUOTES, 'UTF-8'); ?>"  placeholder="PR date" class="form-control"  type="date">
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