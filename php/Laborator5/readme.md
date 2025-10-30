# Лабораторная работа №4. Обработка и валидация форм

## Цель
Освоить основные принципы работы с HTML-формами в PHP, включая отправку данных на сервер и их обработку, включая валидацию данных.

## Инструкция по запуску
- Убедиться, что на устройстве установлен PHP: `php -v`
- Убедиться в работоспосбности `phpmyadmin`
- В браузере перейти по ссылке: `http://localhost/list-of-spells/public/`

## Краткая документация к проекту
**Тема:** Список заклинаний абракадабра
Создаю следующую файловую структуру проекта:
```
list-of-spells/
├── public/
│   └── index.php
├── src/
│   ├── handlers/
│   │   ├── spell/
│   │   │   ├── create.php
│   │   │   ├── edit.php
│   │   │   └── delete.php
│   ├── db.php
│   ├── helpers.php
├── config/
│   └── db.php
└── templates/
    ├── layout.php
    ├── index.php
    └── spell/
        ├── create.php
        ├── edit.php
        └── show.php                
```

### public/index.php
Входная точка приложения, обрабатывает маршруты и запускает нужные обработчики
```php
if (!isset($pdo)) {
    echo "Подключение к базе данных не установлено!";
    exit;
}

$action = $_GET['action'] ?? 'home';
$data = $_POST ?? [];

switch ($action) {
    case 'create':
        require_once __DIR__ . '/../src/handlers/spell/create.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = createSpell($pdo, $data);

            if (!empty($result['success'])) {
                header('Location: /list-of-spells/public/');
                exit;
            }

            $errors = $result['errors'] ?? [];
            $data = $result['data'] ?? [];
        } else {
            $data = [];
            $errors = [];
        }
        include __DIR__ . '/../templates/spell/create.php';
        break;

    case 'edit':
        require_once __DIR__ . '/../src/handlers/spell/edit.php';

        $spell = getSpellById($pdo, $id);

        include __DIR__ . '/../templates/spell/edit.php';
        break;

    case 'show':
        require_once __DIR__ . '/../src/helpers.php'; 
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "ID заклинания не указано.";
            exit;
        }

        $spell = getSpellById($pdo, $id);

        if (!$spell) {
            http_response_code(404);
            echo "Заклинание не найдено.";
            exit;
        }

        include __DIR__ . '/../templates/spell/show.php';
        break;

    case 'delete':
        require_once __DIR__ . '/../src/handlers/spell/delete.php';
        $id = $_GET['id'] ?? null;

        if ($id) {
            deleteSpell($pdo, $id);
        }

        header('Location: /list-of-spells/public/');
        exit;

    case 'home':
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $stmt = $pdo->prepare("SELECT * FROM spells ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $spells = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $pdo->query("SELECT COUNT(*) FROM spells");
        $totalSpells = (int) $countStmt->fetchColumn();
        $totalPages = ceil($totalSpells / $limit);

        include __DIR__ . '/../templates/index.php';
        break;

    default:
        http_response_code(404);
        $title = 'Страница не найдена';
        ob_start();
        echo "<h2>404 — Страница не найдена</h2>";
        $content = ob_get_clean();
        include __DIR__ . '/../templates/layout.php';
        break;
}
```

### src/db.php
Подключение к базе данных
```php
$config = require __DIR__ . '/../config/db.php';

try {
    $pdo = new PDO($config['dsn'], $config['user'], $config['password']);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('DB connection failed: ' . $e->getMessage());
}

return $pdo;
```

