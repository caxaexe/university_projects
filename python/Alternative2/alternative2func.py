import re
from datetime import datetime

def addInfo():
    while True:
        with open("student.txt", mode="a", encoding="utf-8") as student:
            surname = input("Введите фамилию ученика : ")
            surname = surname.title()
            if not re.match('^[а-яА-ЯёЁa-zA-Z\s-]{2,20}$', surname):
                print("Неверный ввод. Допустимое количество букв 2-20.")
                continue
            name = input("Введите имя ученика : ")
            name = name.title()
            if not re.match('^[а-яА-ЯёЁa-zA-Z\s-]{2,20}$', name):
                print("Неверный ввод. Допустимое количество букв 2-20.")
                continue
            date = input("Введите дату в формате дд.мм.гггг: ")
            try:
                validDate = datetime.strptime(date, '%d.%m.%Y')
                if validDate < datetime(2023, 9, 1) or validDate > datetime.now():
                    print("Неверный ввод. Допустимые даты от 01.09.2023 до настоящего времени.")
                    continue
            except ValueError:
                print("Неверный ввод. Неправильный формат даты.")
                continue
            grade = input("Введите оценку ученика : ")
            if not re.match('^[1-9]|10$', grade):
                print("Неверный ввод. Введите целое число.")
                continue
            if int(grade) < 1 or int(grade) > 10:
                print("Допустимый диапазон оценки от 1 до 10.")
                continue
            with open("student.txt", mode="a", encoding="utf-8") as student:
                student.write(f"{surname}\t{name}\t{date}\t{grade}\n")
            break

def averageGrade():
    totalGrades = 0
    totalStudents = 0
    try:
        with open("student.txt", mode = "r", encoding = "utf-8") as student:
            for line in student:
                surname, name, date, grade = line.split()
                totalGrades += int(grade)
                totalStudents += 1
        if totalStudents == 0:
            print("В файле не найдена информация о учениках.")
        else:
            total = totalGrades / totalStudents
            total = round(total, 2)
            print("Средний балл в классе : ", total)
    except FileNotFoundError:
        print("Файл 'student.txt' не найден.")


def gradeBelowFive():
    try:
        with open("student.txt", mode="r", encoding="utf-8") as student:
            for line in student:
                surname, name, date, grade = line.split()
                grade = int(grade)
                if grade < 5:
                    print(surname, name, "-", grade)
    except FileNotFoundError:
        print("Файл 'student.txt' не найден.")

def gradeAbowFive():
    try:
        with open("student.txt", mode="r", encoding="utf-8") as student:
            for line in student:
                surname, name, date, grade = line.split()
                grade = int(grade)
                if grade > 8:
                    print(surname, name, "-", grade)
    except FileNotFoundError:
        print("Файл 'student.txt' не найден.")