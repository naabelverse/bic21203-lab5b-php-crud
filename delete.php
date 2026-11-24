<?php
require "auth.php";
require "db.php";

$matric = $_GET["matric"] ?? "";
$stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
$stmt->bind_param("s", $matric);
$stmt->execute();

if ($matric === $_SESSION["matric"]) {
    session_destroy();
    header("Location: login.php");
} else {
    header("Location: users.php");
}
exit;
