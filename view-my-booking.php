<?php
include './header.php';
require_once './src/Database.php';
require_once './src/Session.php';
session_start();

if (!isset($_SESSION['isLogged']) || $_SESSION['isLogged'] === false) {
    header("Location: ./login.php");
    exit();
}


$user = Session::get('user');


if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Bad request';
    return;
}




$id = $db->real_escape_string($_GET['id']);

$sql = "SELECT * FROM booking WHERE id = '$id'";
$res = $db->query($sql);
$booking_details = $res->fetch_object();

$fileName1 = explode('/', $car_details->image1)[4];

if ($booking_details) {
    // If booking details exist, fetch tour details using the tour_id from the booking details
    $package_id = $booking_details->package_id;
    $sql = "SELECT * FROM tour_packages WHERE id = '$package_id'";
    $res = $db->query($sql);
    $package_details = $res->fetch_object();
    $fileName1 = explode('/', $package_details->image1)[4];
    //print_r($fileName1);die;
    $fileName2 = explode('/', $package_details->image2)[4];
    $fileName3 = explode('/', $package_details->image3)[4];
}



?>

<style>
    .star-rating {
        display: flex;
        gap: 8px;
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .star {
        cursor: pointer;
        color: #ddd;
        transition: all 0.2s ease;
    }

    .star:hover,
    .star.active {
        color: #ffc107;
        transform: scale(1.1);
    }

    .star.hovered {
        color: #ffc107;
    }

    .rating-text {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 5px;
        min-height: 20px;
    }

    .character-count {
        font-size: 0.85rem;
        color: #6c757d;
        text-align: right;
        margin-top: 5px;
    }

    .character-count.warning {
        color: #dc3545;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .submit-btn {
        padding: 12px;
        font-weight: 600;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
        display: none;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        display: none;
        border: 1px solid #c3e6cb;
    }
</style>
<main id="main">
    <section class="section-bg">
        <div class="container">
            <div class="row" style="padding-top: 100px; padding-bottom:100px">

                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Carousel -->
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="./admin/uploaded-files/packages/<?php echo $fileName1 ?>"
                                            class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="./admin/uploaded-files/packages/<?php echo $fileName2 ?>"
                                            class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="./admin/uploaded-files/packages/<?php echo $fileName3 ?>"
                                            class="d-block w-100" alt="...">
                                    </div>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <!-- Card -->
                            <div class="card">
                                <div class="card-header">
                                    Booking details
                                </div>
                                <div class="card-body">
                                    <table class="table tabled-bordered">
                                        <tr>
                                            <td><strong>Booking Id: </strong><?php echo htmlspecialchars($booking_details->booking_id) ?></td>
                                            <td><strong>Booking Date: </strong><?php echo htmlspecialchars($booking_details->booking_date) ?></td>

                                        </tr>
                                     
                                        <tr>
                                            <td colspan="3"><strong>Total Price:</strong> <?php echo htmlspecialchars($booking_details->total_price) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Booking Status.: </strong><span class="badge badge-pill badge-success"><?php echo $booking_details->booking_status; ?></span></td>
                                            <td><strong>Payment Status No: </strong><span class="badge badge-pill badge-info"><?php echo $booking_details->payment_status; ?></span></td>

                                        </tr>
                                    </table>
                                </div>

                                <div class="card-footer bg-white">
                                    <!-- Download receipt button -->
                              <a href="./receipt.php?id=<?php echo urlencode($booking_details->id); ?>" ...>Download Receipt</a>

                                </div>

                            </div>
                            <div class="container mt-5">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal">
                                    Leave a Review
                                </button>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

        </div>
    </section><!-- #intro -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Rating Form -->
            <form class="modal-content" id="ratingForm" method="post" action="" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="bookingModalLabel">Rate Your Experience</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="success-message" id="successMessage">
                        ✓ Thank you for your review! Your feedback has been submitted.
                    </div>

                    <!-- Star Rating -->
                    <div class="mb-4">
                        <label class="form-label">Your Rating</label>
                        <div class="star-rating" id="starRating">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" name="customer" name="customer" value="<?php echo $user->id; ?>">
                        <input type="hidden" name="package" id="package" value="<?php echo $id; ?>">
                        <div class="rating-text" id="ratingText"></div>
                        <div class="error-message" id="ratingError">Please select a rating</div>
                    </div>

                    <!-- Comment Textarea -->
                    <div class="mb-3">
                        <label class="form-label" for="comment">Your Review</label>
                        <textarea
                            class="form-control"
                            id="comment"
                            name="comment"
                            rows="4"
                            maxlength="500"
                            placeholder="Share your experience with us..."
                            required></textarea>
                        <div class="character-count">
                            <span id="charCount">0</span> / 500 characters
                        </div>
                        <div class="error-message" id="commentError">Please write a review (minimum 10 characters)</div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="submit" name="submit" class="btn btn-success w-100 submit-btn">
                        Submit Review
                    </button>
                </div>
            </form>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Get references
        const ratingForm = document.querySelector('#ratingForm');
        const ratingError = document.querySelector('#ratingError');
        const commentError = document.querySelector('#commentError');
        const successMessage = document.querySelector('#successMessage');
        const commentInput = document.querySelector('#comment');
        const charCount = document.querySelector('#charCount');
        const ratingText = document.querySelector('#ratingText');
        const stars = document.querySelectorAll('#starRating .star');
        const charCountDiv = document.querySelector('.character-count');

        let selectedRating = 0;

        // Rating labels
        const ratingLabels = {
            1: 'Poor - Not satisfied',
            2: 'Fair - Below expectations',
            3: 'Good - Meets expectations',
            4: 'Very Good - Exceeds expectations',
            5: 'Excellent - Outstanding!'
        };

        // Star rating click & hover
        stars.forEach(star => {
            star.addEventListener('click', () => {
                selectedRating = parseInt(star.dataset.value);
                updateStars(selectedRating);
                ratingText.textContent = ratingLabels[selectedRating];
                ratingError.style.display = 'none';
            });

            star.addEventListener('mouseenter', () => {
                const hoverValue = parseInt(star.dataset.value);
                stars.forEach((s, i) => s.classList.toggle('hovered', i < hoverValue));
                ratingText.textContent = ratingLabels[hoverValue];
            });
        });

        // Remove hover effect
        document.getElementById('starRating').addEventListener('mouseleave', () => {
            stars.forEach(s => s.classList.remove('hovered'));
            ratingText.textContent = selectedRating ? ratingLabels[selectedRating] : '';
        });

        // Update stars visually
        function updateStars(rating) {
            stars.forEach((star, i) => {
                star.classList.toggle('active', i < rating);
            });
        }

        // Character counter
        commentInput.addEventListener('input', () => {
            const length = commentInput.value.length;
            charCount.textContent = length;
            charCountDiv.classList.toggle('warning', length > 450);
        });

        // Form submission
        ratingForm.addEventListener('submit', event => {
            event.preventDefault();

            const data = new FormData(ratingForm);

            // Clear old messages
            ratingError.style.display = 'none';
            commentError.style.display = 'none';
            successMessage.style.display = 'none';

            let isValid = true;

            // Validate rating
            if (!selectedRating) {
                ratingError.style.display = 'block';
                isValid = false;
            }

            // Validate comment
            const commentValue = commentInput.value.trim();
            if (commentValue.length < 10) {
                commentError.style.display = 'block';
                isValid = false;
            }

            if (!isValid) return;

            // Append rating
            data.append('rating', selectedRating);

            fetch('./src/submit-review.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.json())
                .then(json => {
                    console.log(json);
                    if (json.msg) {
                        successMessage.style.display = 'block';
                        successMessage.textContent = json.msg || '✓ Thank you for your review!';

                        setTimeout(() => {
                            ratingForm.reset();
                            selectedRating = 0;
                            updateStars(0);
                            ratingText.textContent = '';
                            charCount.textContent = '0';
                            successMessage.style.display = 'none';

                            const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
                            if (modal) modal.hide();
                        }, 2000);
                    } else {
                        alert('Failed to submit review: ' + (json.msg || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error submitting review:', error);
                    alert('An error occurred while submitting your review.');
                });
        });
    });
    // Reset form when modal is closed
    document.getElementById('bookingModal').addEventListener('hidden.bs.modal', () => {
        form.reset();
        selectedRating = 0;
        updateStars(0);
        ratingText.textContent = '';
        charCount.textContent = '0';
        ratingError.style.display = 'none';
        commentError.style.display = 'none';
        successMessage.style.display = 'none';
    });
</script>

<?php include './footer.php' ?>