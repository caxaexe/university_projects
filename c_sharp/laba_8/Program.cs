int num1 = Validate("первое число");
int num2 = Validate("второе число");
int num3 = Validate("третье число");
int multiply = num1 * num2 * num3;
Console.WriteLine(multiply);

static int Validate(string name)
{
    while (true)
    {
        Console.WriteLine($"Введите {name}:");
        string str = Console.ReadLine()!;
        bool parsed = int.TryParse(str, out int result);
        if (!parsed)
        {
            Console.WriteLine("Введите число.");
            continue;
        }

        if (result < 2)
        {
            Console.WriteLine("Число должно быть больше 2.");
            continue;

        }

        return result;
    }
}
