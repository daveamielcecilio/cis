<?php
session_start();

//connect to database
require_once('connect.php');

if (isset($_POST['delete_item'])) {
    $id = mysqli_real_escape_string($db, $_POST['delete_item']);

    // Fetch the item details from the inventory table using the provided ID
    $query_fetch = "SELECT * FROM inventory WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($db, $query_fetch);

    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);

        // Get the supply_id from the inventory table to delete from supplies
        $supply_id = $item['supply_id'];

        // Start a transaction to ensure both deletions happen together
        mysqli_begin_transaction($db);

        try {
            // Delete the item from the inventory table
            $query_delete_inventory = "DELETE FROM inventory WHERE id = '$id'";
            if (!mysqli_query($db, $query_delete_inventory)) {
                throw new Exception("Failed to delete from inventory.");
            }

            

            // Commit the transaction
            mysqli_commit($db);

            $_SESSION['message'] = "Item deleted successfully.";
            header("Location: dashboard.php");
            exit(0);
        } catch (Exception $e) {
            // Rollback the transaction if any deletion fails
            mysqli_roll_back($db);
            $_SESSION['message'] = $e->getMessage();
            header("Location: dashboard.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Item not found in inventory.";
        header("Location: dashboard.php");
        exit(0);
    }
}
?>
