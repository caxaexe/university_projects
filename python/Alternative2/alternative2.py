import alternative2func as f

while True:
    print("\n------------------------------------------")
    print("1. Записать данные о ученике.")
    print("2. Средний балл всего класса.")
    print("3. Список учеников с оценками ниже 5.")
    print("4. Список учеников с оценками выше 8.")
    print("5. Выход.")
    print("------------------------------------------")

    choice = input("\nВыберите пункт меню : ")
    print()

    if not choice.isdigit():
        print("\nНеправильный ввод. Введите целое число.\n")
        continue
    choice = int(choice)

    if choice == 1:
        f.addInfo()
        print("\nДанные успешно сохранены.\n")
    elif choice == 2:
        f.averageGrade()
    elif choice == 3:
        print("Список учеников, у которых оценки ниже 5 :")
        f.gradeBelowFive()
    elif choice == 4:
        print("Список учеников, у которых оценки выше 8 :")
        f.gradeAbowFive()
    elif choice == 5:
        print("\nВыход из программы..")
        break
    else:
        print("Неправильный ввод. Введите число в диапазоне от 1 до 5.\n")
