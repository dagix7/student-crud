<?php
include 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

$name = $student['name'];
$email = $student['email'];
$age = $student['age'];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $age   = (int)$_POST["age"];

    if (empty($name)) $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($age <= 0) $errors[] = "Valid age is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, age=? WHERE id=?");
        $stmt->bind_param("ssii", $name, $email, $age, $id);
        $stmt->execute();
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>Edit Student</h2>
        <a href="index.php" class="btn btn-secondary mb-3">Back</a>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
            </div>
            <div class="mb-3">
                <label>Age:</label>
                <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($age) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Student</button>
        </form>
    </div>
</body>
</html>
