<?php
/**
 * Форма редактирования заклинания.
 * Загружает список категорий и тегов из базы данных, отображает текущие данные заклинания,
 * позволяет изменить заголовок, категорию, описание, теги и шаги.
 */
ob_start();

// Получение списка категорий из базы данных
$categoryStmt = $pdo->query('SELECT id, name FROM categories');
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9ff;
        color: #333;
        margin: 0;
        padding: 2rem;
    }

    h2 {
        text-align: center;
        color: #4a4a88;
    }

    form {
        max-width: 700px;
        margin: 0 auto;
        background-color: #fff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-top: 1rem;
        font-weight: bold;
    }

    input[type="text"],
    select,
    textarea {
        width: 100%;
        padding: 0.6rem;
        margin-top: 0.3rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    input[type="text"]:focus,
    select:focus,
    textarea:focus {
        border-color: #7a7ae0;
        outline: none;
    }

    textarea {
        min-height: 80px;
        resize: vertical;
    }

    p {
        margin: 0.3rem 0 0;
        font-size: 0.9rem;
    }

    p[style*="color:red"] {
        color: #d33 !important;
    }

    #steps textarea {
        margin-top: 0.5rem;
    }

    button[type="button"],
    button[type="submit"] {
        margin-top: 1.5rem;
        padding: 0.6rem 1.2rem;
        background-color: #4a4a88;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s ease-in-out;
    }

    button:hover {
        background-color: #5d5dc2;
    }

    .error-message {
        color: #d33;
        font-size: 0.9rem;
    }
</style>

<h2>Добавить заклинание</h2>

<form action="/list-of-spells/public/?action=create" method="post">
    <label for="title">Название:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($data['title'] ?? '') ?>">
    <?php if (!empty($errors['title'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['title']) ?></p>
    <?php endif; ?>
    <br>

    <label for="category">Категория:</label>
    <select name="category" id="category">
        <option value="">Выберите категорию</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($data['category'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['category'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['category']) ?></p>
    <?php endif; ?>
    <br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
    <?php if (!empty($errors['description'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['description']) ?></p>
    <?php endif; ?>
    <br>

    <div>
    <label for="tags">Теги:</label>
    <select name="tags[]" id="tags" multiple>
        <?php
        // Получаем все теги из базы
        $tagStmt = $pdo->query("SELECT id, name FROM tags");
        $allTags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);

        // Полученные теги, выбранные пользователем
        $selectedTags = $data['tags'] ?? []; // массив ID, например [1, 3]
        ?>

        <?php foreach ($allTags as $tag): ?>
            <option value="<?= $tag['id'] ?>"
                <?= in_array($tag['id'], $selectedTags) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tag['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if (isset($errors['tags'])): ?>
        <p class="error"><?= htmlspecialchars($errors['tags']) ?></p>
    <?php endif; ?>
</div>
    <br>

    <label>Шаги выполнения:</label><br>
    <div id="steps">
        <?php
        $stepData = $data['steps'] ?? [''];
        foreach ($stepData as $stepText):
        ?>
            <textarea name="steps[]" placeholder="Введите шаг выполнения заклинания"><?= htmlspecialchars($stepText) ?></textarea><br>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($errors['steps'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['steps']) ?></p>
    <?php endif; ?>

    <button type="button" onclick="addStep()">Добавить шаг</button><br><br>

    <button type="submit">Сохранить</button>
</form>

<script>
    // Функция для добавления нового шага
    function addStep() {
        const newStep = document.createElement('textarea');
        newStep.name = 'steps[]';
        newStep.placeholder = 'Введите шаг выполнения заклинания';
        document.getElementById('steps').appendChild(newStep);
        document.getElementById('steps').appendChild(document.createElement('br'));
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>