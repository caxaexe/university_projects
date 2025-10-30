# Лабораторная работа №2. Предотвращение атаков инъекций XSS-кода

## Предусловия
1. Создайте базу данных „guest”, содержащую одну таблицу - „guest”, которая будет
содержать данные со следующей структурой: id, user, text_message, e_mail,
data_time_message;
2. Создайте мини-приложение типа guest-book, которая позволит хранение введенных
пользователем данных в форму и которая потом выводит на экран все записи из таблицы
„guest”.

## Требования к лабораторной работе
1. Продемонстрируйте как можно использовать язык JavaScript для XSS атак, через контролы
созданной формы, непроверяя входные данные, перенапрвив пользователя на другой сайт
или вставив вместо всей страницы (все что было между <body>…</body>) спорную
фотографию. То есть необходимо вместо ожидаемого контента вывести другой,
неожиданный для пользователя.
2. Добавьте скрипты проверок чтобы небыло возможностей XSS атак.

## Документация к проекту

Я использую ту же базу данных, что и в прошлой лабораторной работе, и создаю новую таблицу `guest` с полями айди, имя, емейл и сообщение:  
  
<img width="660" height="264" alt="image" src="https://github.com/user-attachments/assets/511f1769-0b9e-43a4-a7eb-0190b453a906" />  



Ввожу данные в форму(имя, емейл, запись) и отправляю ее:
<img width="1916" height="602" alt="image" src="https://github.com/user-attachments/assets/f3419c27-7f5b-482c-8cc4-343d5d2f0717" />  
  
Данный попадают в созданную базу данных в таблицу `guest` и мы получаем следующий результат:  
<img width="1902" height="349" alt="image" src="https://github.com/user-attachments/assets/eb1c0c54-e17b-4ee1-97a8-330054a983f7" />  

В противном случае, если в базе нет никакой информации, мы получаем предупреждение о том, что никаких записей нет:  
<img width="1897" height="222" alt="image" src="https://github.com/user-attachments/assets/ab61bec1-65e4-4129-8165-6ae8469c1fe4" />  

А при отправке формы с пустым поле мы получаем сообщение о том, что это конкретное поле нужно заполнить:  
<img width="1901" height="707" alt="image" src="https://github.com/user-attachments/assets/06a20c20-78ae-47c6-b795-95fedace2a3b" />  

---

На этом этапе проводим самые распространенные XSS атаки на данной веб-странице, используя JS скрипты в поле для ввода сообщений:  
  
`<script>alert(123);</script>`:  
<img width="1889" height="668" alt="image" src="https://github.com/user-attachments/assets/aada1fb8-9f4e-464d-b41d-51beeffa9b4b" />  
Появляется всплывающее окно в браузере с текстом `123`:
<img width="1912" height="165" alt="image" src="https://github.com/user-attachments/assets/740a3b21-3c4c-4aa9-b17a-ea21d6d92ccb" />
  
`<script>window.location='https://example.com'</script>`:
<img width="1888" height="676" alt="image" src="https://github.com/user-attachments/assets/b547f9bf-28d6-4cdd-a373-42c6e784c96f" />  
Попадаем на сторонний сайт через скрипт:  
<img width="1899" height="347" alt="image" src="https://github.com/user-attachments/assets/98aea5e7-6464-40e9-8ed9-24b22b6fe6ad" />   
  
`<img src=x onerror="document.body.innerHTML='<h1>Пикачу</h1><img src=\'https://static.wikia.nocookie.net/pokemon/images/4/4a/0025Pikachu.png/revision/latest/scale-to-width-down/1000?cb=20250716011027\'/>'">`
<img width="1888" height="669" alt="image" src="https://github.com/user-attachments/assets/bcc3059c-6e41-40cf-8bb1-1a969009fd93" />
<img width="1911" height="590" alt="image" src="https://github.com/user-attachments/assets/b396b898-10cf-4168-a726-d8215a62ec8a" />

---

  
Изменяем два главных файла `guest.php` и `guest_save.php`, добавляя скрипты, обеспечивающие безопасность:
```html
<meta http-equiv="Content-Security-Policy" content="default-src 'self';">
```
Этот заголовок сообщает браузеру: разрешать загружать ресурсы только с нашего домена ('self'), запрещая скрипты, стили, изображения, если они идут с внешних сайтов.  

```php
<strong class="guest-username"><?= htmlspecialchars($r['user'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>

<div class="guest-message">
  <?= nl2br(htmlspecialchars($r['text_message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?>
</div>

<a href="mailto:<?= htmlspecialchars($r['e_mail'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
```
htmlspecialchars() преобразует специальные символы в HTML‑сущности, а ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' гарантирует, что и одинарные, и двойные кавычки безопасно экранируются, а некорректные UTF-символы заменяются на безопасные сущности.

```php
$stmt = $pdo->prepare("INSERT INTO guest (user, text_message, e_mail) VALUES (:user, :msg, :email)");
$stmt->execute([
    ':user' => $user,
    ':msg' => $msg,
    ':email' => $email
]);
```
Prepared statements — защищают от SQL‑инъекций  

---
  
Теперь заново проводим XSS атаки, чтобы проверить работоспособность этих скриптов. Использую точно такие же JS скрипты, что и ранее, вводя их в поле ввода сообщения:  
<img width="1890" height="684" alt="image" src="https://github.com/user-attachments/assets/f4cd3aa6-5ab4-4635-8b19-296bc8aa8c58" />  
<img width="1889" height="668" alt="image" src="https://github.com/user-attachments/assets/9acc242f-c009-4252-866c-ae20dc5098cd" />  
<img width="1886" height="676" alt="image" src="https://github.com/user-attachments/assets/45d1daa5-7de6-4353-8fb2-d5f9c86ce05e" />  
  
Выводятся просто как текст:  
<img width="1892" height="324" alt="image" src="https://github.com/user-attachments/assets/16c9850d-5fcc-4dfc-bf4f-a393b9da9f5d" />  
<img width="1885" height="331" alt="image" src="https://github.com/user-attachments/assets/aeed440d-b70f-4436-ae95-f14ec1e86482" />  
<img width="1888" height="332" alt="image" src="https://github.com/user-attachments/assets/26bdf5e1-b26c-442d-840a-46369c597613" />  








