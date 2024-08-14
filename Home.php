<?php
session_start();
require_once('db_connection.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Home</title>
</head>
<body>
    <?php include './components/Navbar.php'; ?>
    <?php
    // Fetch data for 4 featured products from the product database
    $sql = "SELECT name, description, image FROM product LIMIT 4";
    $result = mysqli_query($con, $sql);
    ?>
    <section>
        <div class="container hero mt-4 mb-4">
            <div class="text-light p-3">
                <h1>Sustainable Packaging Made Simple</h1>
                <div style="width: 600px; padding: 20px;">
                    <h5> Eco-Packs offers a wide range of eco-friendly packaging solutions to businesses and individuals. We use recycled materials and ensure complete compostability, reducing your environmental footprint.</h5>
                </div>
                <a href="Shop.php" class="btn btn-primary rounded btn-lg text-uppercase">Shop Now</a>
            </div>
        </div>
    </section>

    <section class="icons-container pt-4 pb-4 ms-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 icon-item">
                    <i class="fas fa-shipping-fast"></i>
                    <div class="content">
                        <h3>Free Shipping</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 icon-item">
                    <i class="fas fa-lock"></i>
                    <div class="content">
                        <h3>Secure Payment</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 icon-item">
                    <i class="fas fa-redo-alt"></i>
                    <div class="content">
                        <h3>Easy Returns</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 icon-item">
                    <i class="fas fa-headset"></i>
                    <div class="content">
                        <h3>24/7 Support</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-products pt-4 pb-4">
        <div class="container">
            <h2 class="text-center">Featured Products</h2>
            <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php
                            $imageData = base64_encode($row['image']);
                            $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                            ?>
                            <div class="d-flex justify-content-center align-items-center pt-2">
                                <img src="<?php echo $imageSrc; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="width: 90px; height: auto;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text"><?php echo $row['description']; ?></p>
                            </div>
                            <a href="Shop.php" class="btn btn-primary">View Shop</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="about-us pt-5 pb-5 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="images/plastic_recycle.jpg" alt="Eco-Packs Sustainable Packaging" class="img-fluid rounded">
            </div>
            <div class="col-md-6 about-text">
                <h2 class="pb-2">Eco-Packs: Packaging with a Purpose</h2>
                <p>We're passionate about creating sustainable packaging solutions that minimize environmental impact. Our products are made from recycled materials and are completely compostable, allowing you to make eco-friendly choices without compromising on style or functionality.</p>
                <a href="about.php" class="btn btn-primary">Learn More About Us</a>
            </div>
        </div>
    </div>
    </section>



    <?php include './components/Footer.php'; ?>
</body>
</html>
