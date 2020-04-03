<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// Getting the Categories
require 'connection.php';
require 'connectToWebService.php';    

function readResource()
{
    global $webService;
    $xmlResponse = $webService->get(['resource' => 'addresses']);
    foreach ($xmlResponse->addresses->address as $address) {
        $addressId = (int) $address['id'];//$address['5'];
        $addressXmlResponse = $webService->get(['resource' => 'addresses', 'id' => $addressId]);
        $address = $addressXmlResponse->address[0];
        echo ' <div class="login-page">
            <h4 class="login-header mb-4 mt-4">You must login or sign up to make a purchase</h4>
            <div class="the-login-form">
                <form class="login-form" method="post" action="">
                    <table class="register-table">
                        <tr>
                            <td class="cus-reg-field">First Name: </td>
                            <td><input type="text" placeholder="Name" name="cus_name" value='.$address->firstname.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Second Name: </td>
                            <td><input type="text" placeholder="Email" name="cus_email" value='.$address->lastname.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Address Line 1: </td>
                            <td><input type="text" placeholder="Password" name="cus_pass" value='.$address->address1.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Address Line 2: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" value='.$address->address2.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">City: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" value='.$address->city.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Post Code: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" value='.$address->postcode.' required/></td>
                        </tr>
                        <tr>
                            <td class="cus-reg-field">Phone Number: </td>
                            <td><input type="text" placeholder="Number" name="cus_contact" value='.$address->phone.' required/></td>
                        </tr>
                    </table>
                    <button type="submit" name="register">Print Label</button>
                    <p class="message">Already have an account? <a href="checkout.php">Log in</a></p>
                </form>
            </div>
        </div>';
        //echo sprintf('ID: %s, alias: %s' . PHP_EOL, $address->lastname, $address->lastname);
    }
}

function getCats() {
    global $conn;
    $getCats = "Select * from category";
    $runCats = mysqli_query($conn, $getCats);

    while ($rowCats = mysqli_fetch_array($runCats)) {
        $cat_id = $rowCats['cat_id'];
        $cat_name = $rowCats['cat_name'];


        echo '<a href="index.php?cat_id=' . $cat_id . '" class="list-group-item">' . $cat_name . '</a>';
    }
}

