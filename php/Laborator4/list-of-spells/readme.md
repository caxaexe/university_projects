# Лабораторная работа №4. Обработка и валидация форм

## Цель
Освоить основные принципы работы с HTML-формами в PHP, включая отправку данных на сервер и их обработку, включая валидацию данных.

## Инструкция по запуску
- Убедиться, что на устройстве установлен PHP: `php -v`
- При помощи команды `php -S localhost:8080` убедиться в идеальности проекта

## Краткая документация к проекту
**Тема:** Список заклинаний абракадабра  
Создаю следующую файловую структуру проекта:
```
list-of-spells/
├── public/                        
│   ├── index.php                  
│   └── spells/                    
│       ├── create.php             
│       └── index.php            
├── src/                            
│   ├── handlers/
│       └── handle_form.php      
│   └── helpers.php      
└── storage/                        
    └── spells.txt                        
```

--- 

### public/index.php
Данная веб-страница содержит вывод последних заклинаний
```php
<?php if(empty($latestSpells)): ?>
        <p>Пока нет добавленных заклинаний.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($latestSpells as $spells): ?>
                <li>
                    <div class="spell-item">
                        <h2><?php echo htmlspecialchars($spells['title']) ?></h2>
                        <p>Категория: <?php echo htmlspecialchars($spells['category']) ?> </p>
                        <p>Описание: <?php echo htmlspecialchars($spells['description']) ?> </p>
                        <p>Тэги: <?php echo implode(', ', array_map('htmlspecialchars', $spells['tags'])) ?> </p>
                        <p>Шаги выполнения заклинания:</p>
                            <?php foreach($spells['steps'] as $step): ?>
                                <p><?php echo htmlspecialchars($step) ?> </p>
                            <?php endforeach; ?>
                    </div>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
```

---

### public/spells/index.php
Данная веб-страница содержит вывод всех имеющихся заклинаний
```php
<?php if (empty($spells)): ?>
        <p style="text-align:center;">Пока нет добавленных заклинаний.</p>
    <?php else: ?>
        <?php foreach ($spells as $spell): ?>
            <div class="spell-item">
                <h2><?php echo htmlspecialchars($spell['title']) ?></h2>
                <h2><span class="spell-name">Категория:</span> <?php echo htmlspecialchars($spell['category']) ?></h2>
                <h2><span class="spell-name">Описание:</span> <?php echo htmlspecialchars($spell['description']) ?></h2>
                <h2><span class="spell-name">Тэги:</span> <?php echo implode(', ', array_map('htmlspecialchars', $spell['tags'])) ?></h2>
            </div>
        <?php endforeach; ?>

        <?php if($totalSpells > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalSpells; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <span class="current"><?php echo $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>"><?php echo $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
```

---

### public/spells/create.php
Данная веб-страница содержит форму добавления заклинания (название, категория, описание, тэги и кнопка для добавления шага выполнения заклинания 0_0) 
```php
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
            <label for="descriprion">Описание заклинания:</label>
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
```

---

### src/handlers/handle_form.php
Данный php файл содержит обработку формы
```php
function handlerForm($data) {
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';
    $tags = $data['tags'] ?? '';
    $steps = $data['steps'] ?? '';

    $errors = validateSpell($title, $category, $description, $tags, $steps);

    if(empty($errors)) {
        saveSpell($title, $category, $description, $tags, $steps);
        return ['success' => true];
    }

    return['errors' => $errors];
}
```

