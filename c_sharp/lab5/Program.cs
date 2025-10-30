static int CustomerTotal(Prices prices, ClientChoice choice)
{
    return choice.Drink * prices.Drink + choice.First * prices.First + choice.Second * prices.Second;
}

Prices prices = new Prices(drink: 10, first: 20, second: 30);

{
    ClientChoice clientChoice = new ClientChoice(drink: 250 , first: 300, second: 0); 

    int total = CustomerTotal(prices, clientChoice);

    Console.WriteLine("Заказ первого клиента: " + total);
}


{
    ClientChoice clientChoice = new ClientChoice(drink: 0, first: 150, second: 300);

    int total = CustomerTotal(prices, clientChoice);

    Console.WriteLine("Заказ второго клиента: " + total);
}

//тип-значение
struct Prices
{
    public int Drink;
    public int First;
    public int Second;

    public Prices(int drink, int first, int second)
    {
        Drink = drink;
        First = first;
        Second = second;
    }
}

struct ClientChoice
{
    public int Drink;
    public int First;
    public int Second;

    public ClientChoice(int drink, int first, int second)
    {
        Drink = drink;
        First = first;
        Second = second;
    }
}
