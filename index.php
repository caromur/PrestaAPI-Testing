<!DOCTYPE html>
<?php 

session_start();

if(!isset($_SESSION['admin_login']))
{
    echo '<script>window.open("adminLogin.php", "_self");</script>';
}
else
{
 require 'api/index.php'; 
    require 'functions/functions.php';   
}
?>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Adam Carolan's Online Store</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Orbitron&display=swap" rel="stylesheet">
        <link href="css/shop-homepage.css" rel="stylesheet">
        
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="js/testXML.js"></script>

    </head>

    <body>

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg  fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php"><i class="fas fa-store"></i> Adam Carolan's Online Store</a>
                <i class="fas fa-bars menu-toggler navbar-toggler"  data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"></i>
                <span class="navbar-toggler-icon"></span>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="index.php">Home
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="functions/adminLogout.php">Log Out</a>
                        </li>
                        <li>
                            <a href="myCart.php" class="cart-link"><i class="fas fa-shopping-cart myCart nav-link"></i>
                                <span class="badge badge-danger my-badge"></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="cartArea">
            <form method="get" action="results.php" enctype="multipart/form-data">
                <div class="p-1 bg-light rounded rounded-pill shadow-sm mb-4 w-75 mt-5 search-field">
                    <div class="input-group">
                        <input id="searchbox" type="text" name="user_query" placeholder="Search a product" aria-describedby="button-addon1" class="form-control border-0 bg-light">
                        <div class="input-group-append">
                            <button id="button-addon1" type="submit" name="search" class="btn btn-link text-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <?php readResource() ?>
        <?php generateXML()?>
        <!-- Page Content -->

        <!-- /.container -->

        <!-- Footer -->
        <footer class="py-5 bg-dark">
            <div class="container">
                <p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
            </div>
            <!-- /.container -->
        </footer>

        <!-- Bootstrap core JavaScript -->
        
    </body>

</html>
