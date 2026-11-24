<?php
session_start();
require "db.php";
$failed = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("SELECT matric, name, password FROM users WHERE matric = ?");
    $matric = trim($_POST["matric"] ?? "");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($_POST["password"] ?? "", $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["matric"] = $user["matric"];
        $_SESSION["name"] = $user["name"];
        header("Location: users.php");
        exit;
    }
    $failed = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h2>Login</h2>
<?php if ($failed): ?>
    <p class="msg error">Invalid username or password, try <a href="login.php">login</a> again.</p>
<?php else: ?>
    <form method="post">
        <label>Matric: <input type="text" name="matric" required></label>
        <label>Password: <input type="password" name="password" required></label>
        <input type="submit" value="Login">
    </form>
    <p class="footer"><a href="register.php">Register</a> here if you have not.</p>
<?php endif; ?>
</div>
</body>
</html>
