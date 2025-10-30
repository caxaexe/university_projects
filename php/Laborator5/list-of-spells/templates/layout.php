<?php
/**
 * @var string|null $title Заголовок страницы, отображаемый в <title>
 * @var string $content Основное содержимое страницы, вставляемое в <main>
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Книга заклинаний' ?></title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f4f0;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #5d3a9b;
            color: white;
            padding: 20px 40px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        footer {
            background-color: #2d1e49;
            color: #ddd;
            padding: 15px 40px;
            text-align: center;
            font-size: 0.9rem;
            box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.15);
            margin-top: 40px;
            margin-top: auto;
        }

        main {
            padding: 30px 40px;
            flex-grow: 1; 
        }

        .home-button {
            position: absolute;
            top: 20px;
            left: 40px;
            background-color: #fff;
            color: #5d3a9b;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .home-button:hover {
            background-color: #e0d5f5;
            color: #3a256b;
        }
    </style>
</head>
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
</html>
