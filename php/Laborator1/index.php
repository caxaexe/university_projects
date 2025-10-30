<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1</title>
</head>
<body>
    <div>
        <h2>Первое задание</h2>
        <?php echo "Привет, мир!"; ?>
    </div>
    <div>
        <h2>Второе задание</h2>
        <?php 
        echo "Hello, World with echo! "; 
        print "Hello, World with print!"; 
        ?>
    </div>
    <div>
        <h2>Третье задание</h2>
        <?php
            $days = 288;
            $message = "Все возвращаются на работу!";

            echo "Прошло " . $days . " дней. ". $message;
            echo "<br>Прошло {$days} дней. {$message}";
        ?>
    </div>
</body>
</html>