function getIpAddr() {
    $ipAddr = $_SERVER['REMOTE_ADDR'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddr = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $ipAddr;
}

function cart() {

    if (isset($_GET['add_to_cart'])) {
        global $conn;
        if (loggedInOrOut()) {
            $ipAddr = getIpAddr();
            $product_id = $_GET['add_to_cart'];
            $checkProductAdded = "Select * from cart where ip_address = '$ipAddr' AND product_id = '$product_id'";
            $runCheck = mysqli_query($conn, $checkProductAdded);
            $email = $_SESSION['customer_email'];

            if (mysqli_num_rows($runCheck) > 0) {
                echo "";
            } else {
                $addToCart = "Insert into cart(product_id, ip_address, quantity, customer_email) values('$product_id', '$ipAddr', 1, '$email')";
                $runQuery = mysqli_query($conn, $addToCart);
                echo "<script>window.open('index.php', '_self');</script>";
            }
        } else {
            echo '<script>window.open("checkout.php", "_self");</script>';
        }
    }
}

function getProducts() {
    global $conn;

    if (!isset($_GET['cat_id'])) {
        $query = "Select * from products order by RAND() limit 0,6";
        $runQuery = mysqli_query($conn, $query);
        while ($rowProducts = mysqli_fetch_array($runQuery)) {

            $product_id = $rowProducts['product_id'];
            $product_name = $rowProducts['product_name'];
            //$product_cat = $rowProducts['product_cat'];
            $product_price = $rowProducts['product_price'];
            $product_desc = $rowProducts['product_desc'];
            $product_image = $rowProducts['product_img'];
            $product_quantity = $rowProducts['product_quantity'];

            echo '<div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                                <img class="card-img-top" src="adminPanel/productImages/' . $product_image . '" width="180" height="180" alt="">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="singleItem.php?product_id=' . $product_id . '">' . $product_name . '</a>
                                    </h4>
                                    <h5>€' . $product_price . '</h5>
                                    <p class="card-text">' . $product_desc . '</p>
                                    <h6>Available '.$product_quantity.'</h6>
                                </div>
                                <div class="card-footer add-cart-footer">
                                    <a href="index.php?add_to_cart=' . $product_id . '"><button class="btn btn-primary cart-btn">Add to Cart</button></a>
                                </div>
                            </div>
                            </div>';
        }
    } else {
        getCategoryProducts();
    }
}

function countCart() {
    global $conn;

    if (loggedInOrOut()) {
        $ipAddr = getIpAddr();
        $email = $_SESSION['customer_email'];
        $getItems = "Select * from cart where ip_address = '$ipAddr' AND customer_email = '$email'";
        $runQuery = mysqli_query($conn, $getItems);
        $countItems = mysqli_num_rows($runQuery);
        echo $countItems;
    }
}

function readCart() {
    if (loggedInOrOut()) {
        global $conn;
        $total = 0;
        $shipping = 0;
        $actual_total = 0;
        $noOfItems = 0;
        $ipAddr = getIpAddr();
        $email = $_SESSION['customer_email'];
        $getItems = "Select * from cart where ip_address = '$ipAddr' AND customer_email = '$email'";
        $runQuery = mysqli_query($conn, $getItems);
        while ($records = mysqli_fetch_array($runQuery)) {
            $product_id = $records['product_id'];
            $products = "Select * from products where product_id ='$product_id'";
            $run_product_price = mysqli_query($conn, $products);
            while ($product_records = mysqli_fetch_array($run_product_price)) {
                $product_price = array($product_records['product_price']);
                $new_product_id = $product_records['product_id'];
                $single_price = $product_records['product_price'];
                $product_image = $product_records['product_img'];
                $product_name = $product_records['product_name'];
                $product_quantity = $product_records['product_quantity'];
                $values = array_sum($product_price);
                $total += $values;
                $shipping = 5 * mysqli_num_rows($runQuery);
                $actual_total = $total + $shipping;
            }

            echo '<tr>
            <td><img src="adminPanel/productImages/' . $product_image . '" width="80" height="80" /> </td>
            <td>' . $product_name . '</td>
            <td class="text-right">€ ' . $single_price . '</td>
            <td class="text-right"><input type="number" name="quantity" min="1" max='.$product_quantity.' value="1"></td>
            <td class="text-right"><a href="myCart.php?remove_product=' . $product_id . '"><button class="btn btn-sm btn-danger" name="removeProduct" value="' . $product_id . '"><i class="fa fa-trash"></i></button></a>  </td>
          </tr>';
        }
        echo '<tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Sub-Total</td>
            <td class="text-right">€ ' . $total . '</td>
        </tr>';

        echo '<tr>
        <td></td>
        <td></td>
        <td></td>
        <td>Shipping</td>
        <td class="text-right">€ ' . $shipping . '</td>
        </tr>';

        echo '<tr>
        <td></td>
        <td></td>
        <td></td>
        <td><strong>Total</strong></td>
        <td class="text-right"><strong>€ ' . $actual_total . '</strong></td>
        </tr>';
    }
}

function deleteFromCart() {

    if (isset($_GET['remove_product'])) {
        global $conn;
        $product_id = $_GET['remove_product'];
        $ip_address = getIpAddr();
        $delete_product = "Delete from cart where product_id = '$product_id' AND ip_address='$ip_address'";
        $run_query = mysqli_query($conn, $delete_product);
        if ($run_query) {
            echo '<script>alert("Product Removed");window.open("myCart.php", "_self");</script>';
        }
    }
}

function getCategoryProducts() {
    global $conn;
    if (isset($_GET['cat_id'])) {
        $cat_id = $_GET['cat_id'];
        $query = "Select * from products where product_cat = '$cat_id'";
        $runQuery = mysqli_query($conn, $query);
        while ($rowProducts = mysqli_fetch_array($runQuery)) {

            $product_id = $rowProducts['product_id'];
            $product_name = $rowProducts['product_name'];
            //$product_cat = $rowProducts['product_cat'];
            $product_price = $rowProducts['product_price'];
            $product_desc = $rowProducts['product_desc'];
            $product_image = $rowProducts['product_img'];
            $product_quantity = $rowProducts['product_quantity'];
            echo '<div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                                <a href="singleItem.php"><img class="card-img-top" src="adminPanel/productImages/' . $product_image . '" width="180" height="180" alt=""></a>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="singleItem.php?product_id=' . $product_id . '">' . $product_name . '</a>
                                    </h4>
                                    <h5>€' . $product_price . '</h5>
                                    <a href="singleItem.php?add_to_cart=' . $product_id . '"><button class="btn btn-primary">Add to Cart</button></a>
                                    <p class="card-text">' . $product_desc . '</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                                </div>
                            </div>
                            </div>';
        }
    }
}

function getSingleProduct() {
    global $conn;
    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        $query = "Select * from products where product_id = $product_id";
        $runQuery = mysqli_query($conn, $query);
        while ($rowProducts = mysqli_fetch_array($runQuery)) {

            $product_id = $rowProducts['product_id'];
            $product_name = $rowProducts['product_name'];
            //$product_cat = $rowProducts['product_cat'];
            $product_price = $rowProducts['product_price'];
            $product_desc = $rowProducts['product_desc'];
            $product_image = $rowProducts['product_img'];
            $product_quantity = $rowProducts['product_quantity'];

            echo '<div class="col-lg-6">
            <div class="card mt-4 myCard">
          <img class="card-img-top single-item-img" src="adminPanel/productImages/' . $product_image . '"  alt="">
          </div></div>
          
<div class="col-lg-6">
        <div class="card card-outline-secondary my-4 myCard">
          <div class="card-header">
            Product Details
          </div>
          <div class="card-body">
            <p>' . $product_name . '</p>
            <hr>
            <p>€' . $product_price . '</p>
            <hr>
            <p>' . $product_desc . '</p>
            <hr>
            <p>Available: ' . $product_quantity . '</p>
            <hr>
            <a href="#"><button class="btn btn-primary">Add to Cart</button></a>
        </div></div></div>';
        }
    }
}