### src/helpers.php
Вспомогательные функции
```php
function validateSpell($title, $category, $description, $tags, $steps) {
    $errors = [];
    if (trim($title) === '') {
        $errors['title'] = "Введите название.";
    }

    if (trim($category) === '') {
        $errors['category'] = "Выберите категорию.";
    }

    if (trim($description) === '') {
        $errors['description'] = "Введите описание.";
    }

    if (!is_array($tags) || count($tags) === 0) {
        $errors['tags'] = 'Выберите тэг.';
    }

    if (!is_array($steps) || count(array_filter($steps, fn($st) => trim($st) !== '')) === 0) {
        $errors['steps'] = 'Добавьте хотя бы один шаг выполнения заклинания.';
    }

    return $errors;
}


function getSpellById(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT * FROM spells WHERE id = ?");
    $stmt->execute([$id]);
    $spell = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($spell) {
        if (isset($spell['tags']) && is_string($spell['tags'])) {
            $spell['tags'] = array_filter(array_map('trim', explode(',', $spell['tags'])));
        }

        if (isset($spell['steps']) && is_string($spell['steps'])) {
            $spell['steps'] = json_decode($spell['steps'], true);
        }

        return $spell;
    }

    return null;
}

```

### src/handlers/spell/create.php
Логика добавления заклинания
```php
function createSpell(PDO $pdo, array $postData): array {
    $title = trim($postData['title'] ?? '');
    $category = trim($postData['category'] ?? '');
    $description = trim($postData['description'] ?? '');
    $tagsInput = $postData['tags'] ?? '';
    $stepsInput = $postData['steps'] ?? [];

    $tags = $postData['tags'] ?? [];
    $tags = array_filter(array_map('trim', $tags));
    $steps = array_values(array_filter(array_map('trim', $stepsInput), fn($s) => $s !== ''));

    $errors = validateSpell($title, $category, $description, $tags, $steps);

    if (!empty($errors)) {
        return [
            'errors' => $errors,
            'data' => [
                'title' => $title,
                'category' => $category,
                'description' => $description,
                'tags' => $tags,
                'steps' => $steps,
            ]
        ];
    }

    $stmt = $pdo->prepare('
    INSERT INTO spells (title, category, description, tags, steps, created_at)
    VALUES (?, ?, ?, ?, ?, ?)
    ');

    $stmt->execute([
        $title,
        $category,
        $description,
        json_encode($tags, JSON_UNESCAPED_UNICODE),
        json_encode($steps, JSON_UNESCAPED_UNICODE),
        date('Y-m-d H:i:s')
    ]);

    return ['success' => true];
}
```

### src/handlers/spell/edit.php
Логика редактирования заклинания
```php
function updateSpell(PDO $pdo, $id, $data) {
    // Извлечение данных из массива
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';
    $tags = $data['tags'] ?? []; 
    $steps = $data['steps'] ?? [];

    $errors = validateSpell($title, $category, $description, $tags, $steps);

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE spells SET title = ?, category = ?, description = ?, tags = ?, steps = ? WHERE id = ?");
        $stmt->execute([
            $title,
            $category,
            $description,
            implode(',', $tags),  
            json_encode($steps), 
            $id
        ]);
        return ['success' => true];
    }

    return ['errors' => $errors];
}

$pdo = getPdoConnection();
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID заклинания не указано.";
    exit;
}

$spell = getSpellById($pdo, $id);

if (!$spell) {
    echo "Заклинание не найдено.";
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tagsArray = $_POST['tags'] ?? [];  
    $steps = array_filter($_POST['steps'] ?? [], fn($step) => trim($step) !== '');

    $data = [
        'title' => $_POST['title'] ?? '',
        'category' => $_POST['category'] ?? '',
        'description' => $_POST['description'] ?? '',
        'tags' => $tagsArray, 
        'steps' => $steps,    
    ];

    $result = updateSpell($pdo, $id, $data);

    if (!empty($result['success'])) {
        header("Location: /list-of-spells/public/?action=show&id=$id");
        exit;
    }

    $errors = $result['errors'] ?? [];
    $spell = array_merge($spell, $data);
}
```

### src/handlers/spell/delete.php
Логика удаления заклинания
```php
function deleteSpell(PDO $pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM spells WHERE id = ?");
    $stmt->execute([$id]);
}
```

