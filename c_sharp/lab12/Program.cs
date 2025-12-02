Library library = new Library(
    new Book[]
    {
        new Book { Title = "First", Year = 1234, IsTaken = true, Author = "John" },
        new Book { Title = "Second", Year = 1876, IsTaken = false, Author = "Kavalski" },
        new Book { Title = "Third", Year = 1999, IsTaken = true, Author = "Plus" },
        new Book { Title = "Fourth", Year = 2005, IsTaken = false, Author = "Minus" },
        new Book { Title = "Fifth", Year = 1234, IsTaken = true, Author = "Jane" },
    }
);


InputLibraryFilter testInput = new InputLibraryFilter();
testInput.TrySetTitleContains("i");
testInput.TrySetState("any");
LibraryFilter testFilter = testInput.CreateFinalFilter();
Console.WriteLine("\nТестовый фильтр:");
library.ShowBooksFilter(testFilter);

while (true)
{
    Console.WriteLine("\n1 - Все книги");
    Console.WriteLine("2 - Взять книгу");
    Console.WriteLine("3 - Вернуть книгу");
    Console.WriteLine("4 - Выход");
    Console.Write("Выбор: ");

    string? choice = Console.ReadLine();

    switch (choice)
    {
        case "1":
            library.ShowBooks();
            break;

        case "2":
            Console.Write("Введите название: ");
            library.TakeBook(Console.ReadLine() ?? "");
            break;

        case "3":
            Console.Write("Введите название: ");
            library.ReturnBook(Console.ReadLine() ?? "");
            break;
        case "4":
            Console.WriteLine("Выход.");
            return;
        default:
            Console.WriteLine("Неверный выбор.");
            break;
    }
}
