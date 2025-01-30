<?php
session_start();
// Connect to database
require_once('connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['register_btn'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $password2 = mysqli_real_escape_string($db, $_POST['password2']);
    
    // Check if the email already exists
    $query = "SELECT * FROM accounts WHERE email = '$email'";
    $result = mysqli_query($db, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['message'] = "Email already exists";
        } else {
            if ($password === $password2) {
                // Hash the password before storing for security purposes
                $hashedPassword = md5($password); 
                $sql = "INSERT INTO accounts (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
                
                if (mysqli_query($db, $sql)) {
                    // Send email notification
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'stockitapplication@gmail.com'; // Replace with your Gmail account
                        $mail->Password = 'ytoenpqkdrvipnws'; // Replace with your Gmail app password
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        // Recipients
                        $mail->setFrom('your-email@gmail.com', 'StockIT'); // Sender
                        $mail->addAddress($email); // Recipient's email

                        // Email content
                        $mail->isHTML(true);
                        $mail->Subject = "Account Created Successfully";
                        $mail->Body = "
                            <h3>Welcome, $username!</h3>
                            <p>Your account in StockIT has been successfully created.</p>
                            <p>From:<br>StockIT</p>
                        ";

                        // Send email
                        $mail->send();

                        $_SESSION['message'] = "You are now registered and logged in. Please see your email for notification.";
                    } catch (Exception $e) {
                        error_log("Mailer Error: {$mail->ErrorInfo}");
                        $_SESSION['message'] = "You are now registered and logged in, but email notification failed.";
                    }

                    // Set session variables
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'employee';

                    header("Location: dashboard.php"); // Redirect to the admin page
                    exit();
                } else {
                    $_SESSION['message'] = "Registration failed. Please try again.";
                }

            } else {
                $_SESSION['message'] = "The two passwords do not match";
            }
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

<body class="bg-dark">


    <div class="sufee-login d-flex align-content-center flex-wrap" id="log">
    <div class="picwlogo text-center">
        <div class="pic">
            <img src="logo.svg" alt="Logo" width="200px" class="img-fluid">
        </div>
        <h3>StockIT: Construction Supplies Inventory System</h3>
    </div>

    <!-- Displays message and forms -->
    <div class="container">
        <div class="login-content">
            <div class="login-form">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div id='error_msg' class = 'alert alert-danger'>" . $_SESSION['message'] . "</div>";
                    unset($_SESSION['message']);
                }
                ?>
                                <div class="x"><a href="index.php"><i class="fa-solid fa-x"></i></a></div>

                <form method="post" action="page-register.php">
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" placeholder="User Name" name="username" id="username" maxlength="20" required>
                        <i class="fa-solid fa-user"></i>
                        <div class="invalid-feedback" id="usernameError"></div>
                    </div>
                    <div class="form-group position-relative">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" maxlength="60" oninput="validateGmail(this)" required>
                        <i class="fa-solid fa-envelope"></i>
                        <div class="invalid-feedback" id="emailError">Please enter a valid email of user (@gmail.com).</div>
                    </div>

                    <div class="form-group position-relative">
                        <input type="password" class="form-control" placeholder="Password" name="password" id = "pass" minlength="8" maxlength="16" required>
                        <i class="fa-solid fa-lock"></i>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    <div class="form-group position-relative">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="password2" id = "pass2" minlength="8" maxlength="16" required>
                        <i class="fa-solid fa-lock"></i>
                        <div class="invalid-feedback" id="password2Error"></div>
                    </div>

                    <div>
                        <input type="checkbox" id="show-password" class="show-password-checkbox">
                        <label for="show-password">Show Password</label>
                    </div>

                    <input type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30 w-100" name="register_btn" value="Register" id="login">
                    <div class="register-link m-t-15 text-center">
                        <p>Already have an account? <a href="page-login.php">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
  function validateGmail(input) {
    const errorDiv = document.getElementById('emailError');

    // Check if the email ends with @gmail.com and has a username before it
    const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    if (!emailPattern.test(input.value)) {
      input.setCustomValidity("Invalid email format.");
      input.classList.add('is-invalid');
      errorDiv.textContent = "Please enter a valid email of user (e.g., example@gmail.com).";
    } else {
      // If the email is valid, remove any error messages
      input.setCustomValidity("");
      input.classList.remove('is-invalid');
      errorDiv.textContent = "";
    }
  }

  // Prevent space entry without showing an error message
  document.getElementById("email").addEventListener("keypress", function (e) {
    if (e.key === " ") {
      e.preventDefault(); // Block the space
    }
  });
</script>

<script>
    document.getElementById("username").addEventListener("input", function (e) {
        const inputField = e.target;
        const errorMessage = document.getElementById("usernameError");

        // Remove leading spaces dynamically
        inputField.value = inputField.value.replace(/^\s+/, "");

        // Prevent special characters
        inputField.value = inputField.value.replace(/[^\w\s]/g, ""); // Allows letters, numbers, and underscores only

        // Clear error message while typing
        errorMessage.textContent = "";
        inputField.classList.remove("is-invalid");

        // Remove spaces from the input for the length check
        const valueWithoutSpaces = inputField.value.replace(/\s+/g, "");

        // Check for minimum length requirement (3 characters after removing spaces)
        if (valueWithoutSpaces.length < 3 && valueWithoutSpaces.length > 0) {
            errorMessage.textContent = "Minimum length for username is 3 characters.";
            inputField.classList.add("is-invalid");
        }
    });

    // Validate for trailing space on blur (when moving to another field)
    document.getElementById("username").addEventListener("blur", function (e) {
        const inputField = e.target;
        const errorMessage = document.getElementById("usernameError");

        // Remove spaces from the input for the length check
        const valueWithoutSpaces = inputField.value.replace(/\s+/g, "");

        // Check for trailing space
        if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
        } else if (valueWithoutSpaces.length < 3 && valueWithoutSpaces.length > 0) {
            errorMessage.textContent = "Minimum length for username is 3 characters.";
            inputField.classList.add("is-invalid");
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
    });

    // Optional: Prevent form submission with invalid input
    document.querySelector("form").addEventListener("submit", function (e) {
        const inputField = document.getElementById("username");
        const errorMessage = document.getElementById("usernameError");

        // Remove spaces from the input for the length check
        const valueWithoutSpaces = inputField.value.replace(/\s+/g, "");

        // Check if input is empty or starts with a space
        if (inputField.value.trim() === "") {
            errorMessage.textContent = "User Name cannot be empty or start with a space.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else if (inputField.value.endsWith(" ")) {
            errorMessage.textContent = "You cannot end it on a space.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else if (valueWithoutSpaces.length < 3) {
            errorMessage.textContent = "Minimum length for username is 3 characters.";
            inputField.classList.add("is-invalid");
            e.preventDefault(); // Prevent form submission
        } else {
            errorMessage.textContent = "";
            inputField.classList.remove("is-invalid");
        }
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

  /* Responsive styles using media queries */
  @media (max-width: 768px) {
    .invalid-feedback {
      font-size: 0.8em; /* Slightly smaller font for smaller screens */
      bottom: -18px; /* Adjust position */
    }
  }

  @media (max-width: 576px) {
    .invalid-feedback {
      font-size: 0.75em; /* Even smaller font for very small screens */
      bottom: -16px; /* Further adjust position */
    }

    .form-control.is-invalid {
      border-width: 2px; /* Adjust border width for better visibility */
    }
  }
</style>



</body>

</html>
