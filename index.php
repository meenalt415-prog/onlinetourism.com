<?php include './header.php';

require_once './src/Session.php';
require_once './src/Database.php';
session_start();

$user = Session::get('user');
//unset($_SESSION['redirect_url']);
echo 'sessionvar_name' . $_SESSION['redirect_url'];


$sql = "SELECT * FROM tour_packages ORDER BY tour_packages.created_at DESC LIMIT 3";
$res = $db->query($sql);
$packages = [];
while ($row = $res->fetch_object()) {
    $packages[] = $row;
}



/*$packages = [
    (object)[
        'id' => 1,
        'title' => 'Romantic Paris Getaway',
        'destination' => 'Paris, France',
        'duration' => 5,
        'price' => 49999
    ],
    (object)[
        'id' => 2,
        'title' => 'Adventure in the Himalayas',
        'destination' => 'Manali, India',
        'duration' => 7,
        'price' => 39999
    ],
    // Add more...
];*/


?>

<style>
    .card:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease-in-out;
    }

    .section-bg {
        background-color: #f8f9fa;
    }

    /* Destinations section styles */
    #destinations .card {
        border: 0;
        border-radius: 12px;
        overflow: hidden;
        transition: transform .28s cubic-bezier(.2, .9, .3, 1), box-shadow .28s;
        box-shadow: 0 6px 18px rgba(20, 30, 50, 0.06);
        background: #fff;
        min-height: 320px;
        display: flex;
        flex-direction: column;
    }

    #destinations .card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 18px 40px rgba(20, 30, 50, 0.12);
    }

    #destinations .img-wrap {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #f2f4f8;
    }

    #destinations .img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .6s ease;
    }

    #destinations .card:hover .img-wrap img {
        transform: scale(1.06);
    }

    #destinations .overlay {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 12px;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.35) 50%, rgba(0, 0, 0, 0.6) 100%);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 8px;
    }

    #destinations .dest-title {
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.1;
        margin: 0;
    }

    #destinations .dest-badge {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        padding: 6px 8px;
        border-radius: 999px;
        font-size: 0.78rem;
        backdrop-filter: blur(4px);
    }

    #destinations .card-body {
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    #destinations .desc {
        margin: 0;
        color: #6b7280;
        font-size: 0.92rem;
        line-height: 1.3;
        /* clamp to 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #destinations .btn-view {
        align-self: flex-end;
        padding: .5rem .85rem;
        border-radius: 8px;
        font-size: .9rem;
    }

    .about-section {
        --accent: #2563eb;
        --muted: #6b7280;
    }

    .about-card {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(18, 38, 63, 0.08);
        background: linear-gradient(180deg, #fff 0%, #fbfdff 100%);
    }

    .about-figure {
        position: relative;
        min-height: 320px;
    }

    .about-figure img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .about-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 18px;
        color: #fff;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.25) 40%, rgba(0, 0, 0, 0.45) 100%);
        font-size: .95rem;
    }

    .feature {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .feature .icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .feature strong {
        display: block;
        font-size: .98rem;
    }

    .feature .muted {
        color: var(--muted);
        font-size: .88rem;
    }

    .about-cta .btn {
        min-width: 140px;
    }

    @media (max-width: 767.98px) {
        .about-figure {
            min-height: 220px;
        }
    }

    @media (max-width: 576px) {
        #destinations .img-wrap {
            height: 170px;
        }

        #destinations .card {
            min-height: auto;
        }
    }
</style>
<!--==========================
    Intro Section
  ============================-->
<section id="intro" class="clearfix">
    <div class="container d-flex align-items-center justify-content-between flex-wrap">

        <div class="intro-info text-center">
            <h2>Explore the World<br><span>with Our Tour Packages</span></h2>
            <div class="mt-4">
                <a href="#packages" class="btn btn-primary btn-lg scrollto mx-2">View Packages</a>
                <a href="#about" class="btn btn-outline-light btn-lg scrollto mx-2">Learn More</a>
            </div>
        </div>



    </div>
</section>

<main id="main">

    <!--==========================
      About Us Section
    ============================-->
    <section id="about" class="about-section py-5">


        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <figure class="about-card about-figure">
                        <img src="./img/about.jpg" alt="About our travel services" loading="lazy">

                        <figcaption class="about-caption">
                            Personalized itineraries • Local experts • 24/7 support
                        </figcaption>
                    </figure>
                </div>

                <div class="col-lg-6">
                    <div class="mb-3">
                        <h3 class="mb-2">About Our Travel Services</h3>
                        <p class="lead text-muted mb-0">
                            We design unique and customizable tour experiences — from adventurous expeditions to
                            relaxing luxury escapes. Travel with confidence: expert guides, flexible dates, and curated
                            itineraries.
                        </p>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-2 g-3 mb-3">
                        <div class="col">
                            <div class="feature">
                                <div class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                <div>
                                    <strong>Curated Destinations</strong>
                                    <div class="muted">Handpicked places you'll love</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="feature">
                                <div class="icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                                <div>
                                    <strong>Flexible Dates</strong>
                                    <div class="muted">Book when it suits you</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="feature">
                                <div class="icon"><i class="fa fa-users" aria-hidden="true"></i></div>
                                <div>
                                    <strong>Group & Solo Trips</strong>
                                    <div class="muted">Options for every traveler</div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="feature">
                                <div class="icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                                <div>
                                    <strong>Top-rated Packages</strong>
                                    <div class="muted">Highly reviewed itineraries</div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>

    <?php
    // Fetch latest destinations
    $sql = "SELECT * FROM destinations ORDER BY created_at DESC LIMIT 6";
    $res = $db->query($sql);
    $destinations = [];
    if ($res) {
        while ($row = $res->fetch_object()) {
            $destinations[] = $row;
        }
    }
    ?>

    <section id="destinations" class="py-5">

        <div class="container">
            <div class="section-header text-center mb-4">
                <h2 class="mb-1">Explore Destinations</h2>
                <p class="text-muted mb-0">Popular places to visit — handpicked for you.</p>
            </div>

            <?php if (empty($destinations)): ?>
                <div class="text-center text-muted">No destinations found.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($destinations as $d):
                        $displayName = !empty($d->name) ? $d->name : (!empty($d->destination_name) ? $d->destination_name : 'Destination');
                        $imgField = !empty($d->image) ? $d->image : (!empty($d->image1) ? $d->image1 : '');
                        if (!empty($imgField) && filter_var($imgField, FILTER_VALIDATE_URL)) {
                            $imgUrl = $imgField;
                        } elseif (!empty($imgField)) {
                            $imgUrl = './admin/uploaded-files/destinations/' . basename($imgField);
                        } else {
                            $imgUrl = 'https://via.placeholder.com/800x500?text=No+Image';
                        }
                        $subText = !empty($d->country) ? $d->country : (!empty($d->short_desc) ? $d->short_desc : '');
                        $destId = urlencode($d->id);
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="card h-100" aria-labelledby="dest-<?php echo $destId; ?>">
                                <div class="img-wrap">
                                    <img src="<?php echo htmlspecialchars($imgUrl, ENT_QUOTES); ?>"
                                        alt="<?php echo htmlspecialchars($displayName, ENT_QUOTES); ?>" loading="lazy"
                                        width="800" height="500">
                                    <div class="overlay">
                                        <h3 id="dest-<?php echo $destId; ?>" class="dest-title mb-0">
                                            <?php echo htmlspecialchars($displayName, ENT_QUOTES); ?></h3>
                                        <?php if (!empty($d->country)): ?>
                                            <span class="dest-badge"><?php echo htmlspecialchars($d->country, ENT_QUOTES); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php if (!empty($subText)): ?>
                                        <p class="desc"><?php echo htmlspecialchars($subText, ENT_QUOTES); ?></p>
                                    <?php endif; ?>

                                    <div class="mt-auto">
                                        <a href="destinationwise-packages.php?id=<?php echo $destId; ?>"
                                            class="btn btn-outline-primary btn-sm btn-view"
                                            aria-label="View <?php echo htmlspecialchars($displayName, ENT_QUOTES); ?>">View</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!--==========================
      Tour Packages Section
    ============================-->
    <section id="packages" class="section-bg py-5">
        <div class="container">

            <div class="section-header text-center mb-5">
                <h2>Popular Tour Packages</h2>
                <p>Discover the best destinations with our handpicked travel packages.</p>
            </div>

            <div class="row">
                <?php foreach ($packages as $p): ?>
                    <?php
                    // Extract image file name safely
                    $imagePath = $p->image1;
                    $imageURL = !empty($imagePath) ? $imagePath : 'https://via.placeholder.com/400x200?text=No+Image';
                    $fileName1 = basename($imageURL);

                    ?>
                    <div class="col-md-12 mb-4">
                        <div class="card flex-row shadow-sm border-0">
                            <img src="./admin/uploaded-files/packages/<?php echo $fileName1 ?>"
                                class="card-img-left rounded-start" alt="Tour Image"
                                style="width: 250px; object-fit: cover;">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title text-primary"><?php echo $p->package_name ?></h5>
                                    <p class="card-text text-muted">
                                        <strong>Destination:</strong> <?php echo $p->destination ?><br>
                                        <strong>Duration:</strong> <?php echo $p->duration ?> Days<br>
                                        <strong>Price:</strong> ₹<?php echo number_format($p->price) ?>
                                    </p>
                                </div>
                                <div class="d-flex justify-content-end align-items-center mt-3 mt-md-0"
                                    style="height: 100%; flex-wrap: wrap;">
                                    <a href="package-details.php?id=<?php echo $p->id ?>"
                                        class="btn btn-outline-primary btn-sm px-4">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <a href="packages.php" class="btn btn-primary btn-lg px-5 py-2">Explore More</a>
            </div>

        </div>
    </section>
</main>

<?php include './footer.php' ?>