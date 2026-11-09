<?php

require 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $booking = (int)($_POST['booking_id'] ?? 0);
    $amount = (float)($_POST['amount'] ?? 0);
    $method = $_POST['payment_method'] ?? '';

    $allowed = ['Cash', 'M-Pesa', 'Card', 'Bank Transfer'];

    if ($booking && $amount > 0 &&
        in_array($method, $allowed, true)) {

        $check = $conn->prepare(
            "SELECT id FROM bookings WHERE id = ?"
        );

        $check->bind_param("i", $booking);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $stmt = $conn->prepare(
                "INSERT INTO rental_payments
                (booking_id, amount, payment_method)
                VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "ids",
                $booking,
                $amount,
                $method
            );

            if ($stmt->execute()) {
                $message = "Rental payment recorded successfully!";
            } else {
                $message = "Unable to record payment.";
            }

        } else {
            $message = "Selected booking was not found.";
        }

    } else {
        $message = "Please enter a valid booking, amount and payment method.";
    }
}

// Get bookings
$bookings = $conn->query(
    "SELECT b.id, c.full_name,
            v.registration_no, b.total_amount
     FROM bookings b
     JOIN customers c ON b.customer_id = c.id
     JOIN vehicles v ON b.vehicle_id = v.id
     WHERE b.booking_status != 'Cancelled'
     ORDER BY b.id DESC"
);

// Get payment history
$list = $conn->query(
    "SELECT p.*, c.full_name, v.registration_no
     FROM rental_payments p
     JOIN bookings b ON p.booking_id = b.id
     JOIN customers c ON b.customer_id = c.id
     JOIN vehicles v ON b.vehicle_id = v.id
     ORDER BY p.id DESC"
);

require 'header.php';

?>

<h1>Rental Payments</h1>

<?php if ($message): ?>

    <div class="notice">
        <?= htmlspecialchars($message) ?>
    </div>

<?php endif; ?>

<form method="POST">

    <h2>Record Rental Payment</h2>

    <label>Select Booking</label>

    <select name="booking_id" required>

        <option value="">-- Select Booking --</option>

        <?php while ($b = $bookings->fetch_assoc()): ?>

            <option value="<?= $b['id'] ?>">

                Booking #<?= $b['id'] ?> -

                <?= htmlspecialchars(
                    $b['full_name'] . ' / ' .
                    $b['registration_no'] . ' / KSh ' .
                    number_format($b['total_amount'], 2)
                ) ?>

            </option>

        <?php endwhile; ?>

    </select>

    <label>Amount Paid (KSh)</label>

    <input type="number"
           name="amount"
           min="1"
           step="0.01"
           required>

    <label>Payment Method</label>

    <select name="payment_method" required>

        <option value="">-- Select Payment Method --</option>

        <option value="Cash">Cash</option>

        <option value="M-Pesa">M-Pesa</option>

        <option value="Card">Card</option>

        <option value="Bank Transfer">Bank Transfer</option>

    </select>

    <button type="submit">Save Payment</button>

</form>

<h2>Payment History</h2>

<table>

<tr>
    <th>Booking ID</th>
    <th>Customer</th>
    <th>Vehicle</th>
    <th>Amount Paid</th>
    <th>Payment Method</th>
    <th>Date</th>
</tr>

<?php while ($p = $list->fetch_assoc()): ?>

<tr>

    <td>#<?= $p['booking_id'] ?></td>

    <td><?= htmlspecialchars($p['full_name']) ?></td>

    <td><?= htmlspecialchars($p['registration_no']) ?></td>

    <td>
        KSh <?= number_format($p['amount'], 2) ?>
    </td>

    <td><?= htmlspecialchars($p['payment_method']) ?></td>

    <td><?= htmlspecialchars($p['payment_date']) ?></td>

</tr>

<?php endwhile; ?>

</table>

<?php require 'footer.php'; ?>