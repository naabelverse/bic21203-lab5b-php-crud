<?php
require "auth.php";
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $original = $_POST["original_matric"] ?? "";
    $matric   = trim($_POST["matric"] ?? "");
    $name     = trim($_POST["name"] ?? "");
    $role     = trim($_POST["role"] ?? "");

    if ($matric === "" || $name === "" || strlen($matric) > 10 || strlen($name) > 100
        || !in_array($role, ["lecturer", "student"], true)) {
        $error = "Please fill in all fields. Access Level must be lecturer or student.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?");
        $stmt->bind_param("ssss", $matric, $name, $role, $original);
        try {
            $stmt->execute();
            if ($original === $_SESSION["matric"]) {
                $_SESSION["matric"] = $matric;
                $_SESSION["name"] = $name;
            }
            header("Location: users.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() !== 1062) throw $e;
            $error = "Matric already exists.";
        }
    }
}

$matric = $_POST["original_matric"] ?? $_GET["matric"] ?? "";
$stmt = $conn->prepare("SELECT matric, name, role FROM users WHERE matric = ?");
$stmt->bind_param("s", $matric);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if (!$user) {
    die("User not found. <a href='users.php'>Back</a>");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h1>Update User</h1>
    <?php if (!empty($error)): ?><p class="msg error"><?= $error ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="original_matric" value="<?= htmlspecialchars($user["matric"]) ?>">
        <label>Matric <input type="text" name="matric" maxlength="10" value="<?= htmlspecialchars($user["matric"]) ?>" required></label>
        <label>Name <input type="text" name="name" maxlength="100" value="<?= htmlspecialchars($user["name"]) ?>" required></label>
        <label>Access Level <input type="text" name="role" maxlength="10" value="<?= htmlspecialchars($user["role"]) ?>" required></label>
        <div class="actions">
            <input type="submit" value="Update">
            <a class="btn secondary" href="users.php">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
