<?php

require_once ("./src/Database.php");
require_once("./src/Session.php");
session_start();

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] === false) {
  // Set a session variable with the message
  $_SESSION['login_message'] = 'You are not logged in. Please log in to book car';
  // Redirect to login.php
  header("Location: ./login.php");
  exit();
}

$user = Session::get('user');

$name = $user->name;
$email = $user->email;
$phone = $user->phone;
$carid = $_GET['car_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$amount = $_GET['amount'];
//print_r($carid);die;

$sql = "SELECT count(id) as total_cars FROM cars WHERE id = '$carid'";
$res = $db->query($sql);
$total_cars = $res->fetch_object()->total_cars;

if ($total_cars == 0) {
  header('location:index.php');
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - Car rental </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 form-container">
        <h1>Checkout</h1>
        <hr>
        <?php
        if (isset($_POST['submit_form'])) {
          $_SESSION['fname'] = $_POST['fname'];
          $_SESSION['lname'] = $_POST['lname'];
          $_SESSION['email'] = $_POST['email'];
          $_SESSION['mobile'] = $_POST['mobile'];
          $_SESSION['note'] = $_POST['note'];
          $_SESSION['address'] = $_POST['address'];
          $_SESSION['carid'] = $carid;
          $_SESSION['start_date'] = $start_date;
          $_SESSION['end_date'] = $end_date;
          $_SESSION['amount'] = $amount;

          if ($_POST['email'] != '') {
            header("location:pay.php");
          }
        }
        ?>
        <div class="row">
          <div class="col-8">
            <form action="" method="POST">
              <div class="mb-3">
                <label class="label">First Name</label>
                <input type="text" class="form-control" name="fname" value="<?php echo $name ?>">


                <input type="hidden" class="form-control" name="carid" value="<?php echo $carid ?>">
                <input type="hidden" class="form-control" name="start_date" value="<?php echo $start_date ?>">
                <input type="hidden" class="form-control" name="end_date" value="<?php echo $end_date ?>">
                <input type="hidden" class="form-control" name="customer-_id" value="<?php echo $user->id ?>">

              </div>
              <div class="mb-3">
                <label class="label">Last Name</label>
                <input type="text" class="form-control" name="lname" value="<?php echo $name ?>">

              </div>

              <div class="mb-3">
                <label class="label">Email </label>
                <input type="email" class="form-control" name="email" value="<?php echo $email ?>">
              </div>
              <div class="mb-3">
                <label class="label">Mobile</label>
                <input type="number" class="form-control" name="mobile" value="<?php echo $phone ?>">
              </div>
              <div class="mb-3">
                <label class="label">Address</label>
                <textarea name="address" class="form-control" name="address"></textarea>
              </div>
              <div class="mb-3">
                <label class="label">Note</label>
                <textarea name="note" class="form-control" name="note"></textarea>
              </div>
          </div>
          <div class="col-4 text-center">
            <?php
            $sql = "SELECT * FROM cars WHERE id = '$carid'";
            $res = $db->query($sql);
            $car_details = $res->fetch_object();
            $fileName1 = explode('/', $car_details->image1)[4];
            echo '<div class="card" style="width: 18rem;">
  <img class="card-img-top" src="./admin/uploaded-files/cars/' . $fileName1 . '" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title">' . $car_details->car_name . ' </h5>
    <p class="card-text">' . $_GET['amount'] . ' INR</p>
  </div>
</div>';


            ?>
            <br>
            <button type="submit" class="btn btn-primary" name="submit_form">Place Order</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>