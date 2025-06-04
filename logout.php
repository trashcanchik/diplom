<?php

session_start();
session_destroy();
header('Location: index.php');

$_SESSION['user_id'] = $userData['id'];
$_SESSION['username'] = $userData['username'];
$_SESSION['is_admin'] = (int)$userData['is_admin']; // 0 или 1

exit;
