<?php
include './header.php';
/*if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] == false) {
    header('Location: ./login.php');
    exit();
}*/

require_once './src/Database.php';
require_once './src/Session.php';

//session_start();

$user = Session::get('user');


$id = filter_var($_POST['car_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$total_price = filter_var($_POST['total_price'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$start_date = filter_var($_POST['start_date'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$end_date = filter_var($_POST['end_date'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$customer_id = filter_var($_POST['customer_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$booking_id = uniqid();

$sql = "SELECT * FROM cars WHERE id = '$id'";
$res = $db->query($sql);
$car_details = $res->fetch_object();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['booking_id'] = uniqid();
    $_SESSION['car_id'] = $_POST['car_id'];
    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date'] = $_POST['end_date'];
    $_SESSION['total_price'] = $_POST['total_price'];
    $_SESSION['customer_id'] = $_POST['customer_id'];
    //print_r($_SESSION['car_id'] );die;
    // Redirect to pay.php
    header('Location:./profile.php');
    //exit; // Stop further execution
}

?>
<main id="main">
    <section class="section-bg">
        <div class="container">
            <div class="row" style="padding-top: 120px; padding-bottom:100px">
                <?php if ($user->is_verified == 'Pending'): ?>
                    <div class="col-lg-4 mx-auto">

                        <div class="card ">

                            <div class="card-body text-center">

                                <h5>Your profile is not verified</h5>
                                <h3>Upload documents to verify your profile</h3>
                                <a href="./profile.php" class="btn btn-primary btn-sm">verify profile</a>
                            </div>

                        </div>
                    </div>
                <?php else: ?>

                    <div class="card ">
                        <form action="pay.php" method="POST">

                            <div class="card-body text-center">

                                <h5>Booking Details</h5>
                                <span>Car Name : <?php echo $car_details->car_name ?></span>
                                <span>start time: <?php echo $start_date ?></span>
                                <span>end time: <?php echo $end_date ?></span>
                                <span>Total Price: <?php echo $total_price ?> </span>
                            </div>
                            <input type="hidden" name="car_id" value="<?php echo $id ?>" />
                            <input type="hidden" name="booking_id" value="<?php echo $booking_id ?>" />
                            <input type="hidden" name="total_price" value="<?php echo $total_price ?>" />
                            <input type="hidden" name="start_date" value="<?php echo $start_date ?>" />
                            <input type="hidden" name="end_date" value="<?php echo $end_date ?>" />
                            <input type="hidden" name="customer_id" value="<?php echo $customer_id ?>" />
                            <!--<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="pk_test_51PAnlRSG0EqRj88ti7Gaj5JRv6FXTygLGS0s98IzDZ0SMG7uE0ETDuTfspl14VwgFOfnY9rNXVpIEXTUWb9rbc0D00KcuFaN7V"
                                data-amount=<?php echo str_replace(",","",$total_price) * 100 ?>
                                data-name="<?php echo $car_details->car_name ?>"
                                data-description="<?php echo $car_details->car_name ?>" data-currency="inr"
                                data-locale="auto">
                                </script>-->
                <button type="submit" class="btn btn-primary" name="submit_form">Place Order</button>

                        </form>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        </div>
    </section><!-- #intro -->
</main>

<?php include './footer.php' ?>