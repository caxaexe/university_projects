Book[] library =
{
    new Book
    {
        Title = "First",
        Year = 1234,
        IsTaken = true
    },
    new Book
    {
        Title = "Second",
        Year = 1876,
        IsTaken = false
    },
    new Book
    {
        Title = "Third",
        Year = 1999,
        IsTaken = true
    },
    new Book
    {
        Title = "Fourth",
        Year = 2005,
        IsTaken = false
    },
    new Book
    {
        Title = "Fifth",
        Year = 9876,
        IsTaken = false
    }
};

ICommand[] commands =
{
    new ShowBooksCommand(library),
    new TakeBookCommand(library),
    new ReturnBookCommand(library),
    new ExitCommand()
};

while (true)
{
    Console.WriteLine("\n1 - Посмотреть все книги");
    Console.WriteLine("2 - Взять книгу");
    Console.WriteLine("3 - Вернуть книгу");
    Console.WriteLine("4 - Выход");
    Console.Write("Выбор: ");

    if (int.TryParse(Console.ReadLine(), out int choice) &&
        choice >= 1 && choice <= commands.Length)
    {
        commands[choice - 1].Execute();
    }
    else
        Console.WriteLine("Нельзя");
}
class Book
{
    public required string Title;
    public required int Year;
    public required bool IsTaken;
}

interface ICommand
{
    void Execute();
}

class ShowBooksCommand : ICommand
{
    private Book[] books;
    public ShowBooksCommand(Book[] b) => books = b;
    public void Execute() => Functions.ShowBooks(books);
}

class TakeBookCommand : ICommand
{
    private Book[] books;
    public TakeBookCommand(Book[] b) => books = b;
    public void Execute()
    {
        Console.WriteLine("Введите название книги: ");
        string t = Console.ReadLine()!;
        Functions.TakeBook(books, t);
    }
}

class ReturnBookCommand : ICommand
{
    private Book[]  books;
    public ReturnBookCommand(Book[] b) => books = b;
    public void Execute()
    {
        Console.WriteLine("Введите название книги: ");
        string t = Console.ReadLine()!;
        Functions.ReturnBook(books, t);
    }
}

class ExitCommand : ICommand
{
    public void Execute()
    {
        Console.WriteLine("Выход");
        Environment.Exit(0);
    }
}
static class Functions
{
    public static void ShowBooks(Book[] books)
    {
        Console.WriteLine("Все книги:");
        foreach (var b in books)
            Console.WriteLine($"{b.Title} ({b.Year}) — {(b.IsTaken ? "Занята" : "Свободна")}");
    }

    public static int FindBook(Book[] books, string title)
    {
        for (int i = 0; i < books.Length; i++)
            if (books[i].Title == title)
                return i;
        return -1;
    }

    public static void TakeBook(Book[] books, string title)
    {
        int i = FindBook(books, title);
        if (i == -1) { Console.WriteLine("Не найдено"); return; }
        if (books[i].IsTaken) { Console.WriteLine("Данная книга уже взята"); return; }

        books[i].IsTaken = true;
        Console.WriteLine($"Вы взяли книгу '{title}'");
    }

    public static void ReturnBook(Book[] books, string title)
    {
        int i = FindBook(books, title);
        if (i == -1) { Console.WriteLine("Не найдено"); return; }
        if (!books[i].IsTaken) { Console.WriteLine("Данная книга и так свободна"); return; }

        books[i].IsTaken = false;
        Console.WriteLine($"Вы вернули книгу '{title}'");
    }
}