<?php
ob_start();
session_start();

require_once '../config/config.php';
require_once '../includes/auth_validate.php';
$db = getDbInstance();

$groups = getGroups($db);




// Serve POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare data for insertion
    $post = array_filter($_POST);
    // echo "<pre>";
    // print_r($post);die;
    $param = ['course_name'=>$post['course_name'],'group_id'=>$post['role']];
    $last_id = $db->insert('courses', $param);
    $video_urls = isset($post['video_url']) ? $post['video_url'] : [];

    
    if (is_array($video_urls['url']) == 1) {
        foreach ($video_urls['url'] as $index => $video_url) {
            // Get corresponding values from the other arrays using the same index
            $video_size  = $video_urls['size'][$index];
            $video_type  = $video_urls['type'][$index];
            $duration    = $video_urls['duration'][$index];
    
            // For debugging, print the variables
           
    
            // Insert into the database
            $result =$db->insert('course_videos', [
                'video_url'    => $video_url,
                'course_id'    => $last_id,
                'video_size'   => $video_size,
                'video_type'   => $video_type,
                'duration'     => $duration,
            ]);
            if (!$result) {
                echo "Error inserting video data: " . $db->getLastError(); // Use your DB library's method to get error details
            }
        }
        
         $_SESSION['success'] = "Video links added successfully!";
         header('location: brands.php');
         exit();
    }
    
}

$edit = false;
require_once '../includes/header.php';
?>
<style>
      
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); 
    display: none;
    z-index: 9999;
}

