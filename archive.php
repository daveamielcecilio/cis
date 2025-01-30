<?php
session_start();

//connect to database
require_once('connect.php');

if (isset($_POST['archive_item'])) {
    $id = mysqli_real_escape_string($db, $_POST['archive_item']);

    // Fetch the item details from the inventory table
    $query_fetch = "SELECT * FROM inventory WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($db, $query_fetch);

    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);

        $type = mysqli_real_escape_string($db, $item['type']);
        $brand = mysqli_real_escape_string($db, $item['brand']);
        $qty = mysqli_real_escape_string($db, $item['qty']);
        $supply_id = mysqli_real_escape_string($db, $item['supply_id']); 

        // Insert the item into the archived_inventory table
        $query_insert = "INSERT INTO archived (supply_id, type, brand, qty) VALUES ('$supply_id', '$type', '$brand', '$qty')";
        $insert_run = mysqli_query($db, $query_insert);

        if ($insert_run) {
            // Delete the item from the inventory table
            $query_delete = "DELETE FROM inventory WHERE id = '$id'";
            $delete_run = mysqli_query($db, $query_delete);

            if ($delete_run) {
                $_SESSION['message'] = "Item Archived Successfully";
                header("Location: dashboard.php");
                exit(0);
            } else {
                $_SESSION['message'] = "Failed to remove item from inventory";
                header("Location: dashboard.php");
                exit(0);
            }
        } else {
            $_SESSION['message'] = "Failed to archive item";
            header("Location: dashboard.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Item not found";
        header("Location: dashboard.php");
        exit(0);
    }
}
?>