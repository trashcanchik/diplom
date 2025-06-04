<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/connect.php';

// тут уже ваш код, например выборка всех заявок, проверка роли и т.д.

require 'auth.php';
if ($_SESSION['role']!=='admin') die('Доступ запрещён');
require 'connect.php';

$res = $conn->query("
  SELECT a.id,a.fio,a.created_at,u.username 
  FROM applications a 
  JOIN users u ON a.user_id=u.id 
  ORDER BY a.created_at DESC
");
?>
<h1>Все заявки</h1>
<table>… вывод данных …</table>