function searchResults() {
    global $conn;
    if (isset($_GET['search'])) {
        $userQuery = $_GET['user_query'];

        $getProducts = "Select * from products where product_key_words like '%$userQuery%' OR product_name like '%$userQuery%'";

        $runQuery = mysqli_query($conn, $getProducts);
        while ($rowProducts = mysqli_fetch_array($runQuery)) {

            $product_id = $rowProducts['product_id'];
            $product_name = $rowProducts['product_name'];
            //$product_cat = $rowProducts['product_cat'];
            $product_price = $rowProducts['product_price'];
            $product_desc = $rowProducts['product_desc'];
            $product_image = $rowProducts['product_img'];
            $product_quantity = $rowProducts['product_quantity'];

            echo '<div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                                <img class="card-img-top" src="adminPanel/productImages/' . $product_image . '" width="180" height="180" alt="">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="singleItem.php?product_id=' . $product_id . '">' . $product_name . '</a>
                                    </h4>
                                    <h5>€' . $product_price . '</h5>
                                    <p class="card-text">' . $product_desc . '</p>
                                    <h6>Available '.$product_quantity.'</h6>
                                </div>
                                <div class="card-footer add-cart-footer">
                                    <a href="index.php?add_to_cart=' . $product_id . '"><button class="btn btn-primary cart-btn">Add to Cart</button></a>
                                </div>
                            </div>
                            </div>';
        }
    } else {
        header("Location: index.php");
    }
}

function checkSession() {
    if (isset($_SESSION['customer_email'])) {
        echo '<script>window.open("index.php", "_self");</script>';
    } else {
        include 'checkout.php';
        //echo '<script>window.open("checkout.php", "_self");</script>';
    }
}

function loggedInOrOut() {
    if (isset($_SESSION['customer_email'])) {
        return true;
    } else {
        return false;
    }
}

function displayUser() {
    if (loggedInOrOut()) {
        echo '<li class="nav-item"><a class="nav-link ml-2" href="functions/logout.php">Log Out: </a></li>'
        . '  <li class="nav-item"><a class="nav-link" href="myAccount.php">' . $_SESSION['customer_email'] . '</a></li>';
    } else {
        echo '<a class="nav-link ml-2" href="checkout.php">Log In</a>';
    }
}

function logIn() {
    if (isset($_POST['login'])) {
        global $conn;
        $email = mysqli_real_escape_string($conn, $_POST['cus_email']);
        $password = mysqli_real_escape_string($conn, $_POST['cus_pass']);

        $check_cus = "Select * from customer where customer_email = '$email'";
        $run_check_cus = mysqli_query($conn, $check_cus);

        if (mysqli_num_rows($run_check_cus) == 1) {
            $results = mysqli_fetch_array($run_check_cus);
            $storedPass = $results['customer_password'];
            $name = $results['customer_name'];
            //echo $storedPass;

            if (password_verify($password, $storedPass)) {
                $_SESSION['customer_email'] = $email;
                echo '<script>window.open("index.php", "_self");</script>';
            } else {
                echo 'Password incorrect';
            }
        } else {
            echo 'Incorrect username or password';
        }
    }
    loggedInOrOut();
}

?>