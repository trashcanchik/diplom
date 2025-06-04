// script2.js

document.addEventListener('DOMContentLoaded', function() {
    //
    // === 1) Модалка "Мои заявления" (Apps Modal) ===
    //
  
    // 1.1. Получаем элементы модалки "Мои заявления"
    var appsOpenBtn  = document.getElementById('appsOpenBtn');      // кнопка "Мои заявления" на странице
    var appsOverlay  = document.getElementById('appsModalOverlay'); // затемнённый фон (оверлей)
    var appsCloseBtn = document.getElementById('appsCloseBtn');     // крестик для закрытия
    var appsContent  = document.getElementById('appsModalContent'); // контейнер, куда будем подгружать таблицу
  
    // 1.2. Функция показа модалки "Мои заявления"
    function showAppsModal() {
      appsOverlay.style.display = 'flex';
      document.body.style.overflow = 'hidden'; 
      // Пока таблица не подгрузилась, показываем индикатор загрузки
      appsContent.innerHTML = '<p class="apps-modal-loading">Загрузка…</p>';
  
      // Делаем AJAX-запрос (fetch) к my_applications.php?ajax=1
      fetch('my_applications.php?ajax=1')
        .then(function(response) {
          if (!response.ok) throw new Error('Ошибка ' + response.status);
          return response.text();
        })
        .then(function(html) {
          // 1.2.1. Вставляем полученный HTML (таблица + кнопка) в контейнер
          appsContent.innerHTML = html;
  
          // 1.2.2. Сразу после вставки разметки — навешиваем слушатель на кнопку "Подать новое заявление"
          // Кнопка должна иметь id="openFormFromAppsBtn" в HTML, который вернул my_applications.php
          var openFormFromAppsBtn = document.getElementById('openFormFromAppsBtn');
          if (openFormFromAppsBtn) {
            openFormFromAppsBtn.addEventListener('click', function(e) {
              e.preventDefault();
              // Скрываем текущую модалку "Мои заявления"
              appsOverlay.style.display = 'none';
              document.body.style.overflow = '';
  
              // Затем показываем модалку с формой
              showFormModal();
            });
          }
        })
        .catch(function(err) {
          appsContent.innerHTML = '<p style="color:red; text-align:center;">'
                                + 'Не удалось загрузить заявки:<br>' 
                                + err.message + '</p>';
        });
    }
  
    // 1.3. Функция скрытия модалки "Мои заявления"
    function hideAppsModal() {
      appsOverlay.style.display = 'none';
      document.body.style.overflow = '';
      appsContent.innerHTML = '';
    }
  
    // 1.4. Навешиваем обработчики на кнопки открытия/закрытия
    if (appsOpenBtn) {
      appsOpenBtn.addEventListener('click', function(e) {
        e.preventDefault();
        showAppsModal();
      });
    }
    if (appsCloseBtn) {
      appsCloseBtn.addEventListener('click', function(e) {
        e.preventDefault();
        hideAppsModal();
      });
    }
    if (appsOverlay) {
      appsOverlay.addEventListener('click', function(e) {
        // Если кликнули по самому оверлею (вне содержимого) — закрываем
        if (e.target === appsOverlay) {
          hideAppsModal();
        }
      });
    }
  
  
    //
    // === 2) Модалка "Подача заявления" (Form Modal) ===
    //
  
    // 2.1. Получаем элементы модалки формы
    var openFormBtn  = document.getElementById('openFormModalBtn'); // главная кнопка "Подать заявление" на странице
    var formOverlay  = document.getElementById('formModalOverlay'); // затемнённый фон (оверлей) формы
    var closeFormBtn = document.getElementById('closeFormModal');   // крестик для закрытия формы
  
    // 2.2. Функции показа/скрытия модалки формы
    function showFormModal() {
      if (!formOverlay) return;
      formOverlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function hideFormModal() {
      if (!formOverlay) return;
      formOverlay.style.display = 'none';
      document.body.style.overflow = '';
    }
  
    // 2.3. Навешиваем обработчики открытия/закрытия формы
    if (openFormBtn) {
      openFormBtn.addEventListener('click', function(e) {
        e.preventDefault();
        // Если "Мои заявления" открыты — сначала закрываем их
        if (appsOverlay && appsOverlay.style.display === 'flex') {
          hideAppsModal();
        }
        showFormModal();
      });
    }
    if (closeFormBtn) {
      closeFormBtn.addEventListener('click', function(e) {
        e.preventDefault();
        hideFormModal();
      });
    }
    if (formOverlay) {
      formOverlay.addEventListener('click', function(e) {
        // Если кликнули по самому оверлею (вне содержимого) формы — закрываем
        if (e.target === formOverlay) {
          hideFormModal();
        }
      });
    }
  
  
    //
    // === 3) Динамика "ОГЭ/ЕГЭ" в форме ===
    //
  
    var selectCert   = document.getElementById('certificate_type_form');
    var blockOgeForm = document.getElementById('oge_block_form');
    var blockEgeForm = document.getElementById('ege_block_form');
  
    function onCertTypeChangeForm() {
      if (!selectCert) return;
      var cert = selectCert.value;
      if (cert === '9') {
        blockOgeForm.style.display = 'block';
        blockEgeForm.style.display = 'none';
      } else if (cert === '11') {
        blockOgeForm.style.display = 'none';
        blockEgeForm.style.display = 'block';
      } else {
        blockOgeForm.style.display = 'none';
        blockEgeForm.style.display = 'none';
      }
    }
  
    if (selectCert) {
      selectCert.addEventListener('change', onCertTypeChangeForm);
      // При загрузке страницы сразу скрываем оба блока (или показываем нужный)
      onCertTypeChangeForm();
    }
  
  }); // конец DOMContentLoaded
  
  
  