<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Hiims</title>

        <!-- Bootstrap Core CSS -->
        <link  rel="stylesheet" href="<?php echo BASEURL;?>/assets/css/bootstrap.min.css"/>

        <!-- MetisMenu CSS -->
        <link href="<?php echo BASEURL;?>/assets/js/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="<?php echo BASEURL;?>/assets/css/sb-admin-2.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="<?php echo BASEURL;?>/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="<?php echo BASEURL;?>/assets/js/jquery.min.js" type="text/javascript"></script>

    </head>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true): ?>
                <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="">Administrator</a>
                    </div>
                    <!-- /.navbar-header -->

                    <ul class="nav navbar-top-links navbar-right">
                        <!-- /.dropdown -->

                        <!-- /.dropdown -->
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                                </li>
                                <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                                </li>
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>
                        <!-- /.dropdown -->
                    </ul>
                    <!-- /.navbar-top-links -->

                    <div class="navbar-default sidebar" role="navigation">
                        <div class="sidebar-nav navbar-collapse">
                            <ul class="nav" id="side-menu">
                                <li>
                                    <a href="<?php echo BASEURL;?>/index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                                </li>
								<li <?php echo (CURRENT_PAGE == "press/brands.php" || CURRENT_PAGE == "press/add_brands.php") ? 'class="active"' : ''; ?>>
                                    <a href="#"><i class="fa fa-user-circle fa-fw"></i> COURSES<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
											<a href="<?php echo BASEURL;?>/press/brands.php"><i class="fa fa-plus fa-fw"></i>List COURSES</a>
										</li>
										<li>
											<a href="<?php echo BASEURL;?>/press/add_course.php"><i class="fa fa-plus fa-fw"></i>Add COURSES</a>
										</li>
                                    </ul>
                                </li>
								<li <?php echo (CURRENT_PAGE == "slider/slides.php" || CURRENT_PAGE == "slider/add_slide.php") ? 'class="active"' : ''; ?>>
                                    <a href="#"><i class="fa fa-user-circle fa-fw"></i> Assignment<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li>
											<a href="<?php echo BASEURL;?>/slider/slides.php"><i class="fa fa-plus fa-fw"></i>Add Assignment</a>
										</li>
										<li>
											<a href="<?php echo BASEURL;?>/slider/add_slide.php"><i class="fa fa-plus fa-fw"></i>list Assignment</a>
										</li>
                                    </ul>
                                </li>
								
								
                                
                            </ul>
                        </div>
                        <!-- /.sidebar-collapse -->
                    </div>
                    <!-- /.navbar-static-side -->
                </nav>
            <?php endif;?>
            <!-- The End of the Header -->