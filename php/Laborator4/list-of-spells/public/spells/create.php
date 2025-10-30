<?php

/**
 * Основной файл обработки формы добавления заклинания.
 *
 * Загружает обработчик `handlerForm()` и выполняет проверку данных, отправленных методом POST.
 * В случае успешной отправки перенаправляет пользователя на главную страницу.
 * В случае ошибок сохраняет их в переменную $errors для отображения пользователю.
 *
 * @package SpellsApp
 */
require_once __DIR__ . '/../../src/handlers/handle_form.php';

$errors = [];

/**
 * Обработка запроса формы.
 *
 * Проверка, был ли запрос методом POST. В этом случае вызывается handlerForm(),
 * который возвращает либо сообщение об успехе, либо массив ошибок.
 * При успехе происходит редирект на index.php. Ошибки сохраняются для отображения в форме.
 */
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handlerForm($_POST);

    if(isset($result['success']) && $result['success'] === true) {
        header('Location: /public/index.php');
        exit;
    } else {
        $errors = $result['errors'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление заклинания</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 2rem;
    }

    h1 {
        text-align: center;
        color: #222;
        margin-bottom: 1.5rem;
    }

    form {
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    label {
        display: block;
        margin-bottom: 1rem;
        color: #333;
        font-weight: bold;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    .error {
        color: #c00;
        font-size: 0.9rem;
        margin-top: -0.5rem;
        margin-bottom: 0.8rem;
    }

    .step input[type="text"] {
        margin-top: 0.3rem;
    }

    #addStep {
        margin-top: 0.5rem;
        background-color: #007bff;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.95rem;
    }

    #addStep:hover {
        background-color: #0056b3;
    }

    button[type="submit"] {
        margin-top: 1rem;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #218838;
    }
</style>
</head>
<body>

    <h1>Добавление нового заклинания</h1>

    <form action="" method="POST">
        <div>
            <label for="title">Название заклинания:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($_POST['title'] ?? '') ?>">
            <?php if(isset($errors['title'])): ?>
                <p class="error"><?php echo $errors['title']?><p>
            <?php endif; ?>
        </div>


        <div>
            <label for="category">Категория заклинания:</label>
            <select name="category" id="category">
                <option value="непростительное" <?php echo isset($_POST['category']) && $_POST['category'] === 'непростительное' ? 'selected' : '' ?>>Непростительное</option>
                <option value="бытовое" <?php echo isset($_POST['category']) && $_POST['category'] === 'бытовое' ? 'selected' : '' ?>>Бытовое</option>
                <option value="невербальное" <?php echo isset($_POST['category']) && $_POST['category'] === 'невербальное' ? 'selected' : '' ?>>Невербальное</option>
                <option value="продвинутое" <?php echo isset($_POST['category']) && $_POST['category'] === 'продвинутое' ? 'selected' : '' ?>>Продвинутое</option>
            </select>
            <?php if(isset($errors['category'])): ?>
                <p class="error"><?php echo $errors['category']; ?></p>
            <?php endif; ?>
        </div>


        <div>
            <label for="description">Описание заклинания:</label>
            <textarea name="description" id="description"><?php echo htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            <?php if(isset($errors['description'])): ?>
                <p class="error"><?php echo $errors['description']; ?></p>
            <?php endif; ?>
        </div>


        <div>
            <label for="tags">Тэги:</label>
            <select name="tags[]" id="tags" multiple>
                <option value="оборонительные">Оборонительные</option>
                <option value="атакующие">Атакующие</option>
                <option value="трансфигурационные">Трансфигурационные</option>
                <option value="целебные">Целебные</option>
                <option value="призывные">Призывные</option>
                <option value="разрушительные">Разрушительные</option>
                <option value="контроль сознания">Контроль сознания</option>
            </select>
            <?php if(isset($errors['tags'])): ?>
                <p class="error"><?php echo $errors['tags'] ?> </p>
            <?php endif; ?>
        </div>
    

        <div>
            <label for="steps">Шаги выполнения заклинания:</label>
            <div id="steps-container">
                <div class="step">
                    <input type="text" name="steps[]" value="">
                </div>          
            </div>
            <button type="button" id="addStep">Добавить шаг</button><br>
        </div>
        <div>
            <button type="submit">Отправить</button>
        </div>
    </form>

    <script>
        document.getElementById('addStep').addEventListener('click', function() {
            let stepsDiv = document.getElementById('steps-container');
            let newStep = document.createElement('div');
            newStep.innerHTML = '<input type="text" name="steps[]" value="">';
            stepsDiv.appendChild(newStep);
        });
    </script>
</body>
</html>
