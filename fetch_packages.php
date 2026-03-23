<?php

require_once './src/Database.php';


$destination_id = isset($_GET['destination_id']) ? $_GET['destination_id'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// Base query
$sql = "SELECT * FROM tour_packages WHERE 1";

// Filter by destination
if (!empty($destination_id) && $destination_id !== 'all') {
    $sql .= " AND destination_id = '" . $db->real_escape_string($destination_id) . "'";
}

// Sorting logic
switch ($sort) {
    case 'lowToHigh':
        $sql .= " ORDER BY price ASC";
        break;
    case 'highToLow':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
        break;
}



$result = $db->query($sql);

if ($result && $result->num_rows > 0) {
    while ($package = $result->fetch_assoc()) {
        $imagePath = $package['image1'] ?? '';
        $fileName1 = basename($imagePath);
        $imageURL = "./admin/uploaded-files/packages/" . $fileName1;
        ?>

        <div class="col-lg-4 mb-2">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="min-height: 320px;">
                <div class="position-relative">
                    <img src="<?php echo $imageURL; ?>" class="card-img-top"
                        alt="<?php echo htmlspecialchars($package['package_name']); ?>"
                        style="height: 110px; object-fit: cover;">
                    <div class="card-img-overlay d-flex flex-column justify-content-end p-2"
                        style="background: rgba(83, 82, 82, 0.3); border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                        <h6 class="text-white mb-0 medium">
                            <?php echo htmlspecialchars($package['package_name']); ?>
                        </h6>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="mb-1">
                        <span class="badge bg-info text-white small">
                            <?php echo htmlspecialchars($package['duration']); ?>
                        </span>
                    </div>
                    <p class="text-secondary small mb-2">
                        <?php echo htmlspecialchars($package['description']); ?>
                    </p>
                    <div class="d-flex align-items-end justify-content-between">
                        <span class="h6 mb-0 text-primary">₹<?php echo number_format($package['price']); ?></span>
                        <a href="package-details.php?id=<?php echo $package['id']; ?>"
                            class="btn btn-primary btn-sm rounded-pill px-3 py-0">View Package</a>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
} else {
    echo '<div class="col-12 text-center"><p class="text-muted">No packages found for this destination.</p></div>';
}
?>