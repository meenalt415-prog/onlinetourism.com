<?php include './header.php';
//session_destroy();
if(isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == true){
        header('Location: ./index.php');
        exit();
    }


require_once './src/Database.php';
require_once './src/Session.php';

$err = '';
$msg = '';
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (strlen($name) < 1) {
        $err = "Please enter your name";
    } else if (strlen($email) < 1) {
        $err = "Please enter email";
    } else if (strlen($phone) < 1) {
        $err = "Please enter phone";
    } else if (!preg_match('/^[0-9]{10}+$/', $phone)) {
        $err = "Phone number must be 10 digit";
    } else if (!ctype_digit($phone)) {
        $err = "Please enter valid phone";
    } else if (strlen($password) < 1) {
        $err = "Please choose password";
    } else {

        if ($password != $confirmPassword) {
            $err = "Password doesnot match";
        } else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customers (name,email, phone, password) VALUES ('$name', '$email','$phone','$hash_pass');";

            if ($db->query($sql) === true) {

                $msg = 'Registration has been successfull, Please login';
            } else {
                $err = 'Registration failed please try later';
            }
        }
    }
}


?>


<main id="main">
    <section class="section-bg">
        <div class="container">
            <div class="row" style="padding-top: 120px; padding-bottom:100px">
                <div class="col-lg-4 mx-auto">
                    <div class="card ">



                        <div class="card-body">
                            <form id="formLogin" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <div class="form-group">
                                    <label for="inputUsername">Name</label>
                                    <div class="form-label-group">

                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Enter name">
                                        <small id="nameError" class="form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputUsername">Email</label>
                                    <div class="form-label-group">

                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Enter email">
                                        <small id="emailError" class="form-text text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputUsername">Phone</label>
                                    <div class="form-label-group">

                                        <input type="text" name="phone" id="phone" class="form-control"
                                            placeholder="Enter phone">
                                        <small id="phoneError" class="form-text text-danger"></small>
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
                                <div class="form-group">
                                    <label for="inputPassword">Confirm Password</label>
                                    <div class="form-label-group">

                                        <input type="password" name="confirmPassword" id="confirmPassword"
                                            class="form-control" placeholder="Password">
                                        <small id="passwordError" class="form-text text-danger"></small>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                                <div id="msg" style="margin-top: 15px;"></div>
                            </form>
                            <div class="text-center">
                                <a href="./login.php">Already registered?</a>
                            </div>

                        </div>
                        <?php if (strlen($msg) > 1): ?>
                            <div class="alert alert-success text-center"><strong>Success! </strong><?php echo $msg ?></div>
                        <?php endif ?>
                        <?php if (strlen($err) > 1): ?>
                            <div class="alert alert-danger text-center"><strong>Failed! </strong><?php echo $err ?></div>
                        <?php endif ?>
                    </div>
                </div>


            </div>

        </div>
    </section><!-- #intro -->
</main>
<?php include './footer.php' ?>