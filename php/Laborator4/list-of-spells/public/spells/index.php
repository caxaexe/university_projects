<?php

/**
 * Страница отображения всех заклинаний с постраничной навигацией.
 *
 * Получает текущую страницу из параметра GET, затем использует функцию `getPaginatedSpells()`
 * для получения списка заклинаний и информации о пагинации.
 * Отображает заклинания в виде карточек, а также блок пагинации, если заклинаний больше одной страницы.
 *
 * @package SpellsApp
 */
require_once __DIR__ . '/../../src/helpers.php';

/** @var int $page Текущий номер страницы (по умолчанию 1) */
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

/**
 * Получение пагинированного списка заклинаний.
 *
 * Возвращаемый массив содержит:
 *  - `spells` (array): Массив заклинаний на текущей странице.
 *  - `total_pages` (int): Общее количество страниц.
 *  - `current_page` (int): Текущая страница.
 *
 * @see getPaginatedSpells()
 *
 * @var array $pagination Результат пагинации
 */
$pagination = getPaginatedSpells($page);

/** @var array $spells Список заклинаний на текущей странице */
$spells = $pagination['spells'];

/** @var int $totalSpells Общее количество страниц */
$totalSpells = $pagination['total_pages'];

/** @var int $currentPage Текущий номер страницы */
$currentPage = $pagination['current_page'];

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все заклинания</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .spell-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin: 0 auto 15px auto;
            max-width: 600px;
            border-radius: 6px;
        }

        .spell-item h2 {
            font-size: 16px;
            margin: 6px 0;
        }

        .spell-label {
            font-weight: bold;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 3px;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: #333;
        }

        .pagination span.current {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Все заклинания</h1>

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

    <a href="/public/spells/create.php">Добавить новое заклинание</a>
    <a href="/public/index.php">Глянуть последние заклинания</a>
</body>
</html>
