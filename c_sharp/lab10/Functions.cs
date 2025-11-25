static class Functions
{
   public void ShowBooks(Data[] books)
{
    Console.WriteLine("Все книги в библиотеке:");
    foreach(var book in books)
    {
        Console.WriteLine($"{book.Title} ({book.Year}) - {(book.IsTaken ? "Занята" : "Свободна")}");
    }
}

public int FindBook(Data[] books, string title)
{
    for (int i = 0; i < books.Length; i++)
    {
        if (books[i].Title == title)
            return i;
    }
    return -1;
}

public void TakeBook(Data[] books, string title)
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

public void ReturnBook(Data[] books, string title)
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
}
