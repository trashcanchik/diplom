<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';
$user_id = (int) $_SESSION['user_id'];

// Получаем все заявления текущего пользователя
$sql = "
  SELECT
    a.`created_at`         AS `date_submitted`,
    a.`fio`,
    a.`certificate_type`,
    a.`certificate_avg_score`,
    a.`exam_oge_score`,
    a.`exam_ege_score`,
    a.`grades_completed`,
    s.`name`               AS `specialty_name`
  FROM `applications` AS a
  LEFT JOIN `specialties` AS s
    ON a.`specialty_id` = s.`id`
  WHERE a.`user_id` = ?
  ORDER BY a.`created_at` DESC
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$apps = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заявления</title> 
</head>
<body>
    <h2>Мои заявления</h2>
    <?php if (isset($_GET['success'])): ?>
        <p style="color:green;">Заявление успешно отправлено!</p>
    <?php endif; ?>

    <?php if (empty($apps)): ?>
        <p>У вас пока нет заявлений. 
    <button id="openFormFromAppsBtn" class="link-button">Подать заявление</button>
  </p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Дата подачи</th>
                <th>ФИО</th>
                <th>Тип аттестата</th>
                <th>Средний балл</th>
                <th>Баллы</th>
                <th>Классы</th>
                <th>Специальность</th>
                <th>Статус</th>
            </tr>
            <?php foreach ($apps as $row): ?>
                <tr>
                <td><?= htmlspecialchars($row['date_submitted'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['fio']) ?></td>
                    <td><?= $row['certificate_type'] === '9' ? '9 классов' : '11 классов' ?></td>
                    <td><?= htmlspecialchars($row['certificate_avg_score']) ?></td>
                    <td>
                        <?php if ($row['certificate_type'] === '9'): ?>
                            ОГЭ: <?= htmlspecialchars($row['exam_oge_score']) ?>
                        <?php else: ?>
                            ЕГЭ: <?= htmlspecialchars($row['exam_ege_score']) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['grades_completed']) ?></td>
                    <td>
                    <?= htmlspecialchars($row['specialty_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td>На рассмотрении</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
