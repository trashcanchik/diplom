<?php
// connect.php
session_start();
require_once __DIR__ . '/db.php';

$errorLogin    = '';
$errorRegister = '';

// --- Обработка POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Вход
    if (isset($_POST['login'])) {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
if (!$stmt) {
    die("Ошибка подготовки запроса: " . $mysqli->error);
}

$stmt->bind_param('s', $u);

$stmt->execute();

$result = $stmt->get_result();            
$row    = $result->fetch_assoc();         
 {
            if (password_verify($p, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username']  = $u;  
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            }
        }
        $errorLogin = 'Неверный логин или пароль.';
    }
    // Регистрация
    if (isset($_POST['register'])) {
        $u  = trim($_POST['username'] ?? '');
        $p1 = $_POST['password'] ?? '';
        $p2 = $_POST['password_confirm'] ?? '';
        if ($p1 !== $p2) {
            $errorRegister = 'Пароли не совпадают.';
        } elseif (strlen($p1) < 6) {
            $errorRegister = 'Пароль минимум 6 символов.';
        } else {
            // проверка занятости
            $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
if (!$stmt) {
    die("Ошибка подготовки: " . $mysqli->error);
}
$stmt->bind_param('s', $u);
$stmt->execute();

$result = $stmt->get_result();
$row    = $result->fetch_row();      // [0] = COUNT(*)
$countExisting = (int)$row[0];
$stmt->close();

if ($countExisting > 0) {
    $errorRegister = 'Пользователь уже существует.';
}
             else {
              $hash = password_hash($p1, PASSWORD_DEFAULT);
                $ins  = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $ins->execute([$u, $hash]);
                // сразу логиним
                $_SESSION['user_id'] = $mysqli->insert_id;
                $_SESSION['username'] = $u; 
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            }
        }
    }
}

$loggedIn = isset($_SESSION['user_id']);
?>
<!-- Кнопка-иконка -->
<button id="auth-button" class="auth-toggle" aria-label="User menu">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
  </svg>
</button>

<!-- Модальное окно -->
<div id="auth-modal" class="auth-modal">
  <div class="auth-modal-content">
    <span id="auth-close" class="auth-close">&times;</span>

    <?php if ($loggedIn): ?>
      <p>Здравствуйте, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
      <a href="logout.php">Выйти</a>
    <?php else: ?>

      <div class="auth-tabs">
        <button id="login-tab"   class="auth-tab auth-tab--active">Вход</button>
        <button id="register-tab"class="auth-tab">Регистрация</button>
      </div>

      <div id="login-form" class="auth-form">
        <?php if ($errorLogin): ?><p class="auth-error"><?=htmlspecialchars($errorLogin)?></p><?php endif;?>
        <form method="post">
          <input name="username" placeholder="Логин" required>
          <input name="password" type="password" placeholder="Пароль" required>
          <button name="login" type="submit">Войти</button>
        </form>
        <p class="auth-switch">
          Нет аккаунта? <a href="#" class="auth-switch-link" data-target="register">Зарегистрируйтесь</a>
        </p>
      </div>

      <div id="register-form" class="auth-form auth-form--hidden">
        <?php if ($errorRegister): ?><p class="auth-error"><?=htmlspecialchars($errorRegister)?></p><?php endif;?>
        <form method="post">
          <input name="username" placeholder="Логин" required>
          <input name="password" type="password" placeholder="Пароль" required>
          <input name="password_confirm" type="password" placeholder="Повторите пароль" required>
          <button name="register" type="submit">Регистрация</button>
        </form>
        <p class="auth-switch">
          Уже есть аккаунт? <a href="#" class="auth-switch-link" data-target="login">Войти</a>
        </p>
      </div>

    <?php endif; ?>
  </div>
</div>
