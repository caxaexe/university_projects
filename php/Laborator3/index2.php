<?php
$dir = 'image/';
$files = scandir($dir);

if ($files === false) {
   return;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nachos nachos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        header, footer {
            background-color: rgb(200, 161, 53);
            color: white;
            padding: 10px 0;
        }
        nav {
            background-color: rgb(171, 131, 52);
            overflow: hidden;
        }
        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
        }
        nav a:hover {
            background-color:rgb(255, 221, 129);
        }
        .nachos {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            padding: 10px;
            justify-content: center;
        }
        .nachos img {
            width: 270px;
            height: 270px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 3px solid #c8a135;
            transition: transform 0.3s ease;
        }
        .nachos img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
</head>
<body>

<header>
    <h1>Царство начос</h1>
</header>

<nav>
    <a href="#">Главная начоса на свете</a>
    <a href="#">Лучшие начос на свете</a>
    <a href="#">О лучших начос на свете</a>
</nav>

<div class="nachos">
    <?php 
        $dir = 'image/';
        $files = scandir($dir);

        if ($files === false) {
        return;
        }
        for ($i = 0; $i < count($files); $i++) {
        if (($files[$i] != ".") && ($files[$i] != "..")) {
            $path = $dir . $files[$i]; ?>                  
            <img border='1' src=<?php echo $path?>>    
        <?php
            }
        }
    ?>
</div>

<footer>
    <p>©Начосини 2025</p>
</footer>

</body>
</html>