.loader {
    --dim: 3rem;
    width: var(--dim);
    height: var(--dim);
    animation: spin988 2s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.loader .circle {
    --color: #333;
    --dim: 1.2rem;
    width: var(--dim);
    height: var(--dim);
    background-color: var(--color);
    border-radius: 50%;
    position: absolute;
}

.loader .circle:nth-child(1) {
    top: 0;
    left: 0;
}

.loader .circle:nth-child(2) {
    top: 0;
    right: 0;
}

.loader .circle:nth-child(3) {
    bottom: 0;
    left: 0;
}

.loader .circle:nth-child(4) {
    bottom: 0;
    right: 0;
}

@keyframes spin988 {
    0% {
        transform: scale(1) rotate(0);
    }

    20%, 25% {
        transform: scale(1.3) rotate(90deg);
    }

    45%, 50% {
        transform: scale(1) rotate(180deg);
    }

    70%, 75% {
        transform: scale(1.3) rotate(270deg);
    }

    95%, 100% {
        transform: scale(1) rotate(360deg);
    }
}

</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Add Courses</h2>
        </div>
    </div>
    <form class="form" action="" method="post" id="course_form">
        <fieldset>
            <div class="form-group">
                <label for="course_name">Course Name *</label>
                <input type="text" name="course_name" placeholder="Course Name" class="form-control" required="required">
            </div>
            <div class="form-group">
                <label for="role">Select Role</label>
                <select name="role" class="form-control">
                    <option value="" selected>Select Role</option>
                <?php
                 foreach ($groups as $group) {
            // Assuming 'name' is a column in the 'groups' table
                        echo "<option value='" . htmlspecialchars($group['id']) . "'>" . htmlspecialchars($group['name']) . "</option>";
                    }
                ?>
                    
                </select>
            </div>
            <hr/>
            <div id="video-url-container">
                <div class="form-group video-url-group col-md-3">
                    <label for="video_url">Course Video URL *</label>
                    <input type="text" name="video_url[url][]" placeholder="Video URL" class="form-control videoUrl"  required="required">
                </div>
                <div class="form-group video-url-group col-md-3">
                    <label for="video_size">Video Size *</label>
                    <input type="text" name="video_url[size][]" placeholder="Video URL" class="form-control" required="required">
                </div>
                <div class="form-group video-url-group col-md-3">
                    <label for="video_type">Video Type *</label>
                    <input type="text" name="video_url[type][]" placeholder="Video URL" class="form-control" required="required">
                </div>
                <div class="form-group video-url-group col-md-3">
                    <label for="video_duration">Video Duration *</label>
                    <input type="text" name="video_url[duration][]" placeholder="Video URL" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group">
                <button type="button" id="add-more-btn" class="btn btn-info">Add More</button>
                
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-warning">Save <span class="glyphicon glyphicon-send"></span></button>
            </div>
        </fieldset>
    </form>

         
    <div class="overlay" id="loader" style="display: none;">
    <div class="loader">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
</div>
           

</div>

<script type="text/javascript">
$(document).ready(function () {
   
    $("#course_form").validate({
        rules: {
            video_name: {
                required: true,
                minlength: 3
            },
            "video_url[]": {
                required: true,
                url: true
            }
        }
    });

    
    $("#add-more-btn").click(function () {
        let newField = `
        <div class="cont">
            <div class="row">
                <div class="form-group video-url-group col-md-3">
                    <label for="video_url">Course Video URL *</label>
                    <input type="text" name="video_url[url][]" placeholder="Video URL" class="form-control videoUrl"  required="required">
                    <button type="button" class="remove-btn btn btn-danger btn-sm mt-5" style="margin-top:12px;">Remove</button>
                </div>
                <div class="form-group video-url-group col-md-3">
                    <label for="video_size">Video Size *</label>
                    <input type="text" name="video_url[size][]" placeholder="Video Size" class="form-control" required="required">
                </div>
                <div class="form-group video-url-group col-md-3">
                    <label for="video_type">Video Type *</label>
                    <input type="text" name="video_url[type][]" placeholder="Video Type" class="form-control" required="required">
                </div>
                <div class="form-group video-url-group col-md-3">
                    <label for="video_duration">Video Duration *</label>
                    <input type="text" name="video_url[duration][]" placeholder="Video Duration" class="form-control" required="required">
                </div>
            </div>
        </div>`;
        $("#video-url-container").append(newField);
    });

    
    $("#video-url-container").on("click", ".remove-btn", function () {
        $(this).closest(".cont").remove();
    });

    
    
    $(document).on('blur', '.videoUrl', function () {
        const videoUrl = $(this).val().trim();

        
        if (!videoUrl) {
            alert('Please enter a valid URL.');
            return;
        }

        
        $('#loader').fadeIn();

        const dataToSend = JSON.stringify({ videoUrl });

        $.ajax({
            url: 'https://172.22.3.181:3003/api/v1/filedetailsaws', // API endpoint
            type: 'POST',
            data: dataToSend,
            contentType: 'application/json',
            beforeSend: function() {
                
                $('#loader').fadeIn();
                console.log('Loader shown before sending request');
            },
            success: function (response) {
                
                $('#loader').fadeOut();
                console.log('Loader hidden after success');

                
                $('#response-message').html(
                    `<p style="color: green;">Response: ${JSON.stringify(response)}</p>`
                );
                console.log(response);
                if (response.success && response.details && response.details.contentType) {
                const contentType = response.details.contentType;
                const contentLength = response.details.contentLength;

                // Set content type in the form field
                $("input[name='video_url[type][]']").last().val(contentType);

                // Convert contentLength from bytes to MB
                var contentLengthInMB = contentLength / (1024 * 1024); // Convert bytes to MB
                $("input[name='video_url[size][]']").last().val(contentLengthInMB.toFixed(2) + " MB");

                // Assume you can get the actual bitrate from somewhere, or use a fixed value for now
                const bitrateMbps = 1; // Example: 1 Mbps (replace with actual bitrate, or fetch it dynamically)

                // Convert contentLength from bytes to bits (1 byte = 8 bits)
                const contentLengthInBits = contentLength * 8;
                    console.log(contentLengthInBits);
                // Convert bitrate from Mbps to bps (1 Mbps = 1,000,000 bps)
                const bitrateBps = bitrateMbps * 1000000;
                    console.log("bps",bitrateBps);
                // Calculate the video duration in seconds
                const durationSeconds = contentLengthInBits / bitrateBps;
                
                // Optionally, convert to minutes
                const durationMinutes = durationSeconds / 60;

                // Set the video duration in seconds or minutes
                $("input[name='video_url[duration][]']").last().val(durationSeconds.toFixed(2) + " sec");

                // Or set duration in minutes (if you prefer minutes)
                // $("input[name='video_url[duration][]']").last().val(durationMinutes.toFixed(2) + " min");
            }


                
                // alert("WORKING");
            },
            
            error: function (xhr, status, error) {
                
                $('#loader').fadeOut();
                console.log('Loader hidden after error');

                
                $('#response-message').html(
                    `<p style="color: red;">Error: ${xhr.responseText || error}</p>`
                );
            },
            complete: function() {
                
                $('#loader').fadeOut();
                console.log('Loader hidden after complete');
            }
        });
    });
});




</script>

<?php include_once '../includes/footer.php'; ?>
