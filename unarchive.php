<?php
session_start();

//connect to database
require_once('connect.php');

if (isset($_POST['unarchive_item'])) {
    $id = mysqli_real_escape_string($db, $_POST['unarchive_item']);

    // Fetch the item details from the archived table
    $query_fetch = "SELECT * FROM archived WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($db, $query_fetch);

    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);

        $type = mysqli_real_escape_string($db, $item['type']);
        $brand = mysqli_real_escape_string($db, $item['brand']);
        $qty = mysqli_real_escape_string($db, $item['qty']);
        $supply_id = mysqli_real_escape_string($db, $item['supply_id']); 

        // Insert the item into the inventory table with supply_id
        $query_insert = "INSERT INTO inventory (supply_id, type, brand, qty) VALUES ('$supply_id','$type', '$brand', '$qty')";
        $insert_run = mysqli_query($db, $query_insert);

        if ($insert_run) {
            // Delete the item from the archived table
            $query_delete = "DELETE FROM archived WHERE id = '$id'";
            $delete_run = mysqli_query($db, $query_delete);

            if ($delete_run) {
                $_SESSION['message'] = "Item has been Unarchived Successfully";
                header("Location: archiveditems.php");
                exit(0);
            } else {
                $_SESSION['message'] = "Failed to unarchive item from inventory";
                header("Location: archiveditems.php");
                exit(0);
            }
        } else {
            $_SESSION['message'] = "Failed to unarchive item";
            header("Location: archiveditems.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "Item not found";
        header("Location: archiveditems.php");
        exit(0);
    }
}
?>
