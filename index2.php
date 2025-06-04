<?php
session_start();
require_once __DIR__ . '/db.php'; 

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
$currentRole = $_SESSION['role'] ?? 'user';

$specialties = [];
$sql = "SELECT id, name FROM specialties ORDER BY name";
if ($res = $mysqli->query($sql)) {
    while ($row = $res->fetch_assoc()) {
        
        $specialties[] = $row;
    }
    $res->free();
} else {
    
    $specialties = [];
}
?>
  <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Люберецкий техникум</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="application_form.css">
</head>
<body>
    <div class="header-wrapper">
    <header class="header">
        <div class="logo">
        <a href="https://mail.google.com/mail/u/0/#inbox?compose=GTvVlcSGLdVzHZnVRZRxdVzZkWKZBDlCZmdhlppHvSDgcNPmpSdTNPLTpTGNMwNtwzzfSBDkbVrQc">
            <img src="image 7.png" alt="Логотип"> E-mail: mo_gagarintechn@mosreg.ru </a>
        </div>
        <nav class="nav">
        <a href="tel:+7 (495) 503 45 77">
            <img src="image 6.png" alt="Навигация"> Тел.: 8(495) 503-4577 </a>
        </nav>
        <div class="contacts">
        <a href="#bottom">
            <img src="image 5.png" alt="Контакты"> г. Люберцы, Октябрьский проспект, д.114 </a>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
<div class="my-apps-link">
  <!-- Кнопка для открытия модалки "Мои заявления" -->
  <button id="appsOpenBtn" class="link-button">Мои заявления</button>
</div>

      <?php endif; ?>
    </header>


    <div class="header_content_left">
    <a href="index.php">
        <img src="лого.png" alt="Логотип" width="190">
</a>
    </div>

    <div class="header_content_right">
        <div class="obvodka">
            <div class="dkd">
                <img src="image 2.png" alt="">
                <h5>Министерство просвещения <br>Российской Федерации </h5>
                <img src="шьфпу3.png" alt="">
                <h5>Министерство образования <br>Московской области </h5>
            </div>
            <div class="fail">
            <a href="https://t.me/luberteh">
                <img src="image 3.png" alt="">
                </a>
                <a href="https://vk.com/luberteh?from=search">
                <img src="image 4.png" alt="">
                </a>
                <img src="Group 7.png" alt="">
            </div>
        </div>
    </div>

    <div class="header_content_right2">
        <div class="tehnikym">О техникуме</div>
        <div class="abityrenty">Абитуриенту</div>
        <div class="stybenty">Студенту</div>
        <div class="vipyskniky">Выпускнику</div>
        <div class="pedagogy">Педагогу</div>
    </div>
    <head>
        <meta charset="UTF-8">
        <title>Абитуриенту</title>
        <link rel="stylesheet" href="style2.css">
      </head>
    
        
      
        <section class="section">        
          <h1 class="section-title">АБИТУРИЕНТУ</h1>
          <div class="grid">
            <div class="card active">
              <h3>Приказ о зачислении</h3>
              <p>от 19.08.2024 № 54-у</p>
              <span>&#8250;</span>
            </div>
            <div class="card">
              <h3>Дополнительный приказ о зачислении</h3>
              <p>от 19.08.2024 № 54-у</p>
              <span>&#8250;</span>
            </div>
            <div class="card">
              <h3>Списки рекомендованных к зачислению</h3>
              <p>внебюджет</p>
              <span>&#8250;</span>
            </div>
            <div class="card"><h3>Правила приема</h3><span>&#8250;</span></div>
            <div class="card" onclick="openModal()"> 
              <h3>Перечень специальностей по которым техникум объявляет прием на 2025-2026 учебный год</h3><span>&#8250;</span></div>
            <div class="card"><h3>Документы для поступления</h3><span>&#8250;</span></div>
            <div class="card"><h3>Вступительные испытания</h3><span>&#8250;</span></div>
            <div class="card"><h3>Целевое обучение</h3><span>&#8250;</span></div>
            <div class="card"><h3>Вопрос-ответ для поступающих</h3><span>&#8250;</span></div>
            <div class="card"><h3>Инструкция по подаче заявления</h3><span>&#8250;</span></div>
            <div class="card"><h3>Платные образовательные услуги</h3><span>&#8250;</span></div>
            <div class="card"><h3>Подготовительные курсы</h3><span>&#8250;</span></div>
            <div class="card"><h3>График и сроки работы приёмной комиссии</h3><span>&#8250;</span></div>
            <div class="card"><h3>Документы приёмной комиссии</h3><span>&#8250;</span></div>
          </div>
        </section>
        
        <div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>

