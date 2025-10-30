def addAuthor(library, author):
    if author not in library:
        library[author] = []
        print("Автор '", author, "' добавлен(а) в библиотеку.")
    else:
        print("Данный автор уже есть в библиотеке.")

def addBook(library, author, book):
    if author in library:
        library[author].append(book)
        print("Книга '", book, "' добавлена в библиотеку." )
    else:
        print("Автор '", author, "' не найден(а) в библиотеке.")

def authorAndBook(library):
    if library:
        print("\nСписок авторов и их книг :")
        print("---------------------")
        for authorName, listBooks in library.items():
            print("Автор:", authorName)
            print("Книги:")
            for bookName in listBooks:
                print("-", bookName)
            print("---------------------")
    else:
        print("\nВ библиотеке пусто.")

def amountBooks(library):
    print()
    if library:
        for author, books in library.items():
            print("Количество книг у", author, ":", len(books), end=".\n")
    else:
        print("\nВ библиотеке пусто.")

def deleteData(library, author):
    if author in library:
        del library[author]
        print("Автор '", author, "' был(а) удален(а) из библиотеки.")
    else:
        print("Данного автора и так нет в библиотеке.")
  