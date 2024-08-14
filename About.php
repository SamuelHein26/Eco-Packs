<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>About US</title>
</head>
<body>
    <?php include './components/Navbar.php'; ?>
    <div class="about-us pt-5 pb-5">
        <div class="container">
            <div class="row align-items-center">
            <div class="col-md-6 order-md-2 mb-md-0 mb-4 about-image text-center">
                <img src="images/picking_up_trash.jpg" alt="Eco-Packs Team" class="img-fluid rounded shadow-sm ">  </div>
            <div class="col-md-6 order-md-1 about-text">
                <h1 style="text-align: center; padding-bottom: 20px;">About Eco-Packs</h1>
                <h5>Founded in 2021 by Ms. Jane, Eco-Packs is a company driven by a passion for sustainability and a commitment to creating a positive environmental impact. We believe that eco-friendly packaging shouldn't come at the expense of style or functionality. That's why we offer a wide range of innovative and attractive packaging solutions made from recycled materials and designed to be completely compostable.</h5>
            </div>
            </div>
        </div>
    </div>

    <div class="why-eco-packs pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h3 class="text-center pb-2">Our Goals</h3>
                    <div class="img-wrapper">
                    <img src="images/Goals.jpg" alt="Goals graphic" class="blur h-75">
                    <b class="content slide-left text-center">We strive to create a sustainable future by offering eco-friendly packaging solutions and minimizing environmental impact. Our goal is to make compostable packaging the norm, not the exception.</b>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 class="text-center pb-2">Our Motivation</h3>
                    <div class="img-wrapper">
                    <img src="images/Motivation.jpeg" alt="motivation" class="blur h-75">
                    <b class="content slide-up text-center">We are driven by a passion for a cleaner planet. We believe that every business and individual can make a positive difference through eco-conscious choices. Eco-Packs is here to empower those choices.</b>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h3 class="text-center pb-2">Why Choose Us?</h3>
                    <div class="img-wrapper">
                    <img src="images/why_choose.jpg" alt="women thinking" class="blur h-75">
                    <b class="content slide-right text-center">We offer a wide range of stylish and sustainable packaging options. Our products are made from recycled materials, are completely compostable, and don't compromise on quality or functionality. Choose Eco-Packs and make a statement for the planet.</b>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-us pt-5 pb-5 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="images/joinus.jpg" alt="Join us on our adventure" class="img-fluid rounded">
                </div>
                <div class="col-md-6 about-text">
                    <h2 class="pb-2">Eco-Packs: Packaging with a Purpose</h2>
                    <h5>Together, we can make a difference! Become a part of the Eco-Packs community and help us promote sustainable packaging solutions. Whether you're a business owner, individual consumer, or simply passionate about the environment, there are ways you can join us. Shop our eco-friendly products, spread the word about our mission, or partner with us for your packaging needs.</h5>              
                </div>
            </div>
        </div>
    </div>

    <?php include './components/Footer.php'; ?>
</body>
</html>