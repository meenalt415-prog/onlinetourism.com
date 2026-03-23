<?php include './header.php';


require_once './src/Database.php';

$destination_id = $db->real_escape_string($_GET['id']);



$packages = [];

$dest_id = (int)$destination_id;

if ($stmt = $db->prepare("SELECT * FROM tour_packages WHERE destination_id = ? ORDER BY id DESC")) {
    $stmt->bind_param('i', $dest_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
    $stmt->close();
} else {
    // fallback to simple query if prepare fails
    $sql = "SELECT * FROM tour_packages WHERE destination_id = " . $db->real_escape_string($dest_id) . " ORDER BY id DESC";
    $result = $db->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $packages[] = $row;
        }
    }
}

?>



<main id="main">

    <section class="section-bg">
        <div class="container-fluid" style="padding-top: 80px; ">

            <header class="section-header" style="height:210px;background-image: url('./img/banner2.png'); ">
                <!-- You can remove the h3 and p tags if you don't need them -->
            </header>
        </div>
        <div class="container" style="padding-top: 50px; padding-bottom:80px">

            <div class="form-inline mb-3">
                <span class="mr-md-auto"> </span>
                <select class="mr-2 form-control" id="sortDropdown">
                    <option value="latest">Latest</option>
                    <option value="lowToHigh">Low to High</option>
                    <option value="highToLow">High to Low</option>
                </select>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <span id="durationMessage" class="text-danger"></span>
                </div>
            </div>

            <div class="row">
                <aside class="col-md-3">

                   

                </aside>
                <main class="col-md-9">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php if (!empty($packages)): ?>
                            <?php foreach ($packages as $package): ?>
                                <?php
                                // Extract image file name safely
                                $imagePath = $package['image1'];
                                $imageURL = !empty($imagePath) ? $imagePath : 'https://via.placeholder.com/400x200?text=No+Image';
                                $fileName1 = basename($imageURL);
                                ?>
                                <div class="col">
                                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="min-height: 320px;">
                                        <div class="position-relative">
                                            <img src="./admin/uploaded-files/packages/<?php echo $fileName1 ?>" class="card-img-top"
                                                alt="<?php echo htmlspecialchars($package['title']); ?>"
                                                style="height: 110px; object-fit: cover;">
                                            <div class="card-img-overlay d-flex flex-column justify-content-end p-2"
                                                style="background: rgba(83, 82, 82, 0.3); border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                                <h6 class="text-white mb-0 medium">
                                                    <?php echo htmlspecialchars($package['package_name']); ?>
                                                </h6>
                                                <div class="d-flex align-items-center mt-1">
                                                    <span class="badge bg-warning text-dark me-1 small"><i
                                                            class="bi bi-star-fill"></i>
                                                        <?php echo isset($package['rating']) ? $package['rating'] : '4.5'; ?>
                                                    </span>
                                                    <span class="text-white small">
                                                        (<?php echo isset($package['reviews']) ? $package['reviews'] : rand(50, 300); ?>)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="mb-1">
                                                <span class="badge bg-info text-white small">
                                                    <?php echo htmlspecialchars($package['duration']); ?>
                                                </span>
                                            </div>
                                            <ul class="list-unstyled mb-2 text-secondary small">
                                                <li class="mb-0"><i class="bi bi-check-circle text-success"></i>
                                                    <?php echo htmlspecialchars($package['inclusions']); ?>
                                                </li>
                                                <li class="mb-0"><i class="bi bi-airplane text-success"></i> Transfers</li>
                                            </ul>
                                            <div class="d-flex align-items-end justify-content-between">
                                                <span
                                                    class="h6 mb-0 text-primary">₹<?php echo number_format($package['price']); ?></span>
                                                <a href="package-details.php?id=<?php echo $package['id']; ?>"
                                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0">View Package</a>
                                            </div>
                                        </div>
                                        <div class="card-footer border-0 bg-light text-center text-secondary small py-1">
                                            <i class="bi bi-shield-check text-success"></i> Free Cancel | Limited
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <p class="text-muted">No packages available.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <nav class="mt-4" aria-label="Page navigation sample">
                        <ul class="pagination">
                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>

                </main>
            </div>
        </div>
    </section>
</main>



<?php include './footer.php'; ?>