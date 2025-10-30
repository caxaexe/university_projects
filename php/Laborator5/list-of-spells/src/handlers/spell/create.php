<?php

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

/**
 * Создает новое заклинание в базе данных.
 *
 * Эта функция принимает данные заклинания из формы, проверяет их на валидность, и если все данные корректны,
 * сохраняет их в базу данных. Если в процессе валидации были ошибки, возвращается массив с ошибками и исходными данными.
 *
 * @param PDO $pdo Объект подключения к базе данных.
 * @param array $postData Данные, полученные через POST-запрос, включая название, категорию, описание, теги и шаги заклинания.
 *
 * @return array Возвращает массив с результатом операции:
 *               - если успех: ['success' => true],
 *               - если ошибки: ['errors' => [...], 'data' => [...]].
 */
function createSpell(PDO $pdo, array $postData): array {
    // Получение данных из массива POST-запроса
    $title = trim($postData['title'] ?? '');
    $category = trim($postData['category'] ?? '');
    $description = trim($postData['description'] ?? '');
    $tagsInput = $postData['tags'] ?? '';
    $stepsInput = $postData['steps'] ?? [];

    // Очистка и фильтрация данных
    $tags = $postData['tags'] ?? [];
    $tags = array_filter(array_map('trim', $tags));
    // Очищаем и фильтруем шаги
    $steps = array_values(array_filter(array_map('trim', $stepsInput), fn($s) => $s !== ''));

    // Валидация данных
    $errors = validateSpell($title, $category, $description, $tags, $steps);

    if (!empty($errors)) {
        // Возвращаем ошибки и данные, если валидация не прошла
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

    // Подготовка SQL-запроса для вставки данных в таблицу "spells"
    $stmt = $pdo->prepare('
    INSERT INTO spells (title, category, description, tags, steps, created_at)
    VALUES (?, ?, ?, ?, ?, ?)
    ');

    // Выполнение SQL-запроса с параметрами
    $stmt->execute([
        $title,
        $category,
        $description,
        json_encode($tags, JSON_UNESCAPED_UNICODE),
        json_encode($steps, JSON_UNESCAPED_UNICODE),
        date('Y-m-d H:i:s')
    ]);

    // Возвращаем успех
    return ['success' => true];
}
