<?php include './header.php';


require_once './src/Database.php';

//$db = Database::getInstance();

//fetch destinations
$sql = "SELECT * FROM destinations ORDER BY id DESC";

$res = $db->query($sql);
$destinations = [];
while ($row = $res->fetch_object()) {
    $destinations[] = $row;
}


$sql = "SELECT * FROM tour_packages ORDER BY id DESC";
$result = $db->query($sql);

$packages = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
} else {
    echo "0 results";
}



foreach ($packages as $package) {

    $fileName1 = explode('/', $package['image1'])[4];
    //print_r($fileName1);die;
}


?>

<main id="main">
    <section class="section-bg">
        <div class="container-fluid" style="padding-top: 80px;">
            <header class="section-header" style="height:210px;background-image: url('./img/banner2.png');">
                <!-- Optional header content -->
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
                <!-- Sidebar Filter -->
                <aside class="col-md-3">
                    <div class="card">
                        <article class="filter-group">
                            <header class="card-header">
                                <a href="#" data-toggle="collapse" data-target="#collapse_2" aria-expanded="true">
                                    <i class="icon-control fa fa-chevron-down"></i>
                                    <h6 class="title">Filter By Destinations</h6>
                                </a>
                            </header>
                            <div class="filter-content collapse show" id="collapse_2">
                                <div class="card-body">
                                    <select id="destinationDropdown" class="form-control">
                                        <option value="all">All Destinations</option>
                                        <?php foreach ($destinations as $destination): ?>
                                            <option value="<?php echo $destination->id; ?>">
                                                <?php echo htmlspecialchars($destination->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </article>
                    </div>
                </aside>

               <!-- Package Display Section -->
        <section class="col-md-9">
            <div class="row" id="packageContainer">
                <!-- Packages will load here -->
            </div>
        </section>  
            </div>
        </div>
    </section>
</main>

<?php include './footer.php'; ?>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // ✅ Load all packages by default when page loads
    loadPackages();

    // Trigger when filter changes
    $('#destinationDropdown, #sortDropdown').on('change', function() {
        loadPackages();
    });

    function loadPackages() {
        var destination_id = $('#destinationDropdown').val();
        var sort = $('#sortDropdown').val();

        $.ajax({
            url: 'fetch_packages.php',
            type: 'GET',
            data: {
                destination_id: destination_id,
                sort: sort
            },
            beforeSend: function() {
                $('#packageContainer').html('<p class="text-center text-muted">Loading packages...</p>');
            },
            success: function(response) {
                $('#packageContainer').html(response);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
});
</script>