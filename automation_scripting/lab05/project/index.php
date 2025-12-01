<?php

require __DIR__ . '/vendor/autoload.php';
use App\Calculator;

$calc = new Calculator();

$a = isset($_GET['a']) ? (float)$_GET['a'] : 0;
$b = isset($_GET['b']) ? (float)$_GET['b'] : 0;
$op = $_GET['op'] ?? 'add';

$result = null;
$error = null;

try {
    switch ($op) {
        case 'add': $result = $calc->add($a, $b); break;
        case 'sub': $result = $calc->subtract($a, $b); break;
        case 'mul': $result = $calc->multiply($a, $b); break;
        case 'div': $result = $calc->divide($a, $b); break;
        default:    $error = 'Unknown operation';
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}

function active(string $c, string $o): string {
    return $c === $o ? 'btn-active' : '';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Calculator</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="wrapper">

    <h1>Калькулятор</h1>

    <form class="box" method="get">
        <label>
            Первое число:
            <input type="number" step="0.01" name="a" value="<?= htmlspecialchars((string)$a) ?>">
        </label>

        <label>
            Второе число:
            <input type="number" step="0.01" name="b" value="<?= htmlspecialchars((string)$b) ?>">
        </label>

        <div class="buttons">
            <button type="submit" name="op" value="add" class="<?= active($op,'add') ?>">➕</button>
            <button type="submit" name="op" value="sub" class="<?= active($op,'sub') ?>">➖</button>
            <button type="submit" name="op" value="mul" class="<?= active($op,'mul') ?>">✖️</button>
            <button type="submit" name="op" value="div" class="<?= active($op,'div') ?>">➗</button>
        </div>
    </form>

    <div class="result">
        <?php if ($error): ?>
            <span class="error"><?= htmlspecialchars($error) ?></span>
        <?php else: ?>
            <span>Результат:</span>
            <span class="value"><?= htmlspecialchars(number_format((float)$result, 2, '.', '')) ?></span>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
