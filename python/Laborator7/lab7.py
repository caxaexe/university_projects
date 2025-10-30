# узнать сколько дней прожил пользователь, использовать модули DateTime и Calendar(?)
import re
from datetime import datetime, date
import time

while True:
    birthdate = input("Введите вашу дату рождения (дд.мм.гггг): ")
    if not re.match('^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.\d{4}$', birthdate):
        print("Неверный ввод. Соблюдайте требуемые условия.\n")
        continue
    birth_date = datetime.strptime(birthdate, '%d.%m.%Y')
    if birth_date.date() > datetime.now().date():
        print("\nВы преувеличивайте, перестаньте.\n")
        continue
    break

year_of_birth = birth_date.year
month_of_birth = birth_date.month
day_of_birth = birth_date.day

today = datetime.now().date()
birth_date = date(year_of_birth, month_of_birth, day_of_birth)
days = (today - birth_date).days  # возвращает колво дней

time.sleep(0.5)
print("\nУ вас на счету", days, "дней.")
time.sleep(1)
print("Мне жаль.")
time.sleep(1)


print()
print("---------------------------------------------")
print()


# узнать на какой день недели приходится дата, использовать модуль Calendar
import calendar

while True:
    date = input("Введите дату в формате дд.мм.гггг : ")
    if not re.match('^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.\d{4}+$', date):
        print("Неверный ввод. Соблюдайте требуемые условия.")
        continue
    else:
        date = datetime.strptime(date, '%d.%m.%Y')
        break

day = date.day
month = date.month
year = date.year

weekday_num = calendar.weekday(year, month, day)
weekdays = ["понедельник", "вторник", "среду", "четверг", "пятницу", "субботу", "воскресенье"]
weekday_text = weekdays[weekday_num]

print()
print(date.strftime("%d.%m.%Y"), "приходится на", weekday_text, end=".")
print()


print()
print("---------------------------------------------")
print()


# расчет времени падения объекта, использовать методы math.isnan и math.sqrt
import math

while True:
    height_input = input("Введите высоту падения объекта (в метрах): ")
    try:
        height = float(height_input)
        break
    except ValueError:
        print("Неверный ввод. Введите число.")

if math.isnan(height):
    print("Неверный ввод. Введите корректное число для высоты.")

g = 9.8
time = math.sqrt((2 * height) / g)
height = int(height)
print("\nВремя падения объекта с высоты", height, "метров:", round(time, 2), "секунд")


print()
print("---------------------------------------------")
print()