<div id="modalWindow" class="modal-window">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <h2>Выберите корпус</h2>

    <button class="corp-btn" onclick="toggleSpec(0)">Центральный корпус</button>
    <div class="spec-info">г. Люберцы, Октябрьский пр-кт, д. 114<br>
      <br>
      Специалист по техническому обслуживанию и ремонту автотранспортных средств<br>
      Техник по обслуживанию авиационной техники<br>
      Оператор беспилотных летательных аппаратов<br> 
      Юрист<br> 
      Учитель начальных классов<br> 
      Специалист по документационному обеспечению управления и архивному делу<br>
    </div>

    <button class="corp-btn" onclick="toggleSpec(1)">Гагаринский корпус</button>
    <div class="spec-info">г. Люберцы, Октябрьский проспект, д. 136<br>
      <br>
      Техник<br>
      Специалист по землеустройству<br> 
      Бухгалтер<br>
      Специалист торгового дела<br> 
      Специалист по поварскому и кондитерскому делу<br> 
      Оператор – наладчик металлообрабатывающих станков</div>

    <button class="corp-btn" onclick="toggleSpec(2)">Корпус Красково</button>
    <div class="spec-info">г. Люберцы, пос. Красково, ул. 2-ая Заводская, д. 11<br>
      <br>
      Системный администратор<br>
      Оператор беспилотных летательных аппаратов<br> 
      Операционный логист<br> 
      Оптик – механик; контролёр оптических деталей и приборов оптик<br> 
      Аппаратчик – оператор производство продуктов питания из растительного сырья<br> 
      Мастер по ремонту и обслуживанию автомобилей<br>
    </div>

    <button class="corp-btn" onclick="toggleSpec(3)">Корпус Угреша</button>
    <div class="spec-info">
      г. Дзержинский, ул. Академика Жукова, д. 24<br>
      <br>
      Графический дизайнер<br>
      Повар–кондитер<br>
      Мастер садово–паркового и ландшафтного строительства<br>
      Лаборант<br>
      Мастер слесарных работ Мастер инженерных систем жилищно – коммунального хозяйства<br> 
      Операционный логист<br>
      Техник<br>
      Техник – технолог<br> 
      Разработчик Web и мультимедийных приложений</div>
  </div>
</div>
    
<div class="maim-container">

      <section class="apply-section">
        <div class="apply-text">
            <link rel="stylesheet" href="style2.css">
          <h2>ПОРЯДОК ПОДАЧИ ЗАЯВЛЕНИЙ</h2>
          <p><strong>Уважаемые студенты!</strong></p>
          <p>
            Государственная услуга «Приём документов на обучение в образовательные организации Московской области,
            подведомственные Министерству образования Московской области, реализующие программы среднего
            профессионального образования» ведётся на сайте <strong>Госуслуг Московской области.</strong>
          </p>
        </div>
      
        <div class="apply-cards">

  <button type="button" id="openFormModalBtn" class="apply-card light">
    <div class="card-title">Подать заявление на поступление</div>
    <div class="card-icon">
      <img src="клик.png" alt="Иконка курсора">
    </div>
  </button>
</div>

      
      <a href="Правила подачи заявления.pdf" class="apply-card dark" download>
            <div class="card-title">Скачать инструкцию</div>
            <div class="card-icon">
              <img src="загрузка.png" alt="Иконка загрузки">
            </div>
            <div class="card-note">PDF файл</div>
          </a>
        </div>
      </section>
      <section class="location-section">
        <div class="location-text">
          <h2>Место приема документов</h2>
          <p>
            от поступающих в ГБПОУ МО<br>
            «Люберецкий техникум имени Героя Советского Союза,<br>летчика-космонавта Ю.А. Гагарина»<br>
            Московская область, г. Люберцы,<br>
            Октябрьский проспект, д.114
          </p>
        </div>
      
        <div class="location-table">
          <table>
            <thead>
              <tr>
                <th>Если Вы подали заявление</th>
                <th>Регистрация вашего заявления будет</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>До 16:00</strong> рабочего дня</td>
                <td>В день подачи</td>
              </tr>
              <tr>
                <td><strong>После 16:00</strong> рабочего дня либо в субботу/воскресенье</td>
                <td>На следующий день</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      <head>
        <meta charset="UTF-8">
        <title>Карта</title>
        <style>
          body, html {
            margin: 0;
            padding: 0;
          }
          .full-width-image {
            width: 100vw; /* 100% ширины окна */
            height: auto;
            display: block;
          }
        </style>
      </head>
      
      
      <div id="map"></div>
        <div class="map-block">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A5ac8ea51bb8d581894d3654be0d188681c5c8ceedee302923cbd56d600188b5b&amp;source=constructor" width="100%" height="558" frameborder="0"></iframe>
              <a id="bottom"></a>
            </div>
          </div>
