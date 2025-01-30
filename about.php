<?php
session_start();
if(  isset($_SESSION['username']) )
{
  header("location:dashboard.php");
  die();
}
//connect to database
require_once('connect.php');

?>

<!doctype html>

<html class="no-js" lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edison Oliveros Hardware Inventory System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="logo.svg">


    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="cis.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link
href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
rel="stylesheet"
/>


</head>

<body class="bg-dark" style="backgroung-image: login bg.svg">


    <header id="header" class="header bg-dark text-white">
    <div class="container">
        <div class="row align-items-center py-2">
            <!-- Logo Section -->
            <div class="col-12 col-md-3 text-center text-md-start mb-2 mb-md-0">
                <a href="index.php">
                    <img src="logo.svg" alt="Logo" width="100" class="img-fluid">
                </a>
            </div>
            <!-- Navigation Section -->
            <div class="col-12 col-md-9">
                <nav class="navbar navbar-expand-md navbar-dark justify-content-md-end">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item me-3">
                    <a class="nav-link text-white" href="index.php">Home</a>
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link text-white active" href="about.php">About Us</a>
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link text-white" href="team.php">Team</a>
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link text-white" href="info.php">Info</a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm rounded me-2" href="page-login.php">Login</a>
                </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

        <div class="about">
        <h3>About Us </h3>
        <br></br>
        <h4>Welcome to Edison Oliveros Hardware Shop, your one-stop destination for all your hardware and construction needs. Founded with a vision to provide high-quality tools, materials, and equipment, we have been a trusted partner for homeowners, contractors, and businesses in our community.</h4>
        <br></br>

        <h4>At Edison Oliveros Hardware Shop, we pride ourselves on offering an extensive range of products, from basic DIY essentials to specialized construction supplies. Our team of knowledgeable and friendly staff is always ready to assist you in finding the right solutions for your projects, big or small. </h4>

        <br></br>
        <h4>With a commitment to quality, affordability, and excellent customer service, we strive to make every visit to our store a satisfying experience. Whether you're building your dream home, tackling a weekend project, or managing a large-scale construction, Edison Oliveros Hardware Shop is here to support you every step of the way </h4>
        </div>
        <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Ensure the header remains styled consistently */
.header {
    background-color: #343a40; /* Dark background */
}

.navbar-nav .nav-link.active {
    font-weight: bold;
    border-bottom: 2px solid #ffffff; /* Highlight active link */
}

.navbar-nav .nav-link:hover {
    text-decoration: underline;
}

/* Adjust spacing for smaller screens */
@media (max-width: 576px) {
    .navbar-nav {
        text-align: center;
    }
}

.about {
    text-align: left;
    margin-top: 30px;
    padding: 0 20px;
}

.about h3 {
    font-size: 2.5rem; /* Desktop */
    font-weight: bold;
    color: white;
}

.about h4 {
    font-size: 1.5rem; /* Desktop */
    color: white;
    margin-bottom: 20px;
    text-align: left; /* Align paragraphs to the left */
}

@media (max-width: 768px) {
    .about h3 {
        font-size: 2rem; /* Smaller for tablets */
    }
    .about h4 {
        font-size: 1.2rem; /* Smaller for tablets */
    }
}

@media (max-width: 576px) {
    .about h3 {
        font-size: 1.5rem; /* Smallest for phones */
    }
    .about h4 {
        font-size: 1rem; /* Smallest for phones */
    }
}
    </style>


</body>

</html>
