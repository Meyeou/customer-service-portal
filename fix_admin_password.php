<!-- <?php
include 'connect.php';

$staff_number = 'your staff number'; // admin's staff number
$new_password = 'your password';
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE staff_number = ?");
$stmt->bind_param("ss", $hashed, $staff_number);
if ($stmt->execute()) {
    echo "✅ Admin password updated successfully.";
} else {
    echo "❌ Error: " . $stmt->error;
}
?> -->
