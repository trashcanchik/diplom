<?php
// auth.php
session_start();

// Если пользователь не залогинен — редирект на страницу логина
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Дополнительно: проверка роли для админских страниц
 if ($_SESSION['role'] !== 'admin') {
   die('Доступ запрещён');
 }
