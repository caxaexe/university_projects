# split - разделяет строки на подстроки, strip - удаляет пробелы в начале и конце
# re.match - поиск в текстовом файле
import re

def addInfo():
    while True:
        with open("Labs/data.txt", mode = "a", encoding = "utf-8") as f:
            surname = input("Введите фамилию сотрудника : ").strip()
            if not re.match('^[A-Za-zА-Яа-яЁё-]{2,20}$', surname):
                print("Неверный ввод. Допустимое количество букв 2-20.")
                continue
            else:
                f.write(surname + "\t")
            name = input("Введите имя сотрудника : ").strip()
            if not re.match('^[A-Za-zА-Яа-яЁё-]{2,20}$', name):
                print("Неверный ввод. Допустимое количество букв 2-20.")
                continue
            else: 
                f.write(name + "\t")
            job = input("Введите название отдела, в котором работает сотрудник : ").strip()
            if not re.match('^[A-Za-zА-Яа-яЁё0-9\s]+$', job):
                print("Неверный ввод. Допустимы только буквы, цифры и пробелы.")
                continue
            else:
                f.write(job + "\t")
            children = input("Введите количество детей сотрудника : ").strip()
            if not re.match('^[0-9]{0,19}$', children):
                print("Неверный ввод. Допустимы только целые числа.")
                continue
            intChildren = int(children)
            if intChildren < 0 or intChildren > 19:
                print("Неверный ввод. Допустимое количество детей 0-19.")
                continue
            else: 
                f.write(children + "\n")
            break
    print("\nДанные успешно добавлены.")
    
def infoChildren():
    print()
    allChildren = 0
    try:
        with open("Labs/data.txt", mode = "r", encoding = "utf-8") as f:
            for line in f:
                surname, name, job, children = line.split()
                intChildren = int(children)
                allChildren += intChildren
                print("У сотрудника", surname, name, "из отдела", job, intChildren, "детей.")
    except FileNotFoundError:
        print("Файл 'data.txt' не найден.")
    print("Общее число детей:", allChildren)

def noChildren():
    print()
    try:
        with open("Labs/data.txt", mode = "r", encoding = "utf-8") as f:
            print("Список сотрудников без детей : ")
            for line in f:
                surname, name, job, children = line.split()
                intChildren = int(children)
                if intChildren == 0:
                    print(surname, name)
    except FileNotFoundError:
        print("Файл 'data.txt' не найден.")

                                                        
while True:
    print("\n\n1. Добавить данные в файл.")
    print("2. Вывод данных о детях сотрудников из файла.")
    print("3. Поиск и вывод данных о бездетных сотрудниках.")
    print("4. Выход из программы.")
    choice = int(input("Выберите пункт меню : "))
    
    if choice == 1:
        addInfo()
    elif choice == 2:
        infoChildren()
    elif choice == 3:
        noChildren()
    elif choice == 4:
        break
    else:
        print("Выберите пункт меню из предложенных четырех.")