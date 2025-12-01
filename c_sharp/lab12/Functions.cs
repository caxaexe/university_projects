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
        Console.WriteLine("Все книги в библиотеке:");

        foreach (Book b in freeBooks)
        {
            Console.WriteLine($"{b.Title} ({b.Year}) - Свободна");
        }

        foreach (Book b in takenBooks)
        {
            Console.WriteLine($"{b.Title} ({b.Year}) - Занята");
        }
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
            Console.WriteLine("Книга не найдена или уже занята.");
            return;
        }

        freeBooks.Remove(found);
        found.IsTaken = true;  
        takenBooks.Add(found);

        Console.WriteLine($"Вы взяли книгу '{title}'.");
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
            Console.WriteLine("Книга не найдена или уже занята.");
            return;
        }

        takenBooks.Remove(found);
        found.IsTaken = false;  
        freeBooks.Add(found);
              
        Console.WriteLine($"Вы вернули книгу '{title}'.");
}

    public void ShowBooksFilter(LibraryFilter filter)
    {
        Console.WriteLine("Отфильтрованные книги: ");

        List<Book> all = new List<Book>();

        foreach (Book b in freeBooks) 
            all.Add(b);

        foreach (Book b in takenBooks) 
            all.Add(b);

        foreach (Book b in all)
        {
            if (filter.IsTaken != null)
            {
                if (filter.IsTaken == true && b.IsTaken == false)
                    continue;

                if (filter.IsTaken == false && b.IsTaken == true)
                    continue;
            }

            if (filter.TitleContains != null)
            {
                if(!b.Title.Contains(filter.TitleContains))
                    continue;
            }

            if (filter.TitleStartsWith != null)
            {
                if (!b.Title.StartsWith(filter.TitleStartsWith))
                    continue;
            }

            Console.WriteLine($"{b.Title} ({b.Year}) - {(b.IsTaken ? "Занята" : "Свободна")}");
        }
    }
}