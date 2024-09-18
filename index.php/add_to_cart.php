<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_db";

$product_id = $_POST['id'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0;
}
$_SESSION['cart'][$product_id]++;

$conn->close();
?>
