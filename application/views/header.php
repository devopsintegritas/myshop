<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	    <title><?php echo isset($header_title) ? $header_title : 'My SHop'; ?></title>
	    <link rel="stylesheet" href="<?php echo SITE_CSS_URL?>bootstrap.min.css">
	    <link rel="stylesheet" href="<?php echo SITE_CSS_URL?>all.min.css">
	    <link rel="stylesheet" href="<?php echo SITE_CSS_URL?>font-awesome.min.css">
	    
	    <link rel="stylesheet" href="<?php echo SITE_CSS_URL?>style.css">
	    <link rel="canonical" href="<?php echo SITE_URL;?>"/>
	    <base href="<?php echo SITE_URL;?>"/> 
	    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
	</head>
<body>
<div class="container-fluid">
    <div class="row min-vh-100">
        <div class="col-12">
            <header class="row">
                <div class="col-12 bg-dark py-2 d-md-block d-none">
                    <div class="row">
                        <div class="col-auto mr-auto">
                            <ul class="top-nav">
                                <li>
                                    <a href="tel:+123-456-7890"><i class="fa fa-phone-square mr-2"></i>+123-456-7890</a>
                                </li>
                                <li>
                                    <a href="mailto:mail@shop.com"><i class="fa fa-envelope mr-2"></i>mail@shop.com</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <ul class="top-nav">
                                <li>
                                	<?php 
                                	if(!empty($this->login_info)) {
                                		echo "<a href='javascript:void(0)'>Welcome ".$this->login_info['full_name']." </a>";
                                	}else {
                                		echo "<a href='".SITE_URL."register'><i class='fas fa-user-edit mr-2'></i>Register</a>";
                                	}
                                	?>
                                </li>
                                <li>
                                	<?php 
                                	if(!empty($this->login_info)) {
                                		echo "<a href='".SITE_URL."logout'><i class='fas fa-user-edit mr-2'></i>Logout</a>";
                                	}else {
                                		echo "<a href='".SITE_URL."login'><i class='fas fa-user-edit mr-2'></i>Login</a>";
                                	}
                                	?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
               
                <div class="col-12 bg-white pt-4">
                    <div class="row">
                        <div class="col-lg-auto">
                            <div class="site-logo text-center text-lg-left">
                                <a href="">E-Commerce</a>
                            </div>
                        </div>
                        <div class="col-lg-5 mx-auto mt-4 mt-lg-0">
                            <form action="#">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="search" class="form-control border-dark" placeholder="Search..." required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-dark"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-auto text-center text-lg-left header-item-holder">
                            <a href="<?php echo SITE_URL.'cart';?>" class="header-item">
                                <i class="fas fa-shopping-bag mr-2"></i><span id="header-qty" class="mr-3">0</span>
                            </a>
                        </div>
                    </div>

                    <!-- Nav -->
                    <div class="row">
                        <nav class="navbar navbar-expand-lg navbar-light bg-white col-12">
                            <button class="navbar-toggler d-lg-none border-0" type="button" data-toggle="collapse" data-target="#mainNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="mainNav">
                                <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                                    <li class="nav-item active">
                                        <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="electronics" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Electronics</a>
                                        <div class="dropdown-menu" aria-labelledby="electronics">
                                            <a class="dropdown-item" href="#">Computers</a>
                                            <a class="dropdown-item" href="#">Mobile Phones</a>
                                            <a class="dropdown-item" href="#">Television Sets</a>
                                            <a class="dropdown-item" href="#">DSLR Cameras</a>
                                            <a class="dropdown-item" href="#">Projectors</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="fashion" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Fashion</a>
                                        <div class="dropdown-menu" aria-labelledby="fashion">
                                            <a class="dropdown-item" href="#">Men's</a>
                                            <a class="dropdown-item" href="#">Women's</a>
                                            <a class="dropdown-item" href="#">Children's</a>
                                            <a class="dropdown-item" href="#">Accessories</a>
                                            <a class="dropdown-item" href="#">Footwear</a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" id="books" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Books</a>
                                        <div class="dropdown-menu" aria-labelledby="books">
                                            <a class="dropdown-item" href="#">Adventure</a>
                                            <a class="dropdown-item" href="#">Horror</a>
                                            <a class="dropdown-item" href="#">Romantic</a>
                                            <a class="dropdown-item" href="#">Children's</a>
                                            <a class="dropdown-item" href="#">Non-Fiction</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
        </div>