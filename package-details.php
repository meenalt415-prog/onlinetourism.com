<?php
include './header.php';
require_once './src/Session.php';


session_start();

$user = Session::get('user');

// Get current URL
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//print_r($_SESSION['redirect_url']);die;

// Set redirect URL only if it's not already set or if it's the current URL
if (!isset($_SESSION['redirect_url']) || $_SESSION['redirect_url'] !== $current_url) {
  $_SESSION['redirect_url'] = $current_url;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
  http_response_code(400);
  echo 'Bad request';
  return;
}


require_once './src/Database.php';

$id = $_GET['id'];


$id = $db->real_escape_string($_GET['id']);

$sql = "SELECT * FROM tour_packages WHERE id = '$id'";
$res = $db->query($sql);
$package_details = $res->fetch_object();


$fileName1 = explode('/', $package_details->image1)[4];
//print_r($fileName1);die;
$fileName2 = explode('/', $package_details->image2)[4];
$fileName3 = explode('/', $package_details->image3)[4];



?>


<style>
  /* Simple timeline styling scoped to this page */
  .itinerary-timeline {
    position: relative;
    padding-left: 0;
  }

  .itinerary-day {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
  }

  .itinerary-day+.itinerary-day {
    margin-top: 1rem;
  }

  .itinerary-day .day-badge {
    min-width: 64px;
    min-height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }

  /* vertical connector line */
  .itinerary-timeline {
    position: relative;
  }

  .itinerary-timeline::before {
    content: "";
    position: absolute;
    left: 38px;
    /* center of badges */
    top: 34px;
    bottom: 0;
    width: 2px;
    background: rgba(0, 0, 0, 0.06);
  }

  .itinerary-day:last-child~.itinerary-day::after {
    display: none;
  }

  @media (max-width: 576px) {
    .itinerary-day {
      flex-direction: column;
    }

    .itinerary-timeline::before {
      left: 18px;
    }

    .itinerary-day .day-badge {
      min-width: 48px;
      min-height: 48px;
    }
  }
</style>

<div class="container-fluid" style="padding-top: 80px; ">

  <header class="section-header" style="height:210px;background-image: url('./img/banner2.png'); ">
    <!-- You can remove the h3 and p tags if you don't need them -->
  </header>
</div>

