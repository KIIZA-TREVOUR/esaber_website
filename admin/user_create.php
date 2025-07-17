<?php
include_once 'includes/connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Validate required fields
    if ($username && $email && $password && $role) {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the insert query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $username, $email, $password_hash, $role, $is_active);

        if ($stmt->execute()) {
            // Redirect to user list with success message
            header("Location: user.php?success=1");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="assets/css/app.min.css">
</head>
<body>
<?php if (isset($error)): ?>
    <div class="alert alert-danger" style="margin:20px;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<a href="add-user.php" class="btn btn-secondary" style="margin:20px;">Back to Add User</a>
</body>
</html> 