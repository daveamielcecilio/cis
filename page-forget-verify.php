<?php
session_start();

// Connect to the database
require_once('connect.php');

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
        
        // Check if the email exists in the database
        $query = "SELECT * FROM accounts WHERE email = '$email'";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch the user info
            $row = mysqli_fetch_assoc($result);

            // Create a unique token (this will be added to the URL in the verification email)
            $token = bin2hex(random_bytes(50));

            // Store the token temporarily (you may want to create a column to store this in the database)
            $_SESSION['reset_token'] = $token;

            // Send verification email
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'stockitapplication@gmail.com';  // Your Gmail address
                $mail->Password = 'ytoenpqkdrvipnws';  // Your Gmail app password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('your_email@gmail.com', 'StockIT');
                $mail->addAddress($email);  // Send email to the provided email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Change Verification';
                $mail->Body = "
                    <h3>Password Change Verification</h3>
    <p>Hi, <strong>{$row['username']}</strong>,</p>
    <p>We received a request to change your password. If this was you, click on the link below to proceed:</p>
    <a href='http://localhost/cis/pages-forget.php?token={$token}&email={$email}'>Click here to change your password</a>
    <p>If you did not request this change, please ignore this email.</p>
                    ";

                // Send email
                $mail->send();
                $_SESSION['message'] = "A verification link has been sent to your email.";
                header("Location: page-forget-verify.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header("Location: page-forget-verify.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Email not found. Please check the email address.";
            header("Location: page-forget-verify.php");
            exit();
        }
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
                <?php if (isset($_SESSION['message'])) { ?>
    <p class="alert alert-info"><?php echo $_SESSION['message']; ?></p>
    <?php unset($_SESSION['message']); ?>
<?php } ?>
                <div class="x"><a href="index.php"><i class="fa-solid fa-x"></i></a></div>

                <form method="post">
                    <div class="form-group position-relative">
                        <input type="email" class="form-control" placeholder="Email" name="email" id = "email" oninput="validateGmail(this)" required>
                        <i class="fa-solid fa-envelope"></i>
                        <div class="invalid-feedback" id="emailError">Please enter a valid email of user (@gmail.com).</div>
                    </div>
                    
                    
                    <button class="btn btn-success btn-flat m-b-30 m-t-30 w-100" id = "login" type="submit">Submit</button>
                    <a href="page-login.php">Back to Login</a>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<script>
  function validateGmail(input) {
    const errorDiv = document.getElementById('emailError');

    // Check if the email ends with @gmail.com
    if (!input.value.endsWith("@gmail.com")) {
      input.setCustomValidity("Invalid email domain.");
      input.classList.add('is-invalid');
      errorDiv.textContent = "Please enter a valid email of user (@gmail.com).";
    } else {
      // If the email is valid, remove any error messages
      input.setCustomValidity("");
      input.classList.remove('is-invalid');
      errorDiv.textContent = "";
    }
  }

  // Prevent space entry without showing an error message
  document.getElementById("email").addEventListener("keypress", function (e) {
    const inputField = e.target;

    if (e.key === " ") {
      e.preventDefault(); // Block the space
    }
  });
</script>


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

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
