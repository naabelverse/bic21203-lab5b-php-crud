<?php
require "db.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matric   = trim($_POST["matric"] ?? "");
    $name     = trim($_POST["name"] ?? "");
    $password = $_POST["password"] ?? "";
    $role     = $_POST["role"] ?? "";

    if ($matric === "" || $name === "" || $password === "" || strlen($matric) > 10 || strlen($name) > 100
        || !in_array($role, ["lecturer", "student"], true)) {
        $message = "Error: please fill in all fields correctly.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $matric, $name, $hash, $role);
        try {
            $stmt->execute();
            $message = "Registration successful. <a href='login.php'>Login</a> now.";
            $ok = true;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() !== 1062) throw $e;
            $message = "Error: matric " . htmlspecialchars($matric) . " is already registered.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h2>Register</h2>
    <?php if ($message): ?>
        <p class="msg <?= empty($ok) ? "error" : "success" ?>"><?= $message ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Matric: <input type="text" name="matric" maxlength="10" required></label>
        <label>Name: <input type="text" name="name" maxlength="100" required></label>
        <label>Password: <input type="password" name="password" required></label>
        <label>Role:
            <select name="role" required>
                <option value="">Please select</option>
                <option value="lecturer">Lecturer</option>
                <option value="student">Student</option>
            </select>
        </label>
        <input type="submit" value="Submit">
    </form>
    <p class="footer">Already registered? <a href="login.php">Login</a></p>
</div>
</body>
</html>
