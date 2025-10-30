<?php

/**
 * Главная страница приложения.
 *
 * Отображает два последних заклинания из общего списка.
 * Использует функцию `loadSpell()` для получения всех сохранённых заклинаний,
 * а затем извлекает последние два с помощью `array_slice()`.
 *
 * @package SpellsApp
 */
require_once __DIR__ . '/../src/helpers.php';

/**
 * Получение всех сохранённых заклинаний.
 *
 * @see loadSpell()
 *
 * @var array $spells Массив всех заклинаний, загруженных из файла
 */
$spells = loadSpell();


/**
 * Последние два заклинания.
 *
 * @var array $latestSpells Массив из двух последних заклинаний
 */
$latestSpells = array_slice($spells, -2);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список последних заклинаний</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        padding: 2rem;
        color: #333;
    }

    h1 {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .spell-item {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .spell-item h2 {
        font-size: 1rem;
        margin: 0.3rem 0;
    }
</style>

</head>
<body>
    <h1>Последние заклинания</h1>


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

    <a href="/public/spells/index.php">Глянуть все заклинания</a>
    <a href="/public/spells/create.php">Добавить новое заклинание</a>
</body>
</html>
