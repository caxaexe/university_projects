Library library = new Library(
    new Book[]
    {
        new Book
        {
            Title = "First",
            Year = 1234,
            IsTaken = true,
        },
        new Book
        {
            Title = "Second",
            Year = 1876,
            IsTaken = false,
        },
        new Book
        {
            Title = "Third",
            Year = 1999,
            IsTaken = true,
        },
        new Book
        {
            Title = "Fourth",
            Year = 2005,
            IsTaken = false,
        },
        new Book
        {
            Title = "Fifth",
            Year = 1234,
            IsTaken = true,
        },
    }
);


while (true)
{
    Console.WriteLine("\n1 - Посмотреть все книги.");
    Console.WriteLine("2 - Взять книгу.");
    Console.WriteLine("3 - Вернуть книгу.");
    Console.WriteLine("4 - Посмотреть книги по фильтру.");
    Console.WriteLine("5 - Выйти.");
    Console.WriteLine("Выбор: ");

    string? choice = Console.ReadLine();

    switch (choice)
    {
        case "1":
            library.ShowBooks();
            break;
        case "2":
            Console.WriteLine("Введите название книги: ");
            {
                string? t = Console.ReadLine();
                if (t == null) t = "";
                library.TakeBook(t);
            }
            break;
        case "3":
            Console.Write("Введите название: ");
            {
                string? t = Console.ReadLine();
                if (t == null) t = "";
                library.ReturnBook(t);
            }
            break;
        case "4":
            {
                LibraryFilter filter = CreateFilter();
                library.ShowBooksFilter(filter);
            }
            break;
        case "5":
            Console.WriteLine("Выход");
            return;
        default:
            Console.WriteLine("Нельзя");
            break;
    }
}

static LibraryFilter CreateFilter()
    {
        LibraryFilter filter = new LibraryFilter();

        Console.Write("Название содержит: ");
        string? t1 = Console.ReadLine();
        if (t1 == "")
            filter.TitleContains = null;
        else
            filter.TitleContains = t1;

        Console.Write("Начинается с: ");
        string? t2 = Console.ReadLine();
        if (t2 == "")
            filter.TitleStartsWith = null;
        else
            filter.TitleStartsWith = t2;

        Console.Write("Состояние (free / taken / any): ");
        string? state = Console.ReadLine();

        if (state == "free")
            filter.IsTaken = false;
        else if (state == "taken")
            filter.IsTaken = true;
        else
            filter.IsTaken = null;

        return filter;
}

class Book
{
    public string Title { get; set; }
    public int Year { get; set; }
    public bool IsTaken { get; set; }

    public Book()
    {
        Title = "";
    }
}

class LibraryFilter
{
    public string? TitleContains { get; set; }
    public string? TitleStartsWith { get; set; }
    public bool? IsTaken { get; set; }
}