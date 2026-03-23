<?php include './header.php';
 
 require_once './src/Database.php';
require_once './src/Session.php';
session_start();

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] === false) {
    header("Location: ./login.php");
    exit();
  }
  

$user = Session::get('user'); 

$customer_id = $user->id;

$sql = "SELECT * FROM customers WHERE id = '$customer_id'";
$res = $db->query($sql);
$profile_details = $res->fetch_object();


?>
<main id="main">
    <section class="section-bg">
        <div class="container">
            
            <div class="row" style="padding-top: 100px; padding-bottom:100px">
           
            
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="formProfile" method="post" action=""
                                enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="name">Name</label>
                                        <input type="hidden" class="form-control" id="id" name="id"
                                            value="<?php echo $user->id ?>">
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo $user->name ?>" placeholder="Name">
                                        <small id="nameError" class="form-text text-danger"></small>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                           value="<?php echo $user->email ?>"   placeholder="Email">
                                        <small id="emailError" class="form-text text-danger"></small>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="inputAddress2">Phone</label>
                                        <input type="text" class="form-control" name="phone" id="phone"
                                        value="<?php echo $user->phone ?>"  placeholder="Phone">
                                        <small id="phoneError" class="form-text text-danger"></small>
                                    </div>
                                </div>
                    
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="inputCity">Address</label>
                                        <textarea type="text" class="form-control" id="address" name="address"
                                            placeholder="Apartment, studio, or floor"><?php echo isset($profile_details->address) ? $profile_details->address : "" ?></textarea>
                                        <small id="addressError" class="form-text text-danger"></small>
                                    </div>
                                </div>
                               
                                
                                
                                <div class="form-row text-right">
                                    <div class="form-group col-lg-4 col-sm-12 offset-lg-8">
                                        <button type="submit" class="btn btn-primary btn-block">submit</button>
                                    </div>
                                </div>
                            </form>
                            <div id="msg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- #intro -->
</main>
<?php include './footer.php' ?>

<script>
const nameError = document.querySelector('#nameError');
const emailError = document.querySelector('#emailError');
const phoneError = document.querySelector('#phoneError');
const addressError = document.querySelector('#addressError');
const dlNoError = document.querySelector('#dlNoError');
const drivingLicenseImage1Error = document.querySelector('#drivingLicenseImage1Error');
const drivingLicenseImage2Error = document.querySelector('#drivingLicenseImage2Error');
const addressProofError = document.querySelector('#addressProofError');
const addressProofNoError = document.querySelector('#addressProofNoError');
const addressProofImageError = document.querySelector('#addressProofImageError');


const formProfile = document.querySelector('#formProfile');

formProfile.addEventListener('submit', function(event) {
    event.preventDefault();
    let data = new FormData(this);
    clearErrorMessage();
    msg.innerHTML = '';
    fetch('./src/profile-update.php', {
            method: 'POST',
            body: data
        })
        .then(res => {
            if (res.status == 200) {
                res.json().then(json => {
                    msg.innerHTML =
                        '<div class="alert alert-success"><strong><i class="fa fa-check"></i> Success! </strong>' +
                        json.msg + '</div>'
                })
            } else {
                res.json().then(json => {
                    if (json.errors) displayErrors(json.errors);
                    msg.innerHTML =
                        '<div class="alert alert-danger"><strong><i class="fa fa-times"></i> Failed! </strong>' +
                        json.msg + '</div>'
                })
            }
        })
        .catch(error => {
            console.error(error);
        })
})

function displayErrors(error) {
    nameError.innerHTML = error.name;
    emailError.innerHTML = error.email;
    phoneError.innerHTML = error.phone;
    addressError.innerHTML = error.address;
    dlNoError.innerHTML = error.driving_license_no;
    drivingLicenseImage1Error.innerHTML = error.driving_license_image1;
    drivingLicenseImage2Error.innerHTML = error.driving_license_image2;
    //addressProofError.innerHTML = error.city;
    addressProofNoError.innerHTML = error.address_proof_no;
    addressProofImageError.innerHTML = error. address_proof_image;

}

function clearErrorMessage() {
    nameError.innerHTML = '';
    emailError.innerHTML = '';
    phoneError.innerHTML = '';
    addressError.innerHTML = '';
    dlNoError.innerHTML = '';
    drivingLicenseImage1Error.innerHTML = '';
    drivingLicenseImage2Error.innerHTML = '';
    addressProofNoError.innerHTML = '';
    addressProofImageError.innerHTML = '';

}
</script>