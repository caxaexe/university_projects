<?php

require_once __DIR__ . '/../../src/helpers.php';

/**
 * Обрабатывает данные формы заклинания.
 *
 * Принимает массив данных из формы, извлекает поля, валидирует их с помощью `validateSpell()` и,
 * если ошибок нет, сохраняет заклинание с помощью `saveSpell()`.
 *
 * @param array $data Ассоциативный массив данных формы. Ожидаемые ключи:
 *                    - 'title' (string): название заклинания
 *                    - 'category' (string): категория
 *                    - 'description' (string): описание
 *                    - 'tags' (array|string): тэги
 *                    - 'steps' (array|string): шаги
 *
 * @return array Возвращает массив:
 *               - ['success' => true], если валидация успешна и данные сохранены;
 *               - ['errors' => array], если есть ошибки валидации.
 */
function handlerForm($data) {
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';
    $description = $data['description'] ?? '';
    $tags = $data['tags'] ?? '';
    $steps = $data['steps'] ?? '';

    $errors = validateSpell($title, $category, $description, $tags, $steps);

    if (empty($errors)) {
        saveSpell($title, $category, $description, $tags, $steps);
        return ['success' => true];
    }

    return ['errors' => $errors];
}
