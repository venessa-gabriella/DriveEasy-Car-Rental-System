<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>DriveEasy Car Rental</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="topbar">

    <div class="brand">
        🚗 DriveEasy
        <span>Car Rental System</span>
    </div>

    <nav>
        <a href="index.php">Dashboard</a>
        <a href="vehicles.php">Vehicles</a>
        <a href="customers.php">Customers</a>
        <a href="bookings.php">Bookings</a>
        <a href="payments.php">Payments</a>
    </nav>

</header>

<main class="container">