Console.WriteLine("Hello, Jerry");
Console.WriteLine("----------------------------");
ABC();
Thread.Sleep(500);
ABC();
Thread.Sleep(3000);
ABC();
Thread.Sleep(3000);
Console.WriteLine("----------------------------");
A();
A();
A();


void ABC()
{
    Console.WriteLine("A");
    Console.WriteLine("B");
    Console.WriteLine("C");
}

void A()
{
    Console.WriteLine("A");
    B();
    C();
}

void B()
{
    Console.WriteLine("B");
}

void C()
{
    Console.WriteLine("C");
}