### config/db.php
Параметры подключения к базе данных
```php
$host = 'localhost';
$dbname = 'list_of_spells';
$user = 'root';
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}

function getPdoConnection() {
    global $pdo;
    return $pdo;
}
```

### templates/layout.php
Общий шаблон сайта
```html
<body>
    <header>
        <a href="index.php"><button class="home-button">На главную</button></a>
        <h1>Книга заклинаний</h1>
    </header>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer>
        <p>&copy; Книженция заклинаний</p>
    </footer>
</body>
```

### templates/index.php
Шаблон главной страницы со списком заклинаний
```php
<?php if (empty($spells)): ?>
    <p>Заклинания пока нет.</p>
<?php else: ?>
    <ul>
        <?php foreach ($spells as $spell): ?>
            <li>
                <a href="/list-of-spells/public/?action=show&id=<?= $spell['id'] ?>">
                    <?= htmlspecialchars($spell['title']) ?>
                </a><br>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($totalPages) && $totalPages > 1): ?>
    <nav class="pagination">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?page=<?= $p ?>" <?= $p == $page ? 'style="font-weight:bold;"' : '' ?>>
                <?= $p ?>
            </a>
        <?php endfor; ?>
    </nav>
<?php endif; ?>
```

### templates/spell/create.php
Форма создания заклинания
```php
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
        $tagStmt = $pdo->query("SELECT id, name FROM tags");
        $allTags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);

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
```

### templates/spell/edit.php
Форма редактирования заклинания
```php
<form action="/list-of-spells/public/?action=edit&id=<?= $spell['id'] ?>" method="post">
    <label for="title">Название:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($spell['title'] ?? '') ?>">
    <?php if (!empty($errors['title'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['title']) ?></p>
    <?php endif; ?>
    <br>

    <label for="category">Категория:</label>
    <select name="category" id="category">
        <option value="">Выберите категорию</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($spell['category'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['category'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['category']) ?></p>
    <?php endif; ?>
    <br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description"><?= htmlspecialchars($spell['description'] ?? '') ?></textarea>
    <?php if (!empty($errors['description'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['description']) ?></p>
    <?php endif; ?>
    <br>

    <div>
        <label for="tags">Теги:</label>
        <select name="tags[]" id="tags" multiple>
            $tagStmt = $pdo->query("SELECT id, name FROM tags");
            $allTags = $tagStmt->fetchAll(PDO::FETCH_ASSOC);
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
        $stepData = $spell['steps'] ?? [''];
        foreach ($stepData as $stepText):
        ?>
            <textarea name="steps[]" placeholder="Введите шаг выполнения заклинания"><?= htmlspecialchars($stepText) ?></textarea><br>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($errors['steps'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['steps']) ?></p>
    <?php endif; ?>

    <button type="button" onclick="addStep()">Добавить шаг</button><br><br>

    <button type="submit">Сохранить изменения</button>
</form>
```

### templates/spell/show.php
Отображение отдельного заклинания
```php
<h2><?= htmlspecialchars($spell['title']) ?></h2>

<p><strong>Категория:</strong> <br><?= htmlspecialchars($category) ?></p>
<p><strong>Описание:</strong> <br><?= nl2br(htmlspecialchars($spell['description'])) ?></p>
<p><strong>Теги:</strong> <br> 
    <?= !empty($selectedTagNames) ? implode(', ', $selectedTagNames) : 'Нет тегов' ?>
</p>
<p><strong>Дата создания:</strong> <br><?= htmlspecialchars($spell['created_at']) ?></p>
<p><strong>Шаги:</strong></p>
<div style="text-align: center;">
    <ol style="display: inline-block; text-align: left;">
        <?php foreach ($spell['steps'] as $step): ?>
            <li><?= htmlspecialchars($step) ?></li>
        <?php endforeach; ?>
    </ol>
</div>
```


