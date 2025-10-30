// анализ

sar1();
sar2();
sar3();
sar4();


void sar1()
{
    int a = 5;
    int b = 6;
    a = b;
    b = 7;
    Console.WriteLine(a);
}


void sar2()
{
    int a = 5;
    int b = a + 6;
    a = 7;
    Console.WriteLine(b);
}

void sar3()
{
    string a = "a";
    string b = a;
    a = "b";
    Console.WriteLine(a);
}  

void sar4()
{
    //string a = 5;
}