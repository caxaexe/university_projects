import alternativefunc as f
import time

library = {}

while True:
    time.sleep(1.25)
    print("\n\n\n\tМеню :")
    print("1. Добавить нового автора.")
    print("2. Добавить книгу к существующему автору.")
    print("3. Просмотреть список авторов и их книг.")
    print("4. Вывести сколько книг у каждого автора.")
    print("5. Удалить автора и все его книги из словаря.")
    print("6. Выход.")

    choice = input("\nВыберите номер пункта меню: ")

    if not choice.isdigit():
        print("Неверный ввод данных. Введите число от 1 до 6.")

    choice = int(choice)

    if choice == 1:
        author = input("\nВведите имя автора, которого хотите добавить в библиотеку: ")
        author = author.title()
        f.addAuthor(library, author)
    elif choice == 2:
        author = input("\nВведите имя автора: ")
        author = author.title()
        book = input("Введите название книги: ")
        book = book.title()
        f.addBook(library, author, book)
    elif choice == 3:
        f.authorAndBook(library)
    elif choice == 4:
        f.amountBooks(library)
    elif choice == 5:
        author = input("\nВведите имя автора: ")
        author = author.title()
        f.deleteData(library, author)
    elif choice == 6:
        print("Выход...")
        break
    else:
        print("Ошибка. Введите число от 1 до 6.")
