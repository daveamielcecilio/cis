<?php
session_start();
require_once('connect.php'); // Connect to the database

if(isset($_SESSION["username"])) {


if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($db, $_POST['id']); // Get the item ID from the inventory table
    $type = mysqli_real_escape_string($db, $_POST['type']);
    $brand = mysqli_real_escape_string($db, $_POST['brand']);
    $qty = mysqli_real_escape_string($db, $_POST['qty']);

    // Fetch the corresponding supply_id from the inventory table
    $supply_query = "SELECT supply_id FROM inventory WHERE id = '$id' LIMIT 1";
    $supply_result = mysqli_query($db, $supply_query);
    if ($supply_result && mysqli_num_rows($supply_result) > 0) {
        $supply_row = mysqli_fetch_assoc($supply_result);
        $supply_id = $supply_row['supply_id'];

        // Update the supplies table (including qty)
        $update_supplies_query = "UPDATE supplies SET type = '$type', brand = '$brand', qty = '$qty' WHERE id = '$supply_id'";
        $update_supplies_run = mysqli_query($db, $update_supplies_query);

        if ($update_supplies_run) {
            // Update the inventory table (including qty)
            $update_inventory_query = "UPDATE inventory SET type = '$type', brand = '$brand', qty = '$qty' WHERE id = '$id'";
            $update_inventory_run = mysqli_query($db, $update_inventory_query);

            if ($update_inventory_run) {
                if ($qty == 0) {
                    // If quantity is 0, move the item to the archived table
                    $archive_query = "INSERT INTO archived (supply_id, type, brand, qty) 
                                      SELECT supply_id, type, brand, qty FROM inventory WHERE id = '$id'";
                    $archive_run = mysqli_query($db, $archive_query);

                    if ($archive_run) {
                        // Delete the item from the inventory table
                        $delete_inventory_query = "DELETE FROM inventory WHERE id = '$id'";
                        $delete_inventory_run = mysqli_query($db, $delete_inventory_query);

                        if ($delete_inventory_run) {
                            $_SESSION['message'] = "Item moved to archive successfully.";
                            header("Location: dashboard.php");
                            exit();
                        } else {
                            $_SESSION['message'] = "Failed to delete item from inventory.";
                            header("Location: edit-supply.php?id=$id");
                            exit();
                        }
                    } else {
                        $_SESSION['message'] = "Failed to archive item.";
                        header("Location: edit-supply.php?id=$id");
                        exit();
                    }
                } else {
                    $_SESSION['message'] = "Item has been updated.";
                    header("Location: edit-supply.php?id=$id");
                    exit();
                }
            } else {
                $_SESSION['message'] = "Failed to update inventory.";
                header("Location: edit-supply.php?id=$id");
                exit();
            }
        } else {
            $_SESSION['message'] = "Failed to update supplies.";
            header("Location: edit-supply.php?id=$id");
            exit();
        }
    } else {
        $_SESSION['message'] = "Supply not found.";
        header("Location: edit-supply.php?id=$id");
        exit();
    }
}

