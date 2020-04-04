
<?php

function getProducts() {
    $query = "select * FROM products ORDER BY product_id";
    try {
        global $db;
        $games = $db->query($query);
        $games = $games->fetchAll(PDO::FETCH_ASSOC);
        header("Content-Type: application/json", true);
        echo '{"game": ' . json_encode($games) . '}';
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getProduct($id) {
    $query = "SELECT * FROM products WHERE product_id = '$id'";
    try {
        global $db;
        $games = $db->query($query);
        $games = $games->fetch(PDO::FETCH_ASSOC);
        header("Content-Type: application/json", true);
        echo json_encode($games);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function addProduct() {
    global $app;
    $request = $app->request();
    $game = json_decode($request->getBody());
    $name = $game->product_name;
    $img = $game->product_img;  
    
    
   
    
    
    //$prodId = $game->product_id;
    $category = $game->product_cat;
    $description = $game->product_desc;
    $keyWords = $game->product_key_words;
    $price = $game->product_price;
    //$query= "UPDATE products SET product_id = '$prodId', product_cat = '$category', product_name = '$name', product_price = '$price', product_desc = '$description', product_key_words = '$keyWords' where product_id = '$id'";

    $query = "INSERT INTO products
                 ( product_cat, product_name, product_price, product_desc, product_key_words, product_img)
              VALUES
                 ( '$category', '$name', '$price', '$description', '$keyWords', '$img')";
    try {
        global $db;
        $db->exec($query);
        $game->id = $db->lastInsertId();
        echo json_encode($game);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function deleteProduct($id) {
    $sql = "DELETE FROM products WHERE product_id='$id'";
    try {
        global $db;
        $db->exec($sql);
        echo json_encode("Finished");
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function viewPs1Games($platform) {
    $query = "SELECT * FROM games WHERE platform = '$platform'";
    try {
        global $db;
        $games = $db->query($query);
        $games = $games->fetchAll(PDO::FETCH_ASSOC);
        header("Content-Type: application/json", true);
        echo $query;
        echo json_encode($games);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function updateDetails($id) {
    global $app;
    $request = $app->request();
    $game = json_decode($request->getBody());
    $name = $game->product_name;
    $img = $game->product_img;
    $prodId = $game->product_id;
    $category = $game->product_cat;
    $description = $game->product_desc;
    $keyWords = $game->product_key_words;
    $price = $game->product_price;
    $query = "UPDATE products SET product_id = '$prodId', product_cat = '$category', product_name = '$name', product_price = '$price', product_desc = '$description', product_key_words = '$keyWords' where product_id = '$id'";
    try {
        global $db;
        $db->exec($query);
        echo json_encode($game);
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
?>

