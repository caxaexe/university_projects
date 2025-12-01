static class Functions
{
    public static void ShowBooks(Book[] books)
    {
        Console.WriteLine("Все книги в библиотеке:");
        foreach (var book in books)
        {
            Console.WriteLine($"{book.Title} ({book.Year}) - {(book.IsTaken ? "Занята" : "Свободна")}");
        }
    }

    public static int FindBook(Book[] books, string title)
    {
        for (int i = 0; i < books.Length; i++)
        {
            if (books[i].Title == title)
                return i;
        }
        return -1;
    }

    public static void TakeBook(Book[] books, string title)
    {
        int index = FindBook(books, title);

        if (index == -1)
        {
            Console.WriteLine("Книга не найдена.");
            return;
        }

        if (books[index].IsTaken)
        {
            Console.WriteLine("Книга уже занята.");
            return;
        }

        books[index].IsTaken = true; 
        Console.WriteLine($"Вы взяли книгу '{title}'.");
    }

    public static void ReturnBook(Book[] books, string title)
    {
        int index = FindBook(books, title);

        if (index == -1)
        {
            Console.WriteLine("Книга не найдена.");
            return;
        }

        if (!books[index].IsTaken)
        {
            Console.WriteLine("Книга и так свободна.");
            return;
        }

        books[index].IsTaken = false; 
        Console.WriteLine($"Вы вернули книгу '{title}'.");
    }
}
