<?php
session_start();
Require_once __DIR__ . '/db.php';



$specialties = [];
$sqlSpecialties = "SELECT id, name FROM specialties ORDER BY name";
if ($res = $mysqli->query($sqlSpecialties)) {
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
    <title>Подача заявления</title>
    <script>
    function onCertificateTypeChange() {
        var certType = document.getElementById('certificate_type').value;
        var ogeBlock = document.getElementById('oge_block');
        var egeBlock = document.getElementById('ege_block');
        if (certType === '9') {
            ogeBlock.style.display = 'block';
            egeBlock.style.display = 'none';
        } else if (certType === '11') {
            ogeBlock.style.display = 'none';
            egeBlock.style.display = 'block';
        } else {
            ogeBlock.style.display = 'none';
            egeBlock.style.display = 'none';
        }
    }
    window.addEventListener('DOMContentLoaded', function() {
        onCertificateTypeChange();
        document.getElementById('certificate_type').addEventListener('change', onCertificateTypeChange);
    });
    </script>
</head>
<body>
    <h2>Форма подачи заявления</h2>
    <form action="submit_application.php" method="post">
        <label>ФИО:<br>
            <input type="text" name="fio" required maxlength="255">
        </label><br><br>

        <label>Серия паспорта:<br>
            <input type="text" name="passport_series" required pattern="\d{4}" maxlength="4" placeholder="4 цифры">
        </label><br><br>

        <label>Номер паспорта:<br>
            <input type="text" name="passport_number" required pattern="\d{6}" maxlength="6" placeholder="6 цифр">
        </label><br><br>

        <label>СНИЛС:<br>
            <input type="text" name="snils" required pattern="\d{3}-\d{3}-\d{3} \d{2}" placeholder="XXX-XXX-XXX XX">
        </label><br><br>

        <label>Тип аттестата:<br>
            <select name="certificate_type" id="certificate_type" required>
                <option value="">-- Выберите --</option>
                <option value="9">9 классов</option>
                <option value="11">11 классов</option>
            </select>
        </label><br><br>

        <label>Средний балл аттестата:<br>
            <input type="number" name="certificate_avg_score" step="0.01" min="2" max="5" required placeholder="Например, 4.50">
        </label><br><br>

        <div id="oge_block" style="display:none;">
            <label>Баллы ОГЭ:<br>
                <input type="number" name="exam_oge_score" min="0">
            </label><br><br>
        </div>

        <div id="ege_block" style="display:none;">
            <label>Баллы ЕГЭ:<br>
                <input type="number" name="exam_ege_score" min="0">
            </label><br><br>
        </div>

        <label>Сколько классов окончили:<br>
            <select name="grades_completed" required>
                <option value="">-- Выберите --</option>
                <option value="9">9</option>
                <option value="11">11</option>
            </select>
        </label><br><br>

        <label for="specialty">Специальность:</label>
<select name="specialty_id" id="specialty" required>
    <option value="">-- Выберите специальность --</option>
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

        <button type="submit">Отправить заявление</button>
    </form>
</body>
</html>
