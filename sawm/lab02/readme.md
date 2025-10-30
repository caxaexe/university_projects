# Лабораторная работа №1. Методы предотвращения SQL-injection в веб приложениях 

## Предварительные условия 
1. Создайте веб-страницу – доступ к которой ограниченный. 
2. Эта страница будет закрытым пространством для администратора веб-контента, доступ к 
которому осуществляется через проверку подлинности (аутентификации). 
3. Создайте еще одну страницу, содержащую форму аутентификации, которая имеет 2 
элемента управления - для логина и пароля. 
4. Создайте базу данных, содержащую таблицу «user», в которой будут храниться данные со 
следующей структурой: id, login, password.

## Требования лабораторной работы
1. Создайте 7 новых записей в таблице «user». 
2. Попробуйте использовать SQL-инъекцию, чтобы войти в панель администрирования 
(закрытое пространство администратора), не использовав правильные данные существующих 
аккаунтов.  
3. Создайте client-side и server-side скрипты, которые будут бороться с возможностью ввода 
кода SQL, в  элементы управления формы, путем проверки данных. 
4. Проверьте безопасность созданного мини-приложения.

## Документация к проекту
Создаю следующую файловую структуру проекта:
```
sawmlab2/
├── index.php - главная страница
├── config/
│   ├── auth.php - обработка формы
│   └── bd.php - логика подключения к базе данных
├── css/
│   └── style.css - стили
└── public/
    ├── admin.php - закрытая админ‑панель
    ├── login.php - HTML-форма входа в аккаунт
    └── logout.php - логика выхода из аккаунта
```

Создаю базу данных `sawm` специально для этой и последующих лабораторных, затем таблицу `users` с полями id, login, password:  
<img width="543" height="212" alt="image" src="https://github.com/user-attachments/assets/9d64ff76-1aae-4ce8-ac78-543eaf6f1be3" />  
  
---
  
В созданной уже базе данных в таблицу `users` добавляю 7 новых записей:  
<img width="526" height="295" alt="image" src="https://github.com/user-attachments/assets/e72f9a44-6d9c-49ea-94f5-af3d21a5e5da" />


Тестирую админского пользователя:  
<img width="489" height="550" alt="image" src="https://github.com/user-attachments/assets/085d83a2-9397-4853-91dd-aeb52e14e92c" />  
Попадаю в админ-панель:  
<img width="620" height="306" alt="image" src="https://github.com/user-attachments/assets/c9d40299-edb6-4fa3-8cb1-93e80be0b06b" />  

Успешно захожу в каждый из созданных аккаунтов.
  
---

Пробую использовать пару SQL-инъекций, чтобы проверить незащищенность сайта. К примеру, использую такую инъекцию, как `1' or '1' = '1`, ввожу в поля логина и пароля:  
<img width="558" height="628" alt="Снимок экрана 2025-10-09 182435" src="https://github.com/user-attachments/assets/672adeeb-10f7-457b-b128-5e139d247cf9" />  
Успешно захожу в админ-панель:  
<img width="621" height="339" alt="Снимок экрана 2025-10-09 184154" src="https://github.com/user-attachments/assets/dedc0b58-2f77-4ae4-adb6-ecfc85657007" />  
  
Также использую `' OR 1=1 --`:  
<img width="612" height="306" alt="image" src="https://github.com/user-attachments/assets/cc6edd6a-7769-438d-b5d5-44856e6c6c1f" />  
И также успешно попадаю в панель администратора:   
<img width="460" height="564" alt="image" src="https://github.com/user-attachments/assets/a4c404cc-f4b2-439d-865a-0ce0838d1dde" />  
  
---
Client-side скрипты выполняются в браузере пользователя. Они управляют интерфейсом, проверяют данные формы, делают анимации, реагируют на клики и взаимодействуют с DOM без обращения к серверу. В файл `public/login.php` добавляю такой JS-скрипт:
```js
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
```
Этот скрипт проверяет правильность логина и минимальную длину пароля перед отправкой формы, чтобы не тратить время на запрос к серверу с некорректными данными.  


Server-side скрипты выполняются на сервере. Они обрабатывают запросы пользователей, работают с базой данных, управляют сессиями, формируют страницы для отправки в браузер. В файл `config/auth.php` добавляю следующий скрипт:
```php
<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: ../public/admin.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/login.php");
    exit();
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';


if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $login)) {
    $error = "Неверный формат логина.";
} else {
    $conn = new mysqli("localhost", "root", "", "sawm");
    if ($conn->connect_error) {
        error_log("DB connect error: " . $conn->connect_error);
        $error = "Ошибка сервера. Попробуйте позже.";
    } else {
        $stmt = $conn->prepare("SELECT id, login, password FROM `users` WHERE login = ?");
        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            $error = "Ошибка сервера. Попробуйте позже.";
        } else {
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if ($password === $user['password'] && mb_strtolower($user['login']) === 'admin') {
                    $_SESSION['admin'] = $user['login'];
                    $stmt->close();
                    $conn->close();
                    header("Location: ../public/admin.php");
                    exit();
                } else {
                    $error = "Неверный логин или пароль.";
                }
            } else {
                $error = "Неверный логин или пароль.";
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>
```
Скрипт запускает сессию, проверяет, что запрос — POST и формат логина корректен, затем ищет пользователя в базе через подготовленный запрос; при совпадении пароля записывает в сессию либо admin, либо user и перенаправляет на соответствующую страницу.  

---

На конечном этапе я проверяю защищенность своего сайта после внедрения скриптов, так что использую те же sql-инъекции:
<img width="579" height="788" alt="Снимок экрана 2025-10-09 181917" src="https://github.com/user-attachments/assets/77b85a7a-fa83-46ca-9ad4-dc191712b016" />  
<img width="582" height="778" alt="image" src="https://github.com/user-attachments/assets/c87740b5-1eea-4697-ba19-3ef34ec596be" />  

 Можно заметить, что ни одна инъекция не проходит, и на данный момент сайт можно назвать защищенным.