### src/helpers.php
Данный php файл содержит вспомогательные функции для обработки данных
```php
function loadSpell() {
    $file = __DIR__ . '/../storage/spells.txt';
    if(!file_exists($file)) {
        return [];
    } 

    $spells = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map(fn($line) => json_decode($line, true), $spells);
}

function saveSpell($title, $category, $description, $tags, $steps) {
    $file = __DIR__ . '/../storage/spells.txt';

    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    $tags = array_map(fn($tag) => htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'), $tags);
    $steps = array_map(fn($step) => htmlspecialchars($step, ENT_QUOTES, 'UTF-8'), $steps);

    $spell = json_encode( [
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'tags' => $tags,
        'steps' => $steps,
        'created' => date('Y-m-d H:i:s')
    ]);
    // file_append добавляет данные в конец файла, lock_ex блокирует файл для других процессов пока этот не завершится
     file_put_contents($file, $spell . "\n", FILE_APPEND | LOCK_EX);
}

function validateSpell($title, $category, $description, $tags, $steps) {
    $errors = [];

    if(trim($title) === '') {
        $errors['title'] = "Введите название.";
    }

    if(trim($category) === '') {
        $errors['category'] = "Выберите категорию.";
    }

    if(trim($description) === '') {
        $errors['description'] = "Введите описание.";
    }

    if(!is_array($tags) || count($tags) === 0) {
        $errors['tags'] = 'Выберите тэг.';
    }

    if(!is_array($steps) || count(array_filter($steps, fn($st) => trim($st) !== '')) === 0) {
        $errors['steps'] = 'Добавьте хотя бы один шаг выполнения заклинания.';
    }

    return $errors;
}

function getPaginatedSpells($page, $perPage = 5) {
    $allSpells = loadSpell();
    $totalSpells = count($allSpells);
    $totalPages = max(1, ceil($totalSpells / $perPage));

    $page = max(1, min($page, $totalPages));

    $offSet = ($page - 1) * $perPage;
    $spells = array_slice($allSpells, $offSet, $perPage);

    return [
        'spells' => $spells,
        'total_pages' => $totalPages,
        'current_page' => $page
    ];
}
```

### storage/spells.txt
Данный текстовый файл содержит все добавленные заклинания
```
{"title":"lalalal","category":"\u043d\u0435\u0432\u0435\u0440\u0431\u0430\u043b\u044c\u043d\u043e\u0435","description":"lalalalallalala","tags":["\u043e\u0431\u043e\u0440\u043e\u043d\u0438\u0442\u0435\u043b\u044c\u043d\u044b\u0435","\u0442\u0440\u0430\u043d\u0441\u0444\u0438\u0433\u0443\u0440\u0430\u0446\u0438\u043e\u043d\u043d\u044b\u0435","\u043f\u0440\u0438\u0437\u044b\u0432\u043d\u044b\u0435","\u0440\u0430\u0437\u0440\u0443\u0448\u0438\u0442\u0435\u043b\u044c\u043d\u044b\u0435"],"steps":["lalala","lalalalla","i tut llalalal"],"created":"2025-04-17 12:52:59"}
{"title":"mamamam","category":"\u043f\u0440\u043e\u0434\u0432\u0438\u043d\u0443\u0442\u043e\u0435","description":"mamamaaammamama","tags":["\u0430\u0442\u0430\u043a\u0443\u044e\u0449\u0438\u0435"],"steps":["mamama","mamamam","mamamama","mamamamamamamama"],"created":"2025-04-17 12:53:31"}
```

## Примеры использования
Форма добавления нового заклинания:
![Снимок экрана 2025-04-17 155654](https://github.com/user-attachments/assets/8f17c611-c968-4ae3-af81-0665559f6a33)

Список последних заклинаний:
![Снимок экрана 2025-04-17 155706](https://github.com/user-attachments/assets/363e449f-a623-4804-9b10-737c0f948cdf)

Список всех заклинаний:
![Снимок экрана 2025-04-17 155719](https://github.com/user-attachments/assets/12ddc8f0-5f5a-4c62-8fa3-5b15904abedd)

## Контрольные вопросы
**1. Какие методы HTTP применяются для отправки данных формы?**  
При отправке данных формы в HTTP используются следующие методы:  
- `POST` – отправляет данные в теле запроса. Используется для передачи больших объемов информации, включая файлы.
- `GET` – передает данные через URL-параметры (в строке запроса). Менее безопасен, так как данные видны в URL.
- `PUT` – обновляет существующий ресурс, передавая данные в теле запроса.
- `PATCH` – частично обновляет ресурс.
- `DELETE` – используется для удаления данных. 

**2. Что такое валидация данных, и чем она отличается от фильтрации?**  
*Валидация данных* – проверка на соответствие заданным критериям (например, длина строки, формат email, обязательность заполнения). Если данные не соответствуют правилам, они отклоняются. 

*Фильтрация данных* – обработка входных данных для удаления или преобразования нежелательных символов, уменьшения риска XSS и SQL-инъекций.

**3. Какие функции PHP используются для фильтрации данных?**  
В PHP есть встроенные функции для фильтрации:
- `htmlspecialchars()` – преобразует специальные символы (<, >, &, " и ') в HTML-коды.
- `strip_tags()` – удаляет HTML-теги.
- `trim()` – убирает пробелы в начале и конце строки.
- `filter_var($email, FILTER_VALIDATE_EMAIL)` – проверяет корректность email.
- `filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING)` – фильтрует данные, полученные через $_POST.