<!-- =============================================== -->
<!-- МОДАЛЬНОЕ ОКНО «Мои заявления» (скрыто по умолчанию) -->
<!-- =============================================== -->
<div id="appsModalOverlay" class="apps-modal-overlay" style="display: none;">
  <div class="apps-modal-window">
    <button type="button" class="apps-modal-close" id="appsCloseBtn">&times;</button>
    <div id="appsModalContent">
      <!-- Здесь через JavaScript подгрузится HTML-таблица заявлений -->
      <p class="apps-modal-loading">Загрузка…</p>
    </div>
  </div>
</div>

        <!-- ===========================================
     МОДАЛЬНОЕ ОКНО С ФОРМОЙ (скрыто по умолчанию)
     =========================================== -->
     <div id="formModalOverlay" class="modal-overlay-new" style="display: none;">
  <div class="modal-window-new">
    <button type="button" class="modal-close-new" id="closeFormModal">&times;</button>

    <h2>Подача заявления</h2>
    <form id="applicationForm" action="submit_application.php" method="post">
      <label>ФИО:<br>
        <input type="text" name="fio" required maxlength="255" class="modal-input-new">
      </label><br><br>

      <label>Серия паспорта:<br>
        <input type="text" name="passport_series"
               required pattern="\d{4}" maxlength="4" placeholder="4 цифры"
               class="modal-input-new">
      </label><br><br>

      <label>Номер паспорта:<br>
        <input type="text" name="passport_number"
               required pattern="\d{6}" maxlength="6" placeholder="6 цифр"
               class="modal-input-new">
      </label><br><br>

      <label>СНИЛС:<br>
        <input type="text" name="snils" required
               pattern="\d{3}[- ]?\d{3}[- ]?\d{3}[- ]?\d{2}"
               placeholder="111-222-333-44 или 11122233344"
               class="modal-input-new">
      </label><br><br>

      <label>Тип аттестата:<br>
        <select name="certificate_type" id="certificate_type_form" required class="modal-select-new">
          <option value="">-- Выберите --</option>
          <option value="9">9 классов</option>
          <option value="11">11 классов</option>
        </select>
      </label><br><br>

      <label>Средний балл аттестата:<br>
        <input type="number" name="certificate_avg_score"
               step="0.01" min="2" max="5" required placeholder="4.50"
               class="modal-input-new">
      </label><br><br>

      <div id="oge_block_form" class="modal-block-new hidden-block-new">
        <label>Баллы ОГЭ:<br>
          <input type="number" name="exam_oge_score" min="0"  class="modal-input-new">
        </label><br><br>
      </div>

      <div id="ege_block_form" class="modal-block-new hidden-block-new">
        <label>Баллы ЕГЭ:<br>
          <input type="number" name="exam_ege_score" min="0"  class="modal-input-new">
        </label><br><br>
      </div>

      <label>Сколько классов окончили:<br>
        <select name="grades_completed" required class="modal-select-new">
          <option value="">-- Выберите --</option>
          <option value="9">9</option>
          <option value="11">11</option>
        </select>
      </label><br><br>

      <label>Специальность:<br>
  <select name="specialty_id" required class="modal-select-new">
    <option value="">— Выберите специальность —</option>
    <?php foreach ($specialties as $spec): ?>
      <option 
        value="<?php echo (int)$spec['id']; ?>" 
        <?php 
          if (isset($_POST['specialty_id']) && (int)$_POST['specialty_id'] === (int)$spec['id']) {
            echo ' selected';
          }
        ?>
      >
        <?php echo htmlspecialchars($spec['name'], ENT_QUOTES, 'UTF-8'); ?>
      </option>
    <?php endforeach; ?>
  </select>
</label><br><br>

<button id="submitAppBtn" type="submit" class="send-application-btn">Отправить заявление</button>
    </form>
    <div id="ajaxSuccessMsg" class="ajax-success-message hidden"></div>
  </div>
</div>

<!-- =========================================== -->
<script src="script.js"></script>
<script src="script2.js"></script>
<script src="application-ajax.js"></script>
        </body>
      
      <head>
        <meta charset="UTF-8">
        <title>Подвал страницы</title>
        <link rel="stylesheet" href="style.css"> <!-- Подключение CSS -->
      </head>
      <body>
      
      <footer class="footer">
        <h2>ГБПОУ МО Люберецкий техникум имени Героя Советского Союза,<br>
            лётчика-космонавта Ю. А. Гагарина</h2>
      
        <nav class="footer-nav">
          <a href="#">Время работы</a>
          <a href="#">Схема проезда</a>
          <a href="#">Карта сайта</a>
          <a href="#">Посещаемость сайта</a>
        </nav>
      </footer>
    

      </html>