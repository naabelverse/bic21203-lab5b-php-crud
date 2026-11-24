<?php
require "auth.php";
require "db.php";
$result = $conn->query("SELECT matric, name, role FROM users ORDER BY matric");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box wide">
    <div class="panel-head">
        <h2>User List</h2>
        <span>Logged in as <b><?= htmlspecialchars($_SESSION["name"]) ?></b> (<?= htmlspecialchars($_SESSION["matric"]) ?>) &nbsp;|&nbsp; <a href="logout.php">Logout</a></span>
    </div>
    <div class="table-wrap">
    <table>
        <tr><th>Matric</th><th>Name</th><th>Level</th><th colspan="2">Action</th></tr>
        <?php while ($row = $result->fetch_assoc()): $m = urlencode($row["matric"]); ?>
        <tr>
            <td><?= htmlspecialchars($row["matric"]) ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["role"]) ?></td>
            <td class="action"><a href="update.php?matric=<?= $m ?>">Update</a></td>
            <td class="action"><a class="danger" href="delete.php?matric=<?= $m ?>" onclick="return confirm('Delete this user?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</div>
</body>
</html>
