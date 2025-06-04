<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/db.php';

// Проверяем метод
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Метод не поддерживается';
    exit;
}

// Определяем, AJAX-ли запрос
$isAjax = (
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
);

// Собираем данные из $_POST
$user_id               = (int) $_SESSION['user_id'];
$fio                   = trim($_POST['fio'] ?? '');
$passport_series       = trim($_POST['passport_series'] ?? '');
$passport_number       = trim($_POST['passport_number'] ?? '');
$snils                 = trim($_POST['snils'] ?? '');
$certificate_type      = trim($_POST['certificate_type'] ?? '');
$certificate_avg_score = trim($_POST['certificate_avg_score'] ?? '');
$grades_completed      = trim($_POST['grades_completed'] ?? '');
$exam_oge_score        = trim($_POST['exam_oge_score'] ?? '');
$exam_ege_score        = trim($_POST['exam_ege_score'] ?? '');
$specialty_id          = empty($_POST['specialty_id']) ? null : (int)$_POST['specialty_id'];

$errors = [];

// === 1) Валидация данных ===
if ($fio === '' || mb_strlen($fio) > 255) {
    $errors[] = 'Поле "ФИО" обязательно и не более 255 символов.';
}
if (!preg_match('/^\d{4}$/', $passport_series)) {
    $errors[] = 'Серия паспорта: 4 цифры.';
}
if (!preg_match('/^\d{6}$/', $passport_number)) {
    $errors[] = 'Номер паспорта: 6 цифр.';
}
if (!preg_match('/^\d{3}[\s-]?\d{3}[\s-]?\d{3}[\s-]?\d{2}$/u', $snils)) {
    $errors[] = 'СНИЛС должен быть в формате 123-456-789-00 или 12345678900.';
}
if ($certificate_type !== '9' && $certificate_type !== '11') {
    $errors[] = 'Неверный тип аттестата.';
}
$certScore = filter_var($certificate_avg_score, FILTER_VALIDATE_FLOAT);
if ($certScore === false || $certScore < 2 || $certScore > 5) {
    $errors[] = 'Средний балл должен быть от 2.00 до 5.00.';
}
if ($certificate_type === '9') {
    // ОГЭ: число 0–100
    if ($exam_oge_score === '' || !ctype_digit($exam_oge_score) || (int)$exam_oge_score < 0)  {
        $errors[] = 'Баллы ОГЭ: целое число больше 0.';
    }
    $exam_ege_score = null; // именно NULL, т.к. не нужен
} else {
    // ЕГЭ: число 0–300
    if ($exam_ege_score === '' || !ctype_digit($exam_ege_score) || (int)$exam_ege_score < 0) {
        $errors[] = 'Баллы ЕГЭ: целое число больше 0.';
    }
    $exam_oge_score = null;
}
if (!in_array($grades_completed, ['9','11'], true)) {
    $errors[] = 'Укажите, сколько классов окончили.';
}

// Проверяем, выбрана ли специальность и существует ли она
if (empty($specialty_id) || $specialty_id <= 0) {
    $errors[] = 'Выберите, пожалуйста, специальность.';
} else {
    $stmtCheck = $mysqli->prepare("SELECT COUNT(*) FROM `specialties` WHERE `id` = ?");
    if (!$stmtCheck) {
        // критическая ошибка
        $errors[] = "Ошибка БД: ". $mysqli->error;
    } else {
        $stmtCheck->bind_param('i', $specialty_id);
        $stmtCheck->execute();
        $stmtCheck->bind_result($countSpec);
        $stmtCheck->fetch();
        $stmtCheck->close();
        if ($countSpec == 0) {
            $errors[] = 'Выбранная специальность не найдена.';
        }
    }
}

// Если ошибки есть — сразу возвращаем их пользователю
if (!empty($errors)) {
    if ($isAjax) {
        // Вернём JSON со списком ошибок и статусом 400
        header('Content-Type: application/json; charset=UTF-8', true, 400);
        echo json_encode([
            'success' => false,
            'errors'  => $errors
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Обычный POST: выведем ошибки и ссылку назад
        foreach ($errors as $err) {
            echo '<p style="color:red;">' . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        echo '<p><a href="index2.php">Вернуться к форме</a></p>';
        exit;
    }
}


// === 2) Вставка в таблицу applications ===
$sql = "INSERT INTO `applications`
    (`user_id`, `fio`, `passport_series`, `passport_number`, `snils`,
     `certificate_type`, `certificate_avg_score`,
     `exam_oge_score`, `exam_ege_score`, `grades_completed`, `specialty_id`)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    // Если ошибка подготовки запроса, тоже возвращаем
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8', true, 500);
        echo json_encode([
            'success' => false,
            'errors'  => ['Ошибка подготовки запроса: ' . $mysqli->error]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        die("Ошибка подготовки запроса: " . htmlspecialchars($mysqli->error, ENT_QUOTES, 'UTF-8'));
    }
}

$stmt->bind_param(
    'isssssidiii',
    $user_id,
    $fio,
    $passport_series,
    $passport_number,
    $snils,
    $certificate_type,
    $certScore,
    $exam_oge_score,
    $exam_ege_score,
    $grades_completed,
    $specialty_id
);

if (!$stmt->execute()) {
    // Ошибка исполнения INSERT
    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8', true, 500);
        echo json_encode([
            'success' => false,
            'errors'  => ['Ошибка при сохранении заявления: ' . $stmt->error]
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        die("Ошибка выполнения INSERT: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8'));
    }
}

$stmt->close();


// === 3) Возвращаем успешный ответ ===
if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success' => true,
        'message' => '✅ Заявление успешно отправлено!'
    ], JSON_UNESCAPED_UNICODE);
    exit;
} else {
    header('Location: my_applications.php?success=1');
    exit;
}