<div class="container mt-5">
  <!-- Hero Section -->
  <div class="row mb-4">
    <div class="col-md-7">
      <img src="./admin/uploaded-files/packages/<?php echo $fileName1 ?>" class="img-fluid rounded" alt="Tour Destination">
    </div>
    <div class="col-md-5">
      <h2 class="mb-3"><?php echo $package_details->package_name ?></h2>
      <div class="mb-2"><?php echo $package_details->duration ?> | Kochi, Munnar & Alleppey</div>
      <div class="mb-2">
        <span class="badge bg-success text-white">Rated 4.6/5</span> <span class="text-muted ms-2">(120 reviews)</span>
      </div>
      <p><?php echo $package_details->description ?></p>
      <?php
      $pkgId = (int)$id;
      if ($stmtInc = $db->prepare("SELECT description FROM inclusions WHERE package_id = ? ORDER BY id ASC")) {
        $stmtInc->bind_param('i', $pkgId);
        $stmtInc->execute();
        $resInc = $stmtInc->get_result();

        if ($resInc && $resInc->num_rows > 0) {
          echo '<ul>';
          while ($inc = $resInc->fetch_assoc()) {
            $desc = trim($inc['description']);
            $descEsc = $desc !== '' ? nl2br(htmlspecialchars($desc)) : '<em>No description provided.</em>';
            echo '<li>' . $descEsc . '</li>';
          }
          echo '</ul>';
        } else {
          echo '<p class="text-muted"><em>No inclusions listed for this package.</em></p>';
        }

        $stmtInc->close();
      } else {
        echo '<p class="text-muted"><em>Unable to load inclusions.</em></p>';
      }
      ?>
      <h3 class="text-primary mb-3">&#8377; <?php echo $package_details->price ?><small class="text-muted fs-6"> per person</small></h3>

      <form action="pay.php" method="get" class="d-flex align-items-center gap-2">
        <input type="hidden" name="package_id" value="<?php echo $id ?>">
        <input type="hidden" name="amount" value="<?php echo $package_details->price ?>">
        <input
          type="date"
          name="booking_date"
          class="form-control"
          required
          style="max-width: 180px;"
          min="<?php echo date('Y-m-d'); ?>">
        <button type="submit" class="btn btn-lg btn-warning ml-2">Book This Tour</button>
      </form>
    </div>
  </div>

  <!-- Tabbed Content Section -->
  <ul class="nav nav-tabs" id="tourTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button"
        role="tab">Itinerary</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="inclusions-tab" data-bs-toggle="tab" data-bs-target="#inclusions" type="button"
        role="tab">Inclusions</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button"
        role="tab">Gallery</button>
    </li>
  </ul>
  <div class="tab-content mb-5" id="tourTabContent">
    <?php
    // Fetch itineraries for this package and render a nicer day-wise design
    $pkgId = (int)$id;

    if ($stmt = $db->prepare("SELECT day_number, title, description FROM itineraries WHERE package_id = ? ORDER BY day_number ASC")) {
      $stmt->bind_param('i', $pkgId);
      $stmt->execute();
      $res = $stmt->get_result();
    ?>

      <div class="tab-pane fade show active" id="itinerary" role="tabpanel">


        <?php
        if ($res && $res->num_rows > 0) {
          echo '<div class="itinerary-timeline mt-3">';
          while ($row = $res->fetch_assoc()) {
            $day = htmlspecialchars($row['day_number']);
            $title = trim($row['title']);
            $titleEsc = $title !== '' ? htmlspecialchars($title) : '';
            $desc = trim($row['description']);
            $descEsc = $desc !== '' ? nl2br(htmlspecialchars($desc)) : '';
        ?>
            <div class="itinerary-day">
              <div class="day-badge bg-primary text-white">
                Day <?php echo $day; ?>
              </div>

              <div class="card flex-fill shadow-sm">
                <div class="card-body">
                  <?php if ($titleEsc !== ''): ?>
                    <h5 class="card-title mb-1"><?php echo $titleEsc; ?></h5>
                  <?php else: ?>
                    <h5 class="card-title mb-1 text-muted">Itinerary</h5>
                  <?php endif; ?>

                  <?php if ($descEsc !== ''): ?>
                    <p class="card-text text-muted mb-0" style="white-space:pre-wrap;"><?php echo $descEsc; ?></p>
                  <?php else: ?>
                    <p class="card-text text-muted mb-0"><em>No details provided for this day.</em></p>
                  <?php endif; ?>
                </div>
                <?php
                // Optionally show quick actions or highlights in footer (keep minimal)
                echo '<div class="card-footer bg-transparent d-flex justify-content-between align-items-center">';
                echo '<small class="text-muted">Day ' . $day . ' overview</small>';
                echo '</div>';
                ?>
              </div>
            </div>
        <?php
          } // end while
          echo '</div>'; // end timeline
        } else {
          echo '<p class="mt-3">Itinerary details are not available for this package.</p>';
        }
        ?>
      </div>

    <?php
      $stmt->close();
    } else {
      // Query failed
      echo '<div class="tab-pane fade show active" id="itinerary" role="tabpanel">';
      echo '<p class="mt-3">Unable to load itinerary.</p>';
      echo '</div>';
    }
    ?>
    <div class="tab-pane fade" id="inclusions" role="tabpanel">
      <?php
      if ($stmtInc = $db->prepare("SELECT description FROM inclusions WHERE package_id = ? ORDER BY id ASC")) {
        $stmtInc->bind_param('i', $pkgId);
        $stmtInc->execute();
        $resInc = $stmtInc->get_result();

        if ($resInc && $resInc->num_rows > 0) {
          echo '<ul class="mt-3">';
          while ($inc = $resInc->fetch_assoc()) {
            $desc = trim($inc['description']);
            $descEsc = $desc !== '' ? nl2br(htmlspecialchars($desc)) : '<em>No description provided.</em>';
            echo '<li>' . $descEsc . '</li>';
          }
          echo '</ul>';
        } else {
          echo '<p class="mt-3">Inclusions are not available for this package.</p>';
        }

        $stmtInc->close();
      } else {
        echo '<p class="mt-3">Unable to load inclusions.</p>';
      }
      ?>
    </div>
    <div class="tab-pane fade" id="gallery" role="tabpanel">
      <div class="row mt-3">
        <div class="col-4">
          <img src="./admin/uploaded-files/packages/<?php echo $fileName2 ?>" class="img-fluid rounded mb-2" alt="Gallery Image 1">
        </div>
        <div class="col-4">
          <img src="./admin/uploaded-files/packages/<?php echo $fileName3 ?>" class="img-fluid rounded mb-2" alt="Gallery Image 2">
        </div>
        <div class="col-4">
          <img src="./admin/uploaded-files/packages/<?php echo $fileName1 ?>" class="img-fluid rounded mb-2" alt="Gallery Image 3">
        </div>
      </div>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  var bookingModal = document.getElementById('bookingModal');
  bookingModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var packageName = button.getAttribute('data-package');
    var packageInput = bookingModal.querySelector('#packageName');
    packageInput.value = packageName;
  });