// Fetch the current data for the item to prefill the form
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $query = "SELECT * FROM inventory WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = "Item not found.";
        header("Location: dashboard.php");
        exit();
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
            <div class="col-sm-8">

            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">


                <div class="row">
                    

                    <div class="col-lg-6">
                        <div class="card"  style="border-radius: 10px; color: #3C0471; font-weight: bold; max-width: none; width: 100%;">
                            <div class="card-header"><i class="fa-solid fa-pen-to-square"></i>   Edit Supply </div>
                            <div class="card-body card-block">

                                <form method = "post" action = "edit-supply.php">

                                    <!-- displays successful message -->

                                    <?php
    if(isset($_SESSION['message']))
    {
         echo "<div class='sufee-alert alert with-close alert-success alert-dismissible fade show'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                <label for="company" class=" form-control-label">Item Type</label>

                                <div class="form-group">
                                    <input type="text" id="type" placeholder="Enter the type of the item" name = "type" value="<?php echo htmlspecialchars($item['type']); ?>" class="form-control" oninput="validateTypeInput(); checkChanges();" required>
                                    <div class="invalid-feedback" id="typeError"></div>
                                </div>
                                <label for="vat" class=" form-control-label">Brand</label>

                                    <div class="form-group">
                                        <input type="text" id="brand" placeholder="Enter the brand of the item" name = "brand" value="<?php echo htmlspecialchars($item['brand']); ?>" class="form-control" oninput="validateBrandInput(); checkChanges();" required>
                                        <div class="invalid-feedback" id="brandError"></div>
                                    </div>
                                    <label for="quantity-readonly" class="form-control-label">Current Quantity</label>
                                        <div class="form-group">
                                            <!-- Readonly textbox to display the current quantity -->
                                            <input type="text" id="current-qty" name="qty" 
                                            value="<?php echo htmlspecialchars($item['qty']); ?>" 
                                            class="form-control" readonly>
                                        </div>

                                    <label for="quantity-adjust" class="form-control-label">Update Quantity</label>
                                        <div class="form-group">
                                            <!-- Editable textbox to adjust the quantity -->
                                            <input type="number" id="adjust-qty" placeholder="Enter adjustment (e.g., -5 or 10)" 
                                            class="form-control" oninput="updateQuantity()">
                                        </div>
                                            
                                                    <input type = "submit" class="btn btn-primary btn-sm" name = "update" value = "Save" id="edit" disabled>

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

                                <!-- validatioins -->

                                <script>
    // Store the original values of the fields
    const originalValues = {
        type: document.getElementById('type').value.trim(),
        brand: document.getElementById('brand').value.trim(),
        qty: parseInt(document.getElementById('current-qty').value, 10)
    };

    // Function to validate the 'type' input field
    function validateTypeInput() {
        const inputField = document.getElementById('type');
        const errorMessage = document.getElementById('typeError');
        const saveButton = document.getElementById('edit');

        // Remove restricted characters and numbers dynamically
        inputField.value = inputField.value.replace(/[0-9+\*\/\\=_<>@$;:'"\.\?#!`~%^&\{\}(),\[\]\\|]/g, "");

        // Remove leading spaces
        inputField.value = inputField.value.replace(/^\s+/, "");

        // Check if input ends with a space or is empty
        if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
            saveButton.disabled = true;
        } else if (inputField.value.trim() === "") {
            errorMessage.textContent = "The field cannot be empty.";
            inputField.classList.add("is-invalid");
            saveButton.disabled = true;
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    }

    // Function to validate the 'type' input field
    function validateBrandInput() {
        const inputField = document.getElementById('brand');
        const errorMessage = document.getElementById('brandError');
        const saveButton = document.getElementById('edit');

        // Remove restricted characters and numbers dynamically
        inputField.value = inputField.value.replace(/[\*\\\=_<>@$;:'"\\?#!`~%^&\{\}\[\]\\|]/g, '');

        // Remove leading spaces
        inputField.value = inputField.value.replace(/^\s+/, "");

        // Check if input ends with a space or is empty
        if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
            saveButton.disabled = true;
        } else if (inputField.value.trim() === "") {
            errorMessage.textContent = "The field cannot be empty.";
            inputField.classList.add("is-invalid");
            saveButton.disabled = true;
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    }

    // Function to check if the inputs have changed
    function checkChanges() {
        const type = document.getElementById('type').value.trim();
        const brand = document.getElementById('brand').value.trim();
        const currentQty = parseInt(document.getElementById('current-qty').value, 10);
        const saveButton = document.getElementById('edit');

        if (type !== originalValues.type || brand !== originalValues.brand || currentQty !== originalValues.qty) {
            const isTypeInvalid = document.getElementById('type').classList.contains("is-invalid");

            // Ensure Save button remains disabled if 'type' has invalid input
            saveButton.disabled = isTypeInvalid;
        } else {
            saveButton.disabled = true;
        }
    }

    // Function to dynamically update the readonly quantity textbox
    function updateQuantity() {
        const currentQty = originalValues.qty; // Original quantity
        const adjustInput = document.getElementById('adjust-qty'); // Adjustment input textbox
        const readonlyQty = document.getElementById('current-qty'); // Readonly quantity textbox
        const saveButton = document.getElementById('edit'); // Save button
        const maxQuantity = 500; // Maximum allowed quantity

        // Get the adjustment value entered by the user
        let adjustment = parseInt(adjustInput.value, 10);

        // Handle invalid inputs (e.g., empty, non-numeric, or just a hyphen)
        if (isNaN(adjustment)) {
            readonlyQty.value = currentQty; // Reset to original quantity
            checkChanges();
            return;
        }

        // Prevent resulting quantity from going below 0
        if (currentQty + adjustment < 0) {
            adjustment = -currentQty; // Cap the adjustment to the maximum allowable deduction
            adjustInput.value = adjustment; // Update the adjustment textbox
        }

        // Prevent resulting quantity from exceeding the maximum limit
        if (currentQty + adjustment > maxQuantity) {
            adjustment = maxQuantity - currentQty; // Cap the adjustment to the maximum allowable addition
            adjustInput.value = adjustment; // Update the adjustment textbox
        }

        // Update the readonly quantity textbox
        readonlyQty.value = currentQty + adjustment;

        // Enable or disable the save button based on changes
        checkChanges();
    }

    // Attach the `updateQuantity` function to the adjustment textbox
    document.getElementById('adjust-qty').addEventListener('input', updateQuantity);
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