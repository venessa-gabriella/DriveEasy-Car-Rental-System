<?php

require 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reg = trim($_POST['registration_no'] ?? '');
    $model = trim($_POST['make_model'] ?? '');
    $rate = (float)($_POST['daily_rate'] ?? 0);

    if ($reg && $model && $rate > 0) {

        $stmt = $conn->prepare(
            "INSERT INTO vehicles
            (registration_no, make_model, daily_rate)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param("ssd", $reg, $model, $rate);

        if ($stmt->execute()) {
            $message = "Vehicle registered successfully!";
        } else {
            $message = "Vehicle could not be saved. Check the registration number.";
        }

    } else {
        $message = "Please fill in all fields correctly.";
    }
}

$list = $conn->query(
    "SELECT * FROM vehicles ORDER BY id DESC"
);

require 'header.php';

?>

<h1>Vehicle Registration</h1>

<?php if ($message): ?>
    <div class="notice">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST">

    <h2>Register New Vehicle</h2>

    <label>Registration Number</label>
    <input type="text" name="registration_no"
           placeholder="e.g. KDA 123A" required>

    <label>Make / Model</label>
    <input type="text" name="make_model"
           placeholder="e.g. Toyota Axio" required>

    <label>Daily Rental Rate (KSh)</label>
    <input type="number" name="daily_rate"
           min="1" step="0.01" required>

    <button type="submit">Save Vehicle</button>

</form>

<h2>Registered Vehicles</h2>

<table>

<tr>
    <th>Registration</th>
    <th>Make / Model</th>
    <th>Daily Rate</th>
    <th>Status</th>
</tr>

<?php while ($v = $list->fetch_assoc()): ?>

<tr>
    <td><?= htmlspecialchars($v['registration_no']) ?></td>

    <td><?= htmlspecialchars($v['make_model']) ?></td>

    <td>
        KSh <?= number_format($v['daily_rate'], 2) ?>
    </td>

    <td><?= htmlspecialchars($v['status']) ?></td>
</tr>

<?php endwhile; ?>

</table>

<?php require 'footer.php'; ?>