</script>


<?php include './footer.php'


/*

<div class="container mt-5">
  <!-- Hero Section -->
  <div class="row mb-4">
    <div class="col-md-7">
      <img src="https://picsum.photos/id/1002/700/400" class="img-fluid rounded" alt="Tour Destination">
    </div>
    <div class="col-md-5">
      <h2 class="mb-3"><?php echo $package_details->package_name ?></h2>
      <div class="mb-2">5 Days | Kochi, Munnar & Alleppey</div>
      <div class="mb-2">
        <span class="badge bg-success text-white">Rated 4.6/5</span> <span class="text-muted ms-2">(120 reviews)</span>
      </div>
      <p>Relax in lush green hills and tranquil backwaters. This Kerala tour package includes guided visits, boat rides,
        and authentic cuisine.</p>
      <ul>
        <li>Stay: 4-star hotels</li>
        <li>All meals included</li>
        <li>Daily sightseeing with expert guide</li>
      </ul>
      <h3 class="text-primary mb-3">&#8377; 18,750 <small class="text-muted fs-6">per person</small></h3>
      <button class="btn btn-lg btn-warning" data-bs-toggle="modal" data-bs-target="#bookingModal"
        data-package="Enchanting Kerala Escape">Book This Tour</button>
    </div>
  </div>

  <!-- Tabbed Content Section -->
  <ul class="nav nav-tabs" id="tourTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button"
        role="tab">Itinerary</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="inclusions-tab" data-bs-toggle="tab" data-bs-target="#inclusions" type="button"
        role="tab">Inclusions</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button"
        role="tab">Gallery</button>
    </li>
  </ul>
  <div class="tab-content mb-5" id="tourTabContent">
    <div class="tab-pane fade show active" id="itinerary" role="tabpanel">
      <ol class="mt-3">
        <li><strong>Day 1:</strong> Arrival at Kochi, sightseeing tour.</li>
        <li><strong>Day 2:</strong> Kochi to Munnar, tea estate visit.</li>
        <li><strong>Day 3:</strong> Exploring Munnar hills and Eravikulam Park.</li>
        <li><strong>Day 4:</strong> Transfer to Alleppey, houseboat cruise.</li>
        <li><strong>Day 5:</strong> Departure after breakfast.</li>
      </ol>
    </div>
    <div class="tab-pane fade" id="inclusions" role="tabpanel">
      <ul class="mt-3">
        <li>Daily breakfast, lunch, and dinner</li>
        <li>Airport transfers and internal travel</li>
        <li>Sightseeing as per itinerary</li>
        <li>Houseboat experience</li>
        <li>All applicable taxes</li>
      </ul>
    </div>
    <div class="tab-pane fade" id="gallery" role="tabpanel">
      <div class="row mt-3">
        <div class="col-4">
          <img src="https://picsum.photos/id/1003/300/200" class="img-fluid rounded mb-2" alt="Gallery Image 1">
        </div>
        <div class="col-4">
          <img src="https://picsum.photos/id/1018/300/200" class="img-fluid rounded mb-2" alt="Gallery Image 2">
        </div>
        <div class="col-4">
          <img src="https://picsum.photos/id/1024/300/200" class="img-fluid rounded mb-2" alt="Gallery Image 3">
        </div>
      </div>
    </div>
  </div>
</div>

*/

?>