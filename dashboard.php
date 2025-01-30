<?php
session_start();

//connect to database
require_once('connect.php');

if(isset($_SESSION["username"])) {
?>
<!doctype html>

<html lang="en">


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
    <!-- topbar -->
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
                    <li class="active">
                        <a href="dashboard.php"> <i class="menu-icon fa fa-solid fa-clipboard-list"></i>Dashboard </a>
                    </li>

                    <li>
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
        <a href="dashboard.php" class="active"> Dashboard </a>
        <a href="archiveditems.php"> Archives </a>
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

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= htmlspecialchars(strtoupper($_SESSION['username'])); ?>
                        </a>

                    

                </div>
            </div>
        </div>

        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fa-solid fa-clipboard-list"></i>   Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">

            <div class="col-xl-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title mb-0">Supplies' Quantity</h4>
                            </div>
                            <!--/.col-->
                            <div class="col-sm-8 hidden-sm-down">
                            
                            </div>
                            <!--/.col-->


                        </div>
                        <!--/.row-->
                        <div class="chart-wrapper mt-4">
                            <div id="chartdiv"></div>
                        </div>

                    </div>
                    
                </div>
            </div>

            <!-- fetch total of employees -->


            <?php
            $query = "SELECT COUNT(*) AS total_users FROM accounts";
            $result = mysqli_query($db, $query);

            if ($result) {
            // Fetch the result and display the count
                $row = mysqli_fetch_assoc($result);
                $total_users = $row['total_users'];
            } else {
                $total_users = 0; // Default value in case the query fails
            }
            ?>

            

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-user text-primary border-primary"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Total Employees</div>
                                <div class="stat-digit"><?php echo $total_users; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
            // Query to count the total number of items in the supplies table
$query = "SELECT COUNT(*) AS total_items FROM inventory";
$result = mysqli_query($db, $query);

if ($result) {
    // Fetch the result and display the count
    $row = mysqli_fetch_assoc($result);
    $total_items = $row['total_items'];
} else {
    $total_items = 0; // Default value in case the query fails
}
?>

            <!-- displays total number of items -->
            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-widget-one">
                            <div class="stat-icon dib"><i class="ti-layout-grid2 text-warning border-warning"></i></div>
                            <div class="stat-content dib">
                                <div class="stat-text">Total Items</div>
                                <div class="stat-digit"><?php echo $total_items; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        
        <!-- displays successfull message -->
        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <?php
    if(isset($_SESSION['message']))
    {
         echo "<div class='sufee-alert alert with-close alert-success alert-dismissible fade show'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
                    <!-- table -->
                        <div class="card">
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
                                    <!-- displays data from inventory table -->
                                    <tbody>
                                        <?php 
                                    $query = "SELECT * FROM inventory";
                                    $query_run = mysqli_query($db, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $row)
                                        {
                                            ?>
                                        <tr>
                                            <!-- the column will become red if the quantity of the is 15 and below -->
                                            <td style="background-color: <?= $row['qty'] <= 15 ? '#f7babf' : 'transparent'; ?>; color: <?= $row['qty'] <= 15 ? '#721c24' : 'black'; ?>;"><?php echo $row['supply_id']; ?>
                                            </td>

                                            <td style="background-color: <?= $row['qty'] <= 15 ? '#f7babf' : 'transparent'; ?>; color: <?= $row['qty'] <= 15 ? '#721c24' : 'black'; ?>;"><?php echo $row['type']; ?>
                                            </td>

                                            <td style="background-color: <?= $row['qty'] <= 15 ? '#f7babf' : 'transparent'; ?>; color: <?= $row['qty'] <= 15 ? '#721c24' : 'black'; ?>;"><?php echo $row['brand']; ?>
                                            </td>

                                            <td style="background-color: <?= $row['qty'] <= 15 ? '#f7babf' : 'transparent'; ?>; 
                                                color: <?= $row['qty'] <= 15 ? '#721c24' : 'black'; ?>; 
                                                font-weight: <?= $row['qty'] <= 15 ? 'bold' : 'normal'; ?>;">
                                                <?php echo $row['qty']; ?>
                                            </td>

                                            <td style="background-color: <?= $row['qty'] <= 15 ? '#f7babf' : 'transparent'; ?>; color: <?= $row['qty'] <= 15 ? '#721c24' : 'black'; ?>;">
                                                <div class="dashbuttons">
                                            <a href="edit-supply.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm" id="edit"><i class="fa-solid fa-pencil"></i></a>
                                            <form action="archive.php" method="POST" class="d-inline">
                                                        <button type="submit" name="archive_item" value="<?=$row['id'];?>" class="btn btn-danger btn-sm" id="del" onClick="return confirm('Are you sure you want to archive this item?')"><i class="fa-solid fa-box-archive"></i></button>
                                                    </form>

                                                    <?php if ($_SESSION['role'] === 'owner') : ?>
                                                    <form action="delete.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_item" value="<?=$row['id'];?>" class="btn btn-danger btn-sm" id="delete" onClick="return confirm('Are you sure you want to delete this item?')"><i class="fa-solid fa-trash"></i></button>
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
                        <a href = "add-supply.php" class="btn btn-warning" id="add" ><i class="fa-solid fa-square-plus"></i> Add Supply</a>
                        <a href = "generatesuppliesrecords.php" class="btn btn-warning" id="add" ><i class="fa-solid fa-print"></i> Print</a>
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

    <?php
$sql_lr = "SELECT * FROM inventory WHERE qty <= 15";
if($result_lr = mysqli_query($db, $sql_lr)) {
  $rowcount_lr=mysqli_num_rows($result_lr);
}

$sql_hr = "SELECT * FROM inventory WHERE qty > 15";
if($result_hr = mysqli_query($db, $sql_hr)) {
  $rowcount_hr=mysqli_num_rows($result_hr);
}
?>
            <!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 250px;
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: true,
  panY: true,
  wheelX: "panX",
  wheelY: "zoomX",
  pinchZoomX: true,
  paddingLeft:0,
  paddingRight:1
}));

// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
cursor.lineY.set("visible", false);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, { 
  minGridDistance: 30, 
  minorGridEnabled: true
});

xRenderer.labels.template.setAll({
  rotation: -90,
  centerY: am5.p50,
  centerX: am5.p100,
  paddingRight: 15
});

xRenderer.grid.template.setAll({
  location: 1
})

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0.3,
  categoryField: "country",
  renderer: xRenderer,
  tooltip: am5.Tooltip.new(root, {})
}));

var yRenderer = am5xy.AxisRendererY.new(root, {
  strokeOpacity: 0.1
})

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0.3,
  renderer: yRenderer
}));

// Create series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Series 1",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  sequencedInterpolation: true,
  categoryXField: "country",
  tooltip: am5.Tooltip.new(root, {
    labelText: "{valueY}"
  })
}));

series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
series.columns.template.adapters.add("fill", function (fill, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

series.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});


// Set data
var data = [{
  country: "Low",
  value: <?php echo $rowcount_lr; ?>
}, {
    country: "Sufficient",
    value: <?php echo $rowcount_hr; ?>
  }];

xAxis.data.setAll(data);
series.data.setAll(data);


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000);
chart.appear(1000, 100);

}); // end am5.ready()
</script>

</body>

</html>
<?php } else {header("location:page-login.php");} ?>