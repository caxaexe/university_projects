<?php 

/**
 * Загружает заклинания из текстового файла.
 *
 * Читает файл со списком заклинаний в формате JSON (по одному на строку),
 * декодирует их и возвращает в виде массива.
 *
 * @return array Массив заклинаний (каждое — ассоциативный массив), либо пустой массив, если файл не найден.
 */
function loadSpell() {
    $file = __DIR__ . '/../storage/spells.txt';
    if (!file_exists($file)) {
        return [];
    } 

    $spells = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_values(array_filter(array_map(fn($line) => json_decode($line, true), $spells)));
}

/**
 * Сохраняет новое заклинание в текстовый файл.
 *
 * Экранирует входные данные, создает структуру заклинания и добавляет её
 * в конец файла в формате JSON.
 *
 * @param string $title Название заклинания
 * @param string $category Категория заклинания
 * @param string $description Описание заклинания
 * @param array $tags Массив тэгов
 * @param array $steps Массив шагов выполнения
 * @return void
 */
function saveSpell($title, $category, $description, $tags, $steps) {
    $file = __DIR__ . '/../storage/spells.txt';

    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    $tags = array_map(fn($tag) => htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'), $tags);
    $steps = array_map(fn($step) => htmlspecialchars($step, ENT_QUOTES, 'UTF-8'), $steps);

    $spell = json_encode([
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'tags' => $tags,
        'steps' => $steps,
        'created' => date('Y-m-d H:i:s')
    ]);

    file_put_contents($file, $spell . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Валидирует данные заклинания.
 *
 * Проверяет корректность введенных данных: наличие обязательных полей и их формат.
 * Возвращает массив ошибок, если они имеются.
 *
 * @param string $title Название заклинания
 * @param string $category Категория
 * @param string $description Описание
 * @param array $tags Список тэгов
 * @param array $steps Шаги выполнения
 * @return array Массив ошибок, где ключ — имя поля, значение — текст ошибки.
 */
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

/**
 * Возвращает пагинированный список заклинаний.
 *
 * Разбивает массив заклинаний по страницам и возвращает текущую порцию,
 * а также информацию о текущей странице и общем числе страниц.
 *
 * @param int $page Номер текущей страницы
 * @param int $perPage Количество заклинаний на странице (по умолчанию 5)
 * @return array Ассоциативный массив с ключами: 'spells', 'total_pages', 'current_page'
 */
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
