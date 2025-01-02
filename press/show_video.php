<?php
ob_start();
session_start();
require_once '../config/config.php';
require_once '../includes/auth_validate.php';

// Get parameters from the URL
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$operation = isset($_GET['operation']) ? $_GET['operation'] : '';

// Validate parameters
if ($course_id <= 0 || $operation !== 'show') {
    echo "Invalid request.";
    exit;
}

// Fetch video details from the database
$db = getDbInstance();
$db->where('course_id', $course_id);
$videos = $db->get('course_videos');

// Check if any videos exist
if (empty($videos)) {
    echo "No videos available for this course.";
    exit;
}
require_once '../includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos for Course ID <?php echo $course_id; ?></title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Include Bootstrap CSS -->
    <style>
        iframe {
            width: 100%;
            height: 500px;
            border: none;
        }
    </style>
</head>
<body>
<<div class="container" id="page-wrapper" style="min-height: 490px;">
    <h1>Videos for Course ID: <?php echo $course_id; ?></h1>
    <div class="row"> <!-- Start of the row to hold columns -->
        <?php foreach ($videos as $video): ?>
            <div class="col-md-6"> <!-- Each video takes up 6 columns on medium screens -->
                <div class="video-container">
                    <!-- Optional: Display the video duration if available -->
                    <!-- <h3><?php echo htmlspecialchars($video['duration']); ?></h3> -->
                    <video width="100%" height="300" controls>
                        <source src="<?php echo htmlspecialchars($video['video_url']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        <?php endforeach; ?>
    </div> <!-- End of the row -->
</div>


</body>
</html>
<?php include_once '../includes/footer.php'; ?>