<?php
session_start();
if(  isset($_SESSION['username']) )
{
  header("location:dashboard.php");
  die();
}
//connect to database
require_once('connect.php');

if($db)
{
  if(isset($_POST['login_btn']))
  {
      $username=mysqli_real_escape_string($db,$_POST['username']);
      $password=mysqli_real_escape_string($db,$_POST['password']);
      $password=md5($password); //Remember we hashed password before storing last time
      $sql="SELECT * FROM accounts WHERE (username='$username' OR email='$username') AND password='$password'";
      $result=mysqli_query($db,$sql);
      
      if($result)
      {
     
        if($result && mysqli_num_rows($result) >= 1)
        {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['message']="You are now Loggged In";
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            header("location:dashboard.php");
        }
       else
       {
              $_SESSION['message']="Username and Password combination incorrect";
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
<!-- icon and title -->
<body class="bg-dark" style="backgroung-image: login bg.svg">


    <div class="sufee-login d-flex align-content-center flex-wrap" id="log">
    <div class="picwlogo text-center">
        <div class="pic">
            <img src="logo.svg" alt="Logo" width="200px" class="img-fluid">
        </div>
        <h3>StockIT: Construction Supplies Inventory System</h3>
    </div>
    <div class="container">
        <div class="login-content">
            <div class="login-form">
                <!-- displays message -->
                <?php
                if(isset($_SESSION['message'])) {
                    echo "<div id='error_msg' class = 'alert alert-danger'>".$_SESSION['message']."</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="x"><a href="index.php"><i class="fa-solid fa-x"></i></a></div>

                <form method="post" action="page-login.php">
                    <div class="form-group position-relative">
                        <input type="text" class="form-control" placeholder="Email or Username" name="username" required>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="form-group position-relative">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" minlength="8" required>
                        <i class="fa-solid fa-lock"></i>
                        
                    </div>
                    <div class="checkbox d-flex justify-content-between">
                         <label for="show-password">
                        <input type="checkbox" id="show-password" class="show-password-checkbox">
                       Show Password</label>
                        <label class="pull-right">
                            <a href="page-forget-verify.php">Forgot Password?</a>
                        </label>
                    </div>
                    <input type="submit" class="btn btn-success btn-flat m-b-30 m-t-30 w-100" name="login_btn" value="Sign in" id="login">
                    <div class="register-link m-t-15 text-center">
                        <p>Don't have an account? <a href="page-register.php">Sign Up Here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- show password -->

    <script>
    // Get references to the checkbox and password field
    const passwordField = document.getElementById('password');
    const showPasswordCheckbox = document.getElementById('show-password');

    // Add an event listener to toggle password visibility
    showPasswordCheckbox.addEventListener('change', function () {
        passwordField.type = this.checked ? 'text' : 'password';
    });
</script>


    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


</body>

</html>
