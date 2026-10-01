<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>

    <meta charset="UTF-8">

    <meta name="description" content="DJoz Template">

    <meta name="keywords" content="DJoz, music, DJ, creative">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>DJoz | Template</title>


    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">


    <!-- CSS -->

    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">

    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">

    <link rel="stylesheet" href="css/barfiller.css" type="text/css">

    <link rel="stylesheet" href="css/nowfont.css" type="text/css">

    <link rel="stylesheet" href="css/rockville.css" type="text/css">

    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">

    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">

    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">

    <link rel="stylesheet" href="css/style.css" type="text/css">


    <style>
    .logout-btn {
        color: #ffffff !important;
        background: #ff0055;
        padding: 8px 18px !important;
        border-radius: 4px;
        transition: 0.3s;
    }

    .logout-btn:hover {
        background: #ffffff;
        color: #ff0055 !important;
    }
    </style>

</head>


<body>


    <!-- Preloader -->

    <div id="preloder">

        <div class="loader"></div>

    </div>


    <!-- Header -->

    <header class="header">

        <div class="container">

            <div class="row">


                <!-- Logo -->

                <div class="col-lg-2 col-md-2">

                    <div class="header__logo">

                        <a href="./index.php">

                            <img src="img/logo.png" alt="DJoz">

                        </a>

                    </div>

                </div>


                <!-- Navigation -->

                <div class="col-lg-10 col-md-10">

                    <div class="header__nav">


                        <nav class="header__menu mobile-menu">

                            <ul>


                                <li class="active">

                                    <a href="./index.php">
                                        Home
                                    </a>

                                </li>


                                <li>

                                    <a href="./about.php">
                                        About
                                    </a>

                                </li>


                                <li>

                                    <a href="./discography.php">
                                        Discography
                                    </a>

                                </li>


                                <li>

                                    <a href="./tours.php">
                                        Tours
                                    </a>

                                </li>


                                <li>

                                    <a href="./videos.php">
                                        Videos
                                    </a>

                                </li>


                                <li>

                                    <a href="#">
                                        Pages
                                    </a>


                                    <ul class="dropdown">

                                        <li>
                                            <a href="./about.php">
                                                About
                                            </a>
                                        </li>

                                        <li>
                                            <a href="./blog.php">
                                                Blog
                                            </a>
                                        </li>

                                        <li>
                                            <a href="./blog-details.php">
                                                Blog Details
                                            </a>
                                        </li>

                                    </ul>

                                </li>


                                <li>

                                    <a href="./contact.php">
                                        Contact
                                    </a>

                                </li>


                                <!-- LOGOUT -->
                                <?php if (isset($_SESSION['user_id'])): ?>

                                <li>
                                    <a href="logout.php" class="logout-btn">
                                        <i class="fa fa-sign-out"></i>
                                        Logout
                                    </a>
                                </li>

                                <?php endif; ?>
                            </ul>

                        </nav>


                        <!-- Social -->

                        <div class="header__right__social">

                            <a href="#">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="#">
                                <i class="fa fa-instagram"></i>
                            </a>

                            <a href="#">
                                <i class="fa fa-dribbble"></i>
                            </a>

                        </div>


                    </div>

                </div>

            </div>


            <!-- Mobile Menu -->

            <div id="mobile-menu-wrap"></div>

        </div>

    </header>

    <!-- Header End -->