<?php

require 'db.php';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $customer = (int)($_POST['customer_id'] ?? 0);
    $vehicle = (int)($_POST['vehicle_id'] ?? 0);

    $start = $_POST['start_date'] ?? '';
    $end = $_POST['end_date'] ?? '';

    if (!$customer || !$vehicle || !$start || !$end || $end < $start) {

        $message = "Please select a customer, vehicle and valid rental dates.";

    } else {

        // Calculate rental days
        $days = (new DateTime($start))
            ->diff(new DateTime($end))->days + 1;

        // Check whether vehicle is already booked
        $check = $conn->prepare(
            "SELECT COUNT(*) AS overlaps
             FROM bookings
             WHERE vehicle_id = ?
             AND booking_status = 'Booked'
             AND NOT (end_date < ? OR start_date > ?)"
        );

        $check->bind_param("iss", $vehicle, $start, $end);
        $check->execute();

        $overlap = $check->get_result()
            ->fetch_assoc()['overlaps'];

        // Get vehicle rental rate
        $rateStmt = $conn->prepare(
            "SELECT daily_rate FROM vehicles
             WHERE id = ? AND status = 'Available'"
        );

        $rateStmt->bind_param("i", $vehicle);
        $rateStmt->execute();

        $vehicleRow = $rateStmt->get_result()->fetch_assoc();

        if ($overlap > 0) {

            $message = "Sorry! This vehicle is already booked for the selected dates.";

        } elseif (!$vehicleRow) {

            $message = "This vehicle is not available for booking.";

        } else {

            // Calculate total amount
            $total = $days * (float)$vehicleRow['daily_rate'];

            // Save booking
            $stmt = $conn->prepare(
                "INSERT INTO bookings
                (customer_id, vehicle_id, start_date,
                 end_date, total_amount)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "iissd",
                $customer,
                $vehicle,
                $start,
                $end,
                $total
            );

            if ($stmt->execute()) {

                $success = true;

                $message = "Booking successful! Your car has been booked. Total rental cost: KSh "
                    . number_format($total, 2);

            } else {

                $message = "Booking could not be completed. Please try again.";

            }
        }
    }
}

// Get registered customers
$customers = $conn->query(
    "SELECT id, full_name, phone
     FROM customers ORDER BY full_name"
);

// Get available vehicles
$vehicles = $conn->query(
    "SELECT id, registration_no, make_model, daily_rate
     FROM vehicles
     WHERE status = 'Available'
     ORDER BY make_model"
);

// Get booking records
$list = $conn->query(
    "SELECT b.*, c.full_name,
            v.registration_no, v.make_model
     FROM bookings b
     JOIN customers c ON b.customer_id = c.id
     JOIN vehicles v ON b.vehicle_id = v.id
     ORDER BY b.id DESC"
);

require 'header.php';

?>

<h1>Car Bookings</h1>

<?php if ($message): ?>

    <div class="<?= $success ? 'notice' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>

<?php endif; ?>

<form method="POST">

    <h2>Create New Booking</h2>

    <label>Select Customer</label>

    <select name="customer_id" required>

        <option value="">-- Select Customer --</option>

        <?php while ($c = $customers->fetch_assoc()): ?>

            <option value="<?= $c['id'] ?>">

                <?= htmlspecialchars(
                    $c['full_name'] . ' - ' . $c['phone']
                ) ?>

            </option>

        <?php endwhile; ?>

    </select>

    <label>Select Vehicle</label>

    <select name="vehicle_id" required>

        <option value="">-- Select Vehicle --</option>

        <?php while ($v = $vehicles->fetch_assoc()): ?>

            <option value="<?= $v['id'] ?>">

                <?= htmlspecialchars(
                    $v['registration_no'] . ' | ' .
                    $v['make_model'] . ' | KSh ' .
                    number_format($v['daily_rate'], 2) . '/day'
                ) ?>

            </option>

        <?php endwhile; ?>

    </select>

    <label>Rental Start Date</label>

    <input type="date"
           name="start_date"
           min="<?= date('Y-m-d') ?>"
           required>

    <label>Return Date</label>

    <input type="date"
           name="end_date"
           min="<?= date('Y-m-d') ?>"
           required>

    <button type="submit">Book Car</button>

    <p class="small">
        Rental charges are calculated automatically.
        Both the pick-up and return dates are counted.
    </p>

</form>

<h2>Booking Records</h2>

<table>

<tr>
    <th>Customer</th>
    <th>Vehicle</th>
    <th>Rental Period</th>
    <th>Total Amount</th>
    <th>Status</th>
</tr>

<?php while ($b = $list->fetch_assoc()): ?>

<tr>

    <td><?= htmlspecialchars($b['full_name']) ?></td>

    <td>
        <?= htmlspecialchars(
            $b['registration_no'] . ' ' . $b['make_model']
        ) ?>
    </td>

    <td>
        <?= htmlspecialchars(
            $b['start_date'] . ' to ' . $b['end_date']
        ) ?>
    </td>

    <td>
        KSh <?= number_format($b['total_amount'], 2) ?>
    </td>

    <td><?= htmlspecialchars($b['booking_status']) ?></td>

</tr>

<?php endwhile; ?>

</table>

<?php if ($success): ?>

<script>
    alert(<?= json_encode($message) ?>);
</script>

<?php endif; ?>

<?php require 'footer.php'; ?>