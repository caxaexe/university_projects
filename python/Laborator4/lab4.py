# Создать калькулятор «идеального веса», используя формулу Лоренса.

year = int(input("Введите ваш возраст в годах : "))
hight = int(input("Введите ваш рост в см : "))
width = int(input("Введите ваш вес в кг: "))
gender = input("Введите ваш пол (М или Ж) : ")

if gender == "М" or gender == "м":
    perfect = hight - 100 - ((hight - 150)/4 + (year - 20)/4)
    print("\nИдеальный вес =", int(perfect), "кг.")
    if width > int(perfect):
        print("Возможно стоит сбросить вес. Или нет.")
    elif width == int(perfect):
        print("У вас идеальный вес.")
    else:
        print("Возможно стоит набрать вес. Или нет.")
elif gender == "Ж" or gender == "ж":
    perfect = hight - 100 - ((hight - 150)/2.5 + (year - 20)/6)
    print("\nИдеальный вес =", int(perfect), "кг.")
    if width > int(perfect):
        print("Возможно стоит сбросить вес. Или нет.")
    elif width == int(perfect):
        print("У вас идеальный вес.")
    else:
        print("Возможно стоит набрать вес. Или нет.")
else :
    print("Некорректный ввод. Повторите попытку.")