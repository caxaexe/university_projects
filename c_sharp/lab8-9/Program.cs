var data = new Book
{
    First = new Data
    {
        Title = "First",
        Year = 1234,
        IsTaken = true,
    },
    Second = new Data
    {
        Title = "Second",
        Year = 1876,
        IsTaken = false,
    },
    Third = new Data
    {
        Title = "Third",
        Year = 1999,
        IsTaken = true,
    },
    Fourth = new Data
    {
        Title = "Fourth",
        Year = 2005,
        IsTaken = false,
    },
    Fifth = new Data
    {
        Title = "Fifth",
        Year = 1234,
        IsTaken = true,
    },
};

Data[] library =
{
    data.First,
    data.Second,
    data.Third,
    data.Fourth,
    data.Fifth
};

void ShowBooks(Data[] books)
{
    Console.WriteLine("Все книги в библиотеке:");
    foreach(var book in books)
    {
        Console.WriteLine($"{book.Title} ({book.Year}) - {(book.IsTaken ? "Занята" : "Свободна")}");
    }
}

int FindBook(Data[] books, string title)
{
    for (int i = 0; i < books.Length; i++)
    {
        if (books[i].Title == title)
            return i;
    }
    return -1;
}

void TakeBook(Data[] books, string title)
{
    int i = FindBook(books, title);
    if (i == -1)
    {
        Console.WriteLine("Книга не найдена.");
        return;
    }

    if (books[i].IsTaken)
    {
        Console.WriteLine("Книга уже занята.");
        return;
    }

    books[i].IsTaken = true;
    Console.WriteLine($"Вы взяли книгу '{title}'");
}

void ReturnBook(Data[] books, string title)
{
    int i = FindBook(books, title);
    if (i == -1)
    {
        Console.WriteLine("Книна не найдена.");
        return;
    }

    if (books[i].IsTaken)
    {
        Console.WriteLine("Книга свободна.");
        return;
    }

    books[i].IsTaken = false;
    Console.WriteLine($"Вы вернули книгу '{title}'");
}


while (true)
{
    Console.WriteLine("\n1 - Посмотреть все книги.");
    Console.WriteLine("2 - Взять книгу.");
    Console.WriteLine("3 - Вернуть книгу.");
    Console.WriteLine("4 - Выйти.");
    Console.WriteLine("\nВыбор: ");

    string choice = Console.ReadLine()!;

    switch (choice)
    {
        case "1":
            ShowBooks(library);
            break;
        case "2":
            Console.WriteLine("Введите название книги: ");
            string takeTitle = Console.ReadLine()!;
            TakeBook(library, takeTitle);
            break;
        case "3":
            Console.WriteLine("Введите название книги: ");
            string returnBook = Console.ReadLine()!;
            ReturnBook(library, returnBook);
            break;
        case "4":
            Console.WriteLine("Выход");
            return;
        default:
            Console.WriteLine("Нельзя");
            break;
    }
}


class Book
{
    public required Data First;
    public required Data Second;
    public required Data Third;
    public required Data Fourth;
    public required Data Fifth;
}

class Data
{
    public required string Title;
    public required int Year;
    public required bool IsTaken;
}