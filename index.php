<?php

require 'db.php';

$vehicles = $conn->query(
    "SELECT COUNT(*) total FROM vehicles"
)->fetch_assoc()['total'];

$customers = $conn->query(
    "SELECT COUNT(*) total FROM customers"
)->fetch_assoc()['total'];

$bookings = $conn->query(
    "SELECT COUNT(*) total FROM bookings
     WHERE booking_status='Booked'"
)->fetch_assoc()['total'];

$payments = $conn->query(
    "SELECT COALESCE(SUM(amount),0) total
     FROM rental_payments"
)->fetch_assoc()['total'];

require 'header.php';

?>

<h1>Dashboard</h1>

<p>
Welcome to DriveEasy Car Rental Management System.
</p>

<div class="cards">

    <div class="card">
        Registered Vehicles
        <strong><?= $vehicles ?></strong>
    </div>

    <div class="card">
        Customers
        <strong><?= $customers ?></strong>
    </div>

    <div class="card">
        Active Bookings
        <strong><?= $bookings ?></strong>
    </div>

    <div class="card">
        Payments Received
        <strong>
            KSh <?= number_format((float)$payments, 2) ?>
        </strong>
    </div>

</div>

<div class="panel">

    <h2>Quick Actions</h2>

    <a class="btn" href="vehicles.php">
        Register Vehicle
    </a>

    <a class="btn" href="customers.php">
        Add Customer
    </a>

    <a class="btn" href="bookings.php">
        Make Booking
    </a>

    <a class="btn" href="payments.php">
        Record Payment
    </a>

</div>

<?php require 'footer.php'; ?>