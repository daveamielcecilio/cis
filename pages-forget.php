<?php
session_start();
// Connect to the database
require_once('connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Check if the token and email are present in the URL
if (isset($_GET['token']) && isset($_SESSION['reset_token']) && $_GET['token'] === $_SESSION['reset_token']) {
    // Token matches, allow password change

    // Pre-fill the email field from the URL
    $email = isset($_GET['email']) ? $_GET['email'] : '';

    // Process the password change if the form is submitted
    if (isset($_POST['change_password_btn'])) {
        $newPassword = mysqli_real_escape_string($db, $_POST['new_password']);
        $confirmPassword = mysqli_real_escape_string($db, $_POST['confirm_password']);

        // Check if the email exists in the database
        $query = "SELECT * FROM accounts WHERE email = '$email'";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Proceed if passwords match
            if ($newPassword == $confirmPassword) {
                // Hash the new password
                $newPasswordHash = md5($newPassword);

                // Update the password
                $updateQuery = "UPDATE accounts SET password = '$newPasswordHash' WHERE email = '$email'";
                mysqli_query($db, $updateQuery);
                $affectedRows = mysqli_affected_rows($db);

                if ($affectedRows > 0) {
                    // Send confirmation email
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'stockitapplication@gmail.com'; // Replace with your Gmail account
                        $mail->Password = 'ytoenpqkdrvipnws'; // Replace with your Gmail app password
                        $mail->SMTPSecure = "ssl";
                        $mail->Port = 465;

                        // Recipients
                        $mail->setFrom('your-email@gmail.com', 'StockIT'); // Set the sender's name and email
                        $mail->addAddress($email); // Send email to the email from the URL

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = "Password Change Confirmation";
                        $mail->Body = "
                            <h3>Your password has been successfully updated!</h3>
                            <p>Hello {$row['username']},</p>
                            <p>Your password has been successfully changed. If you did not request this change, please contact support immediately.</p>
                            <p><br>StockIT</p>
                        ";

                        // Send email
                        $mail->send();
                        $_SESSION['message'] = "Password updated successfully. A confirmation email has been sent to $email.";

                        $_SESSION['id'] = $row['id'];

                        // Update session variables (optional)
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['role'] = 'employee';


                        header("Location: dashboard.php"); // Redirect to the dashboard
                        exit();
                    } catch (Exception $e) {
                        error_log("Mailer Error: {$mail->ErrorInfo}");
                        $_SESSION['message'] = "Password updated but email confirmation failed.";
                    }
                } else {
                    $_SESSION['message'] = "Failed to update password.";
                }
            } else {
                $_SESSION['message'] = "The new passwords do not match.";
            }
        } else {
            $_SESSION['message'] = "Email not found.";
        }
    }
} else {
    // Token is missing or invalid
    $_SESSION['message'] = "Invalid or expired reset link.";
    header("Location: page-forget-verify.php");
    exit();
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

<body class="bg-dark" style="backgroung-image: login bg.svg">


    <div class="sufee-login d-flex align-content-center flex-wrap" id="log">
    <div class="picwlogo text-center">
        <div class="pic">
            <img src="logo.svg" alt="Logo" width="200px" class="img-fluid">
        </div>
        <h3>Construction Supplies Inventory System</h3>
    </div>
    <div class="container">
        <div class="login-content">
            <div class="login-form">
                
                <div class="x"><a href="index.php"><i class="fa-solid fa-x"></i></a></div>
                <!-- Displays message and forms -->

                <form method="post">
                    <?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg' class = 'alert alert-danger'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
                    <div class="form-group position-relative">
                        <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $email; ?>" readonly>
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <div class="form-group position-relative">
                        <input type="password" class="form-control" placeholder="New Password" name="new_password" id = "pass" minlength="8" required>
                        <i class="fa-solid fa-lock"></i>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>

                    <div class="form-group position-relative">
                        <input type="password" class="form-control" placeholder="Re-enter Password" name="confirm_password" id = "pass2" minlength="8" required>
                        <i class="fa-solid fa-lock"></i>
                        <div class="invalid-feedback" id="password2Error"></div>
                    </div>

                    <div>
                        <input type="checkbox" id="show-password" class="show-password-checkbox">
                        <label for="show-password">Show Password</label>
                    </div>
                    
                    
                    <button class="btn btn-success btn-flat m-b-30 m-t-30 w-100" id = "login" type="submit" name="change_password_btn">Reset Password</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- validations for password textboxes -->

    <script>
    // Get references to the password fields and checkbox
    const passField = document.getElementById('pass');
    const pass2Field = document.getElementById('pass2');
    const showPasswordCheckbox = document.getElementById('show-password');

    // Add event listener to toggle password visibility for both fields
    showPasswordCheckbox.addEventListener('change', function () {
        const type = this.checked ? 'text' : 'password';
        passField.type = type;
        pass2Field.type = type;
    });
</script>

    <script>
     const passwordInput = document.getElementById("pass");
    const errorMessage = document.getElementById("passwordError");

    // Prevent spaces and show error message
    passwordInput.addEventListener("keypress", function (e) {
        if (e.key === " ") {
            e.preventDefault(); // Block the space
            errorMessage.textContent = "You cannot add space.";
            this.classList.add("is-invalid");
        }
    });

    // Clear error message on input
    passwordInput.addEventListener("input", function () {
        errorMessage.textContent = "";
        this.classList.remove("is-invalid");
    });

    // Remove error message when moving to another input field
    passwordInput.addEventListener("blur", function () {
        errorMessage.textContent = ""; // Clear the error message
        this.classList.remove("is-invalid");
    });
</script>

<script>
     const confirmPasswordInput = document.getElementById("pass2");
    const confirmPasswordError = document.getElementById("password2Error");

    // Prevent spaces and show error message
    confirmPasswordInput.addEventListener("keypress", function (e) {
        if (e.key === " ") {
            e.preventDefault(); // Block the space
            confirmPasswordError.textContent = "You cannot add space.";
            this.classList.add("is-invalid");
        }
    });

    // Clear error message on valid input
    confirmPasswordInput.addEventListener("input", function () {
        confirmPasswordError.textContent = "";
        this.classList.remove("is-invalid");
    });

    // Remove error message when moving to another input field
    confirmPasswordInput.addEventListener("blur", function () {
        confirmPasswordError.textContent = ""; // Clear the error message
        this.classList.remove("is-invalid");
    });
</script>

<style>
  /* Adjust the error message styling */
  .invalid-feedback {
    display: none; /* Hidden by default */
    font-size: 0.875em;
    color: #dc3545;
    position: absolute;
    bottom: -20px;
    left: 0;
  }

  .form-control.is-invalid {
    border-color: #dc3545; /* Red border for invalid input */
  }
</style>


</body>

</html>
