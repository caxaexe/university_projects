class InputLibraryFilter
{
    private LibraryFilter f = new LibraryFilter();

    public bool TrySetTitleContains(string? s)
    {
        f.TitleContains = string.IsNullOrWhiteSpace(s) ? null : s;
        return true;
    }

    public bool TrySetTitleNotContains(string? s)
    {
        f.TitleNotContains = string.IsNullOrWhiteSpace(s) ? null : s;
        return true;
    }

    public bool TrySetTitleStartsWith(string? s)
    {
        f.TitleStartsWith = string.IsNullOrWhiteSpace(s) ? null : s;
        return true;
    }

    public bool TrySetAuthor(string? s)
    {
        f.Author = string.IsNullOrWhiteSpace(s) ? null : s;
        return true;
    }

    public bool TrySetState(string? s)
    {
        if (s == null) return false;

        s = s.Trim().ToLower();

        if (s == "free") f.State = BookState.Free;
        else if (s == "taken") f.State = BookState.Taken;
        else if (s == "any") f.State = BookState.Any;
        else return false;

        return true;
    }

    public LibraryFilter CreateFinalFilter()
    {
        return f;
    }
}
