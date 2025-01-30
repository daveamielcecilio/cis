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
                    <a class="nav-link text-white" href="about.php">About Us</a>
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link text-white active" href="team.php">Team</a>
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

        <div class="team-header">
    <h3>Meet the Team</h3>
</div>

<div class="team">
    <div class="members">
        <div class="mem">
            <img src="avatar.svg" alt="avatar.svg">
            <p>Edison Barbacena Oliveros</p>
            <p>Owner</p>
        </div>
        <div class="mem">
            <img src="avatar.svg" alt="avatar.svg">
            <p>Christine Cornel</p>
            <p>Owner</p>
        </div>
        <div class="mem">
            <img src="avatar.svg" alt="avatar.svg">
            <p>Jaymark Avelino</p>
            <p>Manager</p>
        </div>
        <div class="mem">
            <img src="avatar.svg" alt="avatar.svg">
            <p>Jeromel Quanico</p>
            <p>Staff</p>
        </div>
        <div class="mem">
            <img src="avatar.svg" alt="avatar.svg">
            <p>Justine Carl Leyva</p>
            <p>Staff</p>
        </div>
    </div>
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

.team-header {
    text-align: center;
    margin-top: 30px;
}

.team-header h3 {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
}

.team {
    margin-top: 40px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

.members {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.mem {
    text-align: center;
    max-width: 180px;
}

.mem img {
    width: 100%;
    height: auto;
    margin-bottom: 10px;
}

.mem p {
    color: white;
    margin: 5px 0;
}

@media (max-width: 768px) {
    .team-header h3 {
        font-size: 2rem;
    }

    .mem {
        max-width: 150px;
    }

    .mem p {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .team-header h3 {
        font-size: 1.5rem;
    }

    .mem {
        max-width: 120px;
    }

    .mem p {
        font-size: 0.8rem;
    }
}
    </style>


</body>

</html>
