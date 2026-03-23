<?php

require_once './src/Database.php';
require_once './src/Session.php';
session_start();

$message = '';

if (isset($_SESSION['login_message'])) {
  $message = $_SESSION['login_message'];
  // Unset the session variable to avoid repeated messages (optional)
  unset($_SESSION['login_message']);
}
echo 'sessionvar_name' . $_SESSION['redirect_url'];
$err = '';
$msg = '';
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (strlen($email) < 1) {
        $err = "Please enter email";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = "Please enter a valid email";
    } else if (strlen($password) < 1) {
        $err = "Please enter password";
    } else {
        $sql = "SELECT * FROM customers WHERE email = '$email'";
        //echo $sql;die;
        $res = $db->query($sql);

        if ($res->num_rows > 0) {
            $user = $res->fetch_object();
            if (password_verify($password, $user->password)) {

                //print_r($_SESSION['redirect_url']);
                if (isset($_SESSION['redirect_url'])) {
                    Session::set('isLogged', true);
                    Session::set('user', $user);
                    //header('Location: ./index.php');
                    //exit();
                    $redirect_url = $_SESSION['redirect_url'];
                    unset($_SESSION['redirect_url']);
                    header("Location: $redirect_url");

                    exit();
                } else {
                    Session::set('isLogged', true);
                    Session::set('user', $user);
                    header('Location: ./index.php');
                    exit();
                }
            } else {
                $err = "Wrong username or password";
            }
        } else {
            $err = "User not found";
        }

    }

}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Online Tourism System</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="A services, mobile and computer repairing"
        name="keywords">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="frame-src https://* ;">
    <meta name="description"
        content="">


    <link rel="canonical" href="">

    <!-- Favicons -->
    <link href="img/faviconn.png" rel="icon">
    <link href="img/faviconn.png" rel="apple-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">

    <!-- Bootstrap CSS File -->
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!--==========================
  Header
  ============================-->
    <header id="header" class="fixed-top">
        <div class="container">

            <div class="logo float-left">
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <h1 class="text-light"><a href="#header"><span>NewBiz</span></a></h1> -->
                <a href="./index.php" class="scrollto"><img src="img/logo-new.png" alt="" class="img-fluid"></a>
            </div>

            <nav class="main-nav float-right d-none d-lg-block">



                <ul>
                    <li class="active"><a href="./index.php">Home</a></li>
                    <li><a href="./packages.php">Explore packages</a></li>
                    <li><a href="./contact.php">Contact Us</a></li>
                    <li><a href="./login.php">Login</a></li>
                    <li><a href="./register.php">Register</a></li>

                </ul>

            </nav><!-- .main-nav -->

        </div>
    </header><!-- #header -->
    <main id="main">
        <section class="section-bg">
            <div class="container">
                <div class="row" style="padding-top: 120px; padding-bottom:100px">
                    <div class="col-lg-4 mx-auto">
                        <div id="msg">
                        <?php if ($message): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                         <?php echo $message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                        <?php endif; ?>
                            <?php if (strlen($err) > 1): ?>
                                <div class="alert alert-danger mt-3 text-center"><strong>Failed! </strong><?php echo $err ?>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="card ">



                            <div class="card-body">
                                <form id="formLogin" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                    <div class="form-group">
                                        <label for="inputUsername">Email</label>
                                        <div class="form-label-group">

                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Enter email">
                                            <small id="usernameError" class="form-text text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword">Password</label>
                                        <div class="form-label-group">

                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="Password">
                                            <small id="passwordError" class="form-text text-danger"></small>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                                    <div id="msg" style="margin-top: 15px;"></div>
                                </form>

                            </div>


                        </div>

                    </div>
        </section><!-- #intro -->
    </main>
    <?php include './footer.php' ?>