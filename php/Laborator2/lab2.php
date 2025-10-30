<?php

$date = date("l");

function getdata($date, $person)
{
    if ($person === "John Styles") {
        if ($date == "Monday" || $date == "Wednesday" || $date == "Friday") {
            return "8:00 - 12:00";
        }
        return "Нерабочий день";
    }

    if ($person === "Jane Doe") {
        if ($date == "Tuesday" || $date == "Thursday" || $date == "Saturday") {
            return "12:00 - 16:00";
        }
        return "Нерабочий день";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laborator2</title>
</head>

<body>
    <table border="1px" align="center" cellspacing="0" cellpadding="3" width="30%">
        <th>№</th>
        <th>Фамилия Имя</th>
        <th>График работы</th>
        <tr>
            <td>1</td>
            <td>John Styles</td>
            <td><?php echo getdata($date, "John Styles"); ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jane Doe</td>
            <td><?php echo getdata($date, "Jane Doe"); ?></td>
        </tr>
    </table>
</body>

</html>
