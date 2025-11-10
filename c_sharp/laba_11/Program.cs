using System.Diagnostics;

Stoplight now = Stoplight.green;

for (int i = 0; i < 3; i++)
{
    string a = Action(now);
    PrintAction(a);
    now = NextStep(now);
}



static string Action(Stoplight a)
{
    switch (a)
    {
        case Stoplight.green:
            {
                return "Go";
            }
        case Stoplight.red:
            {
                return "Stop";
            }
        case Stoplight.yellow:
            {
                return "Be ready";
            }
        default:
            {
                Debug.Fail("Нет такого в светофоре ты чево");
                return "";
            }
    }
}

static void PrintAction(string a)
{
    Console.WriteLine($"Completed action: {a}");
}

static Stoplight NextStep(Stoplight a)
{
    switch (a)
    {
        case Stoplight.green:
            {
                return Stoplight.yellow;
            }
        case Stoplight.yellow:
            {
                return Stoplight.red;
            }
        case Stoplight.red:
            {
                return Stoplight.green;
            }
        default:
            {
                Debug.Fail("Запрещаем, дружочек");
                return Stoplight.red;
            }
    }
}

enum Stoplight
{
    green,
    red,
    yellow,
}