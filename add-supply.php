<?php
session_start();
require_once('connect.php'); // Connect to the database

if(isset($_SESSION["username"])) {


if (isset($_POST['submit'])) {
    $type = mysqli_real_escape_string($db, $_POST['type']);
    $brand = mysqli_real_escape_string($db, $_POST['brand']);
    $qty = mysqli_real_escape_string($db, $_POST['qty']);

    // Start transaction
    mysqli_begin_transaction($db);

    try {
        // Insert into supplies table
        $sql_supplies = "INSERT INTO supplies (type, brand, qty) VALUES ('$type', '$brand', '$qty')";
        if (!mysqli_query($db, $sql_supplies)) {
            throw new Exception("Failed to add to supplies table.");
        }

        // Get the last inserted ID for supply_id
        $supply_id = mysqli_insert_id($db);

        // Insert into inventory table with the foreign key
        $sql_inventory = "INSERT INTO inventory (supply_id, type, brand, qty) VALUES ('$supply_id', '$type', '$brand', '$qty')";
        if (!mysqli_query($db, $sql_inventory)) {
            throw new Exception("Failed to add to inventory table.");
        }

        // Commit transaction
        mysqli_commit($db);

        $_SESSION['message'] = "Item has been added to supplies and inventory.";
        header("Location: add-supply.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        mysqli_rollback($db);

        $_SESSION['message'] = "Failed to add item: " . $e->getMessage();
    }
}

    
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

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>


    <link rel="stylesheet" href="cis.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link
href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap"
rel="stylesheet"
/>

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

            <!-- <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><img src="images/logo.png" alt="Logo"></a>
                <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
            </div> -->

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
        <a href="dashboard.php"> Dashboard </a>
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

        </header><!-- /header -->
        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">


                <div class="row">
                    
                    <div class="col-lg-6">
                        <div class="card" style="border-radius: 10px; color: #3C0471; font-weight: bold; max-width: none; width: 100%;">
                            <div class="card-header" ><i class="fa-solid fa-square-plus"></i>   Add Supply</div>
                            <div class="card-body card-block" id="crud">
                                <form method = "post" action = "add-supply.php">
                    <!-- displays successful message -->
                                    <?php
    if(isset($_SESSION['message']))
    {
         echo "<div class='sufee-alert alert with-close alert-success alert-dismissible fade show'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
                                    <label for="company" class=" form-control-label">Item Type</label>

                                <div class="form-group">
                                    <input type="text" id="type" placeholder="Enter the type of the item" name = "type" maxlength = "50" oninput="restrictNumbers(this)" class="form-control" required>
                                    <div class="invalid-feedback" id="typeError"></div>
                                </div>
                                <label for="vat" class=" form-control-label">Brand</label>

                                    <div class="form-group">
                                        <input type="text" id="brand" placeholder="Enter the brand of the item" name = "brand" maxlength = "50" class="form-control"required>
                                        <div class="invalid-feedback" id="brandError"></div>
                                    </div>
                                    <label for="street" class=" form-control-label">Quantity</label>

                                    <div class="form-group">
                                        <input type="text" id="qty" name="qty" class="form-control" pattern="^[1-9]\d*$" 
                                        oninput="this.value=this.value.replace(/\D/g,''); validateQty(this);" required>
                                        <span id="qty-error" style="color: red; display: none;">Quantity must be between 1 and 500.</span>
                                    </div>
                                            
                                                    <input type = "submit" class="btn btn-primary btn-sm" name = "submit" value = "Submit" id="edit">
                                                    <a href = "dashboard.php" class="btn btn-danger btn-sm" id="del">
                                                       Cancel
                                                    </a>
                                                </form>
                                                 </div>
                                            </div>
                                         </div>

                                            </div>
                                        </div><!-- .animated -->
                                    </div><!-- .content -->
                                </div><!-- /#right-panel -->
                                <!-- Right Panel -->

                                <!-- validations -->

                                <script>
                                function validateQty(input) {
                                const errorSpan = document.getElementById('qty-error');
                                const qtyValue = parseInt(input.value);
        
                                // Check if the value is 0 or greater than 500
                                if (qtyValue === 0 || qtyValue > 500) {
                                    errorSpan.style.display = "inline";
                                    input.setCustomValidity("Quantity must be between 1 and 500.");
                                } else {
                                    errorSpan.style.display = "none";
                                    input.setCustomValidity(""); // Reset validation
                                }
                                }
                                </script>

                                <script>
     // Remove leading spaces dynamically
    document.getElementById("type").addEventListener("input", function (e) {
        const inputField = e.target;
        const errorMessage = document.getElementById("typeError");
        this.value = this.value.replace(/[+\*\/\\=_<>@$;:'"\.\?#!`~%^&\{\}(),\[\]\\|]/g, '');

        // Remove leading spaces
        inputField.value = inputField.value.replace(/^\s+/, "");
        
        // Clear error message while typing
        errorMessage.textContent = "";
        inputField.classList.remove("is-invalid");
    });

    // Validate for trailing space on blur (when moving to another field)
    document.getElementById("type").addEventListener("blur", function (e) {
        const inputField = e.target;
        const errorMessage = document.getElementById("typeError");

        if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    });

    // Optional: Prevent form submission with invalid input
    document.querySelector("form").addEventListener("submit", function (e) {
        const inputField = document.getElementById("type");
        const errorMessage = document.getElementById("typeError");

        if (inputField.value.trim() === "") {
            errorMessage.textContent = "The field cannot be empty or start with a space.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    });

    function restrictNumbers(input) {
        // Remove any digits if entered
        input.value = input.value.replace(/[0-9]/g, '');
    }
</script>

<script>
     // Remove leading spaces dynamically
    document.getElementById("brand").addEventListener("input", function (e) {
        const inputField = e.target;
        const errorMessage = document.getElementById("brandError");
        this.value = this.value.replace(/[\*\\\=_<>@$;:'"\\?#!`~%^&\{\}\[\]\\|]/g, '');

        // Remove leading spaces
        inputField.value = inputField.value.replace(/^\s+/, "");
        
        // Clear error message while typing
        errorMessage.textContent = "";
        inputField.classList.remove("is-invalid");
    });

    // Validate for trailing space on blur (when moving to another field)
    document.getElementById("brand").addEventListener("blur", function (e) {
        const inputField = e.target;
        const errorMessage = document.getElementById("brandError");

        if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    });

    // Optional: Prevent form submission with invalid input
    document.querySelector("form").addEventListener("submit", function (e) {
        const inputField = document.getElementById("brand");
        const errorMessage = document.getElementById("brandError");

        if (inputField.value.trim() === "") {
            errorMessage.textContent = "The field cannot be empty or start with a space.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    });
</script>


                            <script src="vendors/jquery/dist/jquery.min.js"></script>
                            <script src="vendors/popper.js/dist/umd/popper.min.js"></script>

                            <script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
                            <script src="vendors/jquery-validation-unobtrusive/dist/jquery.validate.unobtrusive.min.js"></script>

                            <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
                            <script src="assets/js/main.js"></script>

                            
</body>
</html>
<?php } else {header("location:page-login.php");} ?>