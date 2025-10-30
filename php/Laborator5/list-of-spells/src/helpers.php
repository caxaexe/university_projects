<?php

/**
 * Валидирует данные заклинания.
 *
 * Эта функция проверяет, что все обязательные поля для создания или редактирования
 * заклинания заполнены корректно. Также выполняется проверка для тэгов и шагов.
 *
 * @param string $title Название заклинания.
 * @param string $category Категория заклинания.
 * @param string $description Описание заклинания.
 * @param array $tags Массив тэгов заклинания.
 * @param array $steps Массив шагов заклинания.
 *
 * @return array Возвращает массив ошибок, если они есть, или пустой массив, если ошибок нет.
 *               Структура массива ошибок: ключ - название поля, значение - сообщение об ошибке.
 */
function validateSpell($title, $category, $description, $tags, $steps) {
    $errors = [];

    // Проверка названия
    if (trim($title) === '') {
        $errors['title'] = "Введите название.";
    }

    // Проверка категории
    if (trim($category) === '') {
        $errors['category'] = "Выберите категорию.";
    }

    // Проверка описания
    if (trim($description) === '') {
        $errors['description'] = "Введите описание.";
    }

    // Проверка тэгов
    if (!is_array($tags) || count($tags) === 0) {
        $errors['tags'] = 'Выберите тэг.';
    }

    // Проверка шагов
    if (!is_array($steps) || count(array_filter($steps, fn($st) => trim($st) !== '')) === 0) {
        $errors['steps'] = 'Добавьте хотя бы один шаг выполнения заклинания.';
    }

    return $errors;
}

/**
 * Получает заклинание по его ID из базы данных.
 *
 * Эта функция выполняет запрос к базе данных для получения информации о заклинании
 * по его уникальному идентификатору. Также обрабатывает тэги и шаги заклинания, 
 * преобразуя их из строк в массивы.
 *
 * @param PDO $pdo Объект подключения к базе данных.
 * @param int $id Идентификатор заклинания.
 *
 * @return array|null Возвращает ассоциативный массив с данными заклинания, если оно найдено,
 *                    или null, если заклинание с таким ID не найдено.
 */
function getSpellById(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT * FROM spells WHERE id = ?");
    $stmt->execute([$id]);
    $spell = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($spell) {
        // Обработка тэгов: строка превращается в массив
        if (isset($spell['tags']) && is_string($spell['tags'])) {
            $spell['tags'] = array_filter(array_map('trim', explode(',', $spell['tags'])));
        }

        // Обработка шагов: строка превращается в массив JSON
        if (isset($spell['steps']) && is_string($spell['steps'])) {
            $spell['steps'] = json_decode($spell['steps'], true);
        }

        return $spell;
    }

    return null;
}
