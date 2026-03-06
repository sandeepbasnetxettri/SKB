<?php
$basePath = "../";
include_once $basePath . 'includes/auth.php';

logoutUser();

header("Location: " . $basePath . "index.php");
exit();
?>
