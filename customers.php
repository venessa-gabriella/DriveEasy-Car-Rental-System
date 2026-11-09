<?php

require 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && $phone) {

        $stmt = $conn->prepare(
            "INSERT INTO customers
            (full_name, phone, email)
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $phone, $email);

        if ($stmt->execute()) {
            $message = "Customer record saved successfully!";
        } else {
            $message = "Unable to save customer record.";
        }

    } else {
        $message = "Full name and phone number are required.";
    }
}

$list = $conn->query(
    "SELECT * FROM customers ORDER BY id DESC"
);

require 'header.php';

?>

<h1>Customer Records</h1>

<?php if ($message): ?>
    <div class="notice">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST">

    <h2>Register Customer</h2>

    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Phone Number</label>
    <input type="text" name="phone" required>

    <label>Email Address</label>
    <input type="email" name="email">

    <button type="submit">Save Customer</button>

</form>

<h2>Registered Customers</h2>

<table>

<tr>
    <th>Full Name</th>
    <th>Phone Number</th>
    <th>Email</th>
    <th>Date Added</th>
</tr>

<?php while ($c = $list->fetch_assoc()): ?>

<tr>

    <td><?= htmlspecialchars($c['full_name']) ?></td>

    <td><?= htmlspecialchars($c['phone']) ?></td>

    <td><?= htmlspecialchars($c['email'] ?? '') ?></td>

    <td><?= htmlspecialchars($c['created_at']) ?></td>

</tr>

<?php endwhile; ?>

</table>

<?php require 'footer.php'; ?>