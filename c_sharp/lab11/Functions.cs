class Library
{

    public required Book[] Books;
    public void ShowBooks()
    {
        Console.WriteLine("Все книги в библиотеке:");
        foreach (var book in Books)
        {
            Console.WriteLine($"{book.Title} ({book.Year}) - {(book.IsTaken ? "Занята" : "Свободна")}");
        }
    }

    private int FindBook(string title)
    {
        for (int i = 0; i < Books.Length; i++)
        {
            if (Books[i].Title == title)
                return i;
        }
        return -1;
    }

    public void TakeBook(string title)
    {
        int index = FindBook(title);

        if (index == -1)
        {
            Console.WriteLine("Книга не найдена.");
            return;
        }

        if (Books[index].IsTaken)
        {
            Console.WriteLine("Книга уже занята.");
            return;
        }

        Books[index].IsTaken = true; 
        Console.WriteLine($"Вы взяли книгу '{title}'.");
    }

    public void ReturnBook(string title)
    {
        int index = FindBook(title);

        if (index == -1)
        {
            Console.WriteLine("Книга не найдена.");
            return;
        }

        if (!Books[index].IsTaken)
        {
            Console.WriteLine("Книга и так свободна.");
            return;
        }

        Books[index].IsTaken = false; 
        Console.WriteLine($"Вы вернули книгу '{title}'.");
    }
}