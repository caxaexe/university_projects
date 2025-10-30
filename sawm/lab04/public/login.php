

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заходь</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="login-container">
    <h2>Вход</h2>
    <form action="../config/auth.php" method="post">
        <label>Логин:</label>
        <input type="text" name="login" required><br><br>

        <label>Пароль:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Войти">
    </form>
    <div class="nav-buttons">
        <a href="../index.php">На главную</a>
    </div>
</div>
    
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            const login = form.login.value.trim();
            const password = form.password.value;

            const loginRe = /^[A-Za-z0-9._-]{3,50}$/;
            if (!loginRe.test(login)) {
                e.preventDefault();
                alert('Неверный формат логина. Попробуйте еще раз.');
                return;
            }

            if (password.length < 4) {
                e.preventDefault();
                alert('Пароль должен быть не короче 4 символов.');
                return;
            }
        });
    });
</script>
</body>
</html>
