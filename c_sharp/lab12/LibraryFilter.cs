using System.Diagnostics;

enum BookState
{
    Free,
    Taken,
    Any
}

class LibraryFilter
{
    private string? contains;
    private string? notContains;
    private string? startsWith;
    private string? author;
    private BookState state = BookState.Any;

    public string? TitleContains
    {
        get => contains;
        set
        {
            Debug.Assert(value == null || value.Length > 0);
            contains = string.IsNullOrWhiteSpace(value) ? null : value;
        }
    }

    public string? TitleNotContains
    {
        get => notContains;
        set
        {
            Debug.Assert(value == null || value.Length > 0);
            notContains = string.IsNullOrWhiteSpace(value) ? null : value;
        }
    }

    public string? TitleStartsWith
    {
        get => startsWith;
        set
        {
            Debug.Assert(value == null || value.Length > 0);
            startsWith = string.IsNullOrWhiteSpace(value) ? null : value;
        }
    }

    public string? Author
    {
        get => author;
        set
        {
            Debug.Assert(value == null || value.Length > 0);
            author = string.IsNullOrWhiteSpace(value) ? null : value;
        }
    }

    public BookState State
    {
        get => state;
        set
        {
            Debug.Assert(Enum.IsDefined(typeof(BookState), value));
            state = value;
        }
    }
}
