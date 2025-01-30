<?php
session_start();

//connect to database
require_once('connect.php');

if(isset($_SESSION["username"])) {
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
    <link rel="stylesheet" href="vendors/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="cis.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link
href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
rel="stylesheet"
/>


    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>

<body>
<div class="top">
<div class="pic">
   <img src="logo.svg" width="50px">
   </div>
   <div class="cons">
    Stock It: Construction Supplies Inventory System
   </div> 

</div>

    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                <li>
                        <a href="dashboard.php"> <i class="menu-icon fa fa-solid fa-clipboard-list"></i>Dashboard </a>
                    </li>
        
                    <li  class="active">
                        <a href="archiveditems.php"> <i class="menu-icon fa-solid fa-box-archive"></i>Archives </a>
                    </li>

                    <?php if ($_SESSION['role'] === 'owner') : ?>
                    <li>
                        <a href="ownerpage.php"> <i class="menu-icon fa-solid fa-user-tie"></i>Owner's Page </a>
                    </li>
                    <?php endif; ?>

                    <li>
                        <a href="logout.php"><i class="menu-icon fa fa-sign-in"></i> Logout</a>
                    </li>
                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->
    <div class="topnav" >                        
        <a href="dashboard.php"> Dashboard </a>
        <a href="archiveditems.php" class="active"> Archives </a>
        <?php if ($_SESSION['role'] === 'owner') : ?>
        <a href="ownerpage.php">Owner's Page </a>
        <?php endif; ?>
        <a href="logout.php"> Logout</a>
    </div>

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        

                       
                    </div>
                </div>

                <div class="col-sm-5" id="name">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= htmlspecialchars(strtoupper($_SESSION['username'])); ?>
                        </a>
                    

                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa-solid fa-box-archive"></i>   Archives</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- displays successful message -->
        <?php
    if(isset($_SESSION['message']))
    {
         echo "<div class='sufee-alert alert with-close alert-success alert-dismissible fade show'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <!-- table -->
                            <div class="card-body"  style="overflow-x: auto;">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th style="border-top-left-radius: 8px;">ID Number</th>
                                            <th>Item Type</th>
                                            <th>Brand Name</th>
                                            <th>Quantity</th>
                                            <th style="border-top-right-radius: 8px;">Action</th>
                                        </tr>
                                    </thead>
                                    <!-- fetching data from archived table -->
                                    <tbody>
                                        <?php 
                                    $query = "SELECT * FROM archived";
                                    $query_run = mysqli_query($db, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $row)
                                        {
                                            ?>
                                        <tr>
                                            <td><?php echo $row['supply_id']; ?></td>
                                            <td><?php echo $row['type']; ?></td>
                                            <td><?php echo $row['brand']; ?></td>
                                            <td><?php echo $row['qty']; ?></td>
                                            <td>
                                                <div class="full">
                                            <form action="unarchive.php" method="POST" class="d-inline">
                                                        <button type="submit" name="unarchive_item" value="<?=$row['id'];?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to archive this item?')" id="del"><i class="fa-solid fa-box-archive"></i>  Unarchive</button>
                                                    </form>

                                                    <?php if ($_SESSION['role'] === 'owner') : ?>
                                                    <form action="archivedpagedelete.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_item" value="<?=$row['id'];?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete this item?')" id="delete"><i class="fa-solid fa-trash"></i>  Delete</button>
                                                    </form>
                                                    <?php endif; ?>
                                                    </div>

                                                    <div class="nofull">
                                            <form action="unarchive.php" method="POST" class="d-inline">
                                                        <button type="submit" name="unarchive_item" value="<?=$row['id'];?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to archive this item?')" id="del"><i class="fa-solid fa-box-archive"></i></button>
                                                    </form>

                                                    <?php if ($_SESSION['role'] === 'owner') : ?>
                                                    <form action="archivedpagedelete.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_item" value="<?=$row['id'];?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete this item?')" id="delete"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                    <?php endif; ?>
                                                    </div>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href = "generatesuppliesrecordsarchive.php" class="btn btn-warning" id="add" ><i class="fa-solid fa-print"></i> Print</a>
                    </div>


                </div>
            </div><!-- .animated -->
        </div><!-- .content -->

    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/js/init-scripts/data-table/datatables-init.js"></script>


    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/widgets.js"></script>
    <script src="vendors/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script>
        (function($) {
            "use strict";

            jQuery('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#1de9b6',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#1de9b6', '#03a9f5'],
                normalizeFunction: 'polynomial'
            });
        })(jQuery);
    </script>

</body>

</html>
<?php } else {header("location:page-login.php");} ?>