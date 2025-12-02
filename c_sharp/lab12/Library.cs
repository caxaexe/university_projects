class Library
{
    private List<Book> freeBooks;
    private List<Book> takenBooks;

    public Library(Book[] books)
    {
        freeBooks = new List<Book>();
        takenBooks = new List<Book>();

        foreach (Book b in books)
        {
            if (b.IsTaken)
                takenBooks.Add(b);
            else
                freeBooks.Add(b);
        }
    }

    public void ShowBooks()
    {
        Console.WriteLine("\nВсе книги:");

        foreach (Book b in freeBooks)
            Console.WriteLine($"{b.Title} ({b.Year}) - Свободна - Автор: {b.Author}");

        foreach (Book b in takenBooks)
            Console.WriteLine($"{b.Title} ({b.Year}) - Занята - Автор: {b.Author}");
    }

    public void TakeBook(string title)
    {
        Book? found = null;

        foreach (Book b in freeBooks)
        {
            if (b.Title == title)
            {
                found = b;
                break;
            }
        }

        if (found == null)
        {
            Console.WriteLine("Книга не найдена или занята.");
            return;
        }

        freeBooks.Remove(found);
        found.IsTaken = true;
        takenBooks.Add(found);

        Console.WriteLine($"Вы взяли '{title}'.");
    }

    public void ReturnBook(string title)
    {
        Book? found = null;

        foreach (Book b in takenBooks)
        {
            if (b.Title == title)
            {
                found = b;
                break;
            }
        }

        if (found == null)
        {
            Console.WriteLine("Книга не найдена среди занятых.");
            return;
        }

        takenBooks.Remove(found);
        found.IsTaken = false;
        freeBooks.Add(found);

        Console.WriteLine($"Вы вернули '{title}'.");
    }

    public void ShowBooksFilter(LibraryFilter filter)
    {
        Console.WriteLine("\nОтфильтрованные книги:");

        List<Book> all = new List<Book>();
        all.AddRange(freeBooks);
        all.AddRange(takenBooks);

        foreach (Book b in all)
        {
            if (filter.State == BookState.Free && b.IsTaken) continue;
            if (filter.State == BookState.Taken && !b.IsTaken) continue;

            if (filter.TitleContains != null &&
                !b.Title.Contains(filter.TitleContains))
                continue;

            if (filter.TitleNotContains != null &&
                b.Title.Contains(filter.TitleNotContains))
                continue;

            if (filter.TitleStartsWith != null &&
                !b.Title.StartsWith(filter.TitleStartsWith))
                continue;

            if (filter.Author != null &&
                b.Author != filter.Author)
                continue;

            Console.WriteLine($"{b.Title} ({b.Year}) - {(b.IsTaken ? "Занята" : "Свободна")} - Автор: {b.Author}");
        }
    }
}
