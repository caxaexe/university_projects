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
            Functions.ShowBooks(library);
            break;
        case "2":
            Console.WriteLine("Введите название книги: ");
            string takeTitle = Console.ReadLine()!;
            Functions.TakeBook(library, takeTitle);
            break;
        case "3":
            Console.WriteLine("Введите название книги: ");
            string returnBook = Console.ReadLine()!;
            Functions.ReturnBook(library, returnBook);
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