## Примеры использования
Форма добавления нового заклинания:  
![Снимок экрана 2025-05-03 141359](https://github.com/user-attachments/assets/de4ee9b2-eef6-4ec7-8dbd-2bcf603dd5fc)

Список всех имеющихся заклинаний:  
![Снимок экрана 2025-05-03 142541](https://github.com/user-attachments/assets/878a7652-1b5f-438f-a8ca-217901a702b6)

Подробная информация заклинания:  
![Снимок экрана 2025-05-03 143839](https://github.com/user-attachments/assets/8d1defe2-b71b-4b53-bbe9-a96ec01eb4dc)

Редактирование заклинания:  
![Снимок экрана 2025-05-03 144059](https://github.com/user-attachments/assets/fa1f9762-98c4-4fd1-b8ed-b1be493a99da)

Результат:  
![Снимок экрана 2025-05-03 144112](https://github.com/user-attachments/assets/d9df9e10-c65d-4531-9ecd-438411d743a2)

Удаление заклинания:  
![Снимок экрана 2025-05-03 144127](https://github.com/user-attachments/assets/9b1e6d8b-c0a8-4a8f-a7b1-6df6e5312d40)

Страшные последствия:  
![Снимок экрана 2025-05-03 144138](https://github.com/user-attachments/assets/888ff335-4b3f-485c-8200-4bd151eadd31)


## Контрольные вопросы
**1. Какие преимущества даёт использование единой точки входа в веб-приложении?**  
*Единая точка входа* — это файл (обычно index.php), через который проходят все запросы к приложению.  
Преимущества:
- Централизованное управление: можно удобно настраивать маршруты, фильтры, авторизацию и обработку ошибок в одном месте.
- Повышенная безопасность: можно установить глобальные меры защиты (например, проверку токенов или сессий).
- Упрощённая маршрутизация: легко перенаправлять запросы к нужным контроллерам.
- Удобство при разработке и сопровождении: легче масштабировать и изменять поведение приложения.

**2. Какие преимущества даёт использование шаблонов?**  
*Шаблоны* - позволяют разделить логику и представление. Преимущества:
- Разделение кода отделяет логику от отображения (MVC-подход).
- Повторное использование одни и те же шаблоны можно использовать для разных данных.
- Упрощение редактирования дизайнер может править шаблон без знания логики PHP.
- Чистота кода легче поддерживать и читать код.

**3. Какие преимущества даёт хранение данных в базе по сравнению с хранением в файлах?**  
*База данных* (например, MySQL, PostgreSQL) по сравнению с файлами (например, .txt, .json и др.) предоставляет следующие преимущества:
- Структурированность данных данные хранятся в таблицах с типами, связями и ограничениями.
- Поиск и фильтрация базы позволяют быстро находить нужные записи с помощью SQL.
- Безопасность можно задавать права доступа и шифровать данные.
- Масштабируемость базы данных справляются с большим количеством записей эффективнее.
- Интеграция легко интегрируются с веб-фреймворками и инструментами отчётности.
- Транзакции поддержка целостности данных (например, rollback при ошибке).

**4. Что такое SQL-инъекция? Придумайте пример SQL-инъекции и объясните, как её предотвратить.** 
*SQL-инъекция* — это уязвимость, при которой злоумышленник вставляет вредоносный SQL-код в пользовательский ввод, чтобы изменить поведение запроса к базе данных.  
Пример:
У нас есть такой код на PHP:
```php
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```  

Если пользователь введёт:
```sql
username: admin
password: ' OR '1'='1
```

Запрос станет:
```sql
SELECT * FROM users WHERE username = 'admin' AND password = '' OR '1'='1'
```  

Результат: условие всегда истинно, злоумышленник получит доступ.  

Существуют следующие способы защиты от SQL-инъекций:
- Подготовленные выражения (prepared statements):
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);
```
- Использование ORM (например, Eloquent, Doctrine).
- Фильтрация и валидация ввода.
- Ограничение прав пользователя БД (не использовать root).
