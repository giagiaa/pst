<?php
    $conn = new mysqli("localhost", "root", "root", "search_pruduct_app");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
?>
