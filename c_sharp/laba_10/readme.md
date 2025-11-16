# Анализ

```cs
if (true)
{
    Console.WriteLine("Hello");
}
```
 > из-за условия true инструкция всегда будет выполняться 
 
```cs
if (false)
{
    Console.WriteLine("Hello");
}
```
 > такое не пропускаем - unreachable означает, что этот код гарантировано никогда не выполнится

```cs
bool execute = true;
if (execute)
{
    Console.WriteLine("Hello");
}

bool notExecute = !execute;
if (notExecute)
{
    Console.WriteLine("Not executed");
}
```
 > Hello, выведется инструкция с уловием тру

*В чем функциональное и семантическое различие функций F1, F2, F3?*
```cs
static void F1()
{
    if (A())
    {
        if (B())
        {
            Console.WriteLine("Hello");
        }
    }
}

static void F2()
{
    if (A() && B())
    {
        Console.WriteLine("Hello");
    }
}

static void F3()
{
    bool a = A();
    bool b = B();
    bool ok = a && b;
    if (ok)
    {
        Console.WriteLine("Hello");
    }
}

static bool A()
{
    Console.WriteLine("A");
    return true;
}
static bool B()
{
    Console.WriteLine("B");
    return true;
}
```
 > семантически - написаны по-разному, но вывол один и тот же. функционально - если в f3 вернуть false из а, буде другой результат


```cs
if (1)
{
    Console.WriteLine("Hello");
}
```
 > if применяется только с bool значениями, с числовыми неа

```cs
if (false)
{
    Console.WriteLine("A");
    Console.WriteLine("B");
}
```
 > ничего не напечатается

```cs
if (false)
    Console.WriteLine("A");
    Console.WriteLine("B");
```
 > напечатается B, пушто только инструкция с А относится к if 

```cs
if (false)
{
    Console.WriteLine("A");
}
else
{
    Console.WriteLine("B");
}
```
 > B - тк else выполняется в том случае, если первое условие ложно

```cs
bool a = true;
if (a)
{
    a = false;
}
else
{
    Console.WriteLine("B");
}
```
 > ничего не напечатается, тк перезаписанное значение в первом условии уже ни на что не влияет

```cs
F();

static void F()
{
    if (true)
    {
        return;
    }
    else
    {
        Console.WriteLine("B");
    }
}
```
 > ничего не напечатается, выполнится только условие с тру

```cs
if (true)
    Console.WriteLine("A");
else
    Console.WriteLine("B");
Console.WriteLine("C");
```
 > A(true) и C(не в if)

```cs
if (true)
    Console.WriteLine("A");
else
{
    Console.WriteLine("B");
}
```
 > напечатается А

*Как обычно записывают данный код, используя цепочку if-else?*
```cs
if (a)
{
    Console.WriteLine("A");
}
else
{
    if (b)
    {
        Console.WriteLine("B");
    }
    else
    {
        if (c)
        {
            Console.WriteLine("C");
        }
    }
}
```
```
if (a)
{
    Console.WriteLine("A");
}
else if (b)
{
    Console.WriteLine("B");
}
else if (c)
{
    Console.WriteLine("C");
}
```

Попытайтесь представить данный код как цепочку if-else, семантически ему идентичную. Как сделать этот код через early return / guard clause?
```cs
if (a)
{
    Console.WriteLine("A");
}
else
{
    Console.WriteLine("After A");

    if (b)
    {
        Console.WriteLine("B");
    }
    else
    {
        Console.WriteLine("After B");

        if (c)
        {
            Console.WriteLine("C");
        }
        else
        {
            Console.WriteLine("After C");
        }
    }
}
```
цепочка
```
if (a)
{
    Console.WriteLine("A");
}
else if (b)
{
    Console.WriteLine("After A");
    Console.WriteLine("B");
}
else if (c)
{
    Console.WriteLine("After A");
    Console.WriteLine("After B");
    Console.WriteLine("C");
}
else
{
    Console.WriteLine("After A");
    Console.WriteLine("After B");
    Console.WriteLine("After C");
}
```
c применением early return / guard clause
```
static void SendWelcomeEmail(User user)
{
    // Можно блоком разграничить контракт 
    // (необходимые условия для выполнения основного действия), 
    // или вынести его в свою функцию.
    {
        // Соблюдена локальность: условия рядом с их обработкой.
        if (user == null)
        {
            Console.WriteLine("User not found.");
            return;
        }
    
        if (!user.IsActive)
        {
            Console.WriteLine("User is not active.");
            return;
        }
    
        if (!user.EmailConfirmed)
        {
            Console.WriteLine("Email not confirmed.");
            return;
        }
    }

    // Основной код находится после всех проверок, а не в середине.
    Console.WriteLine($"Sending email to {user.Email}");
}
```


```cs
int i = 0;
while (true)
{
   if (i == 4)
   {
       Console.WriteLine("ERROR: Should not happen");
       break;
   }
   if (i == 3)
   {
       Console.WriteLine("Exit");
       break;
   }
   if (i == 0)
   {
       Console.WriteLine("Increase by 2 on first iter");
       i += 2;
       continue;
   }

   Console.WriteLine("Increase by 1 normally");
   i++;
}
```
 > Increase by 2 on first iter - Increase by 1 normally - Exit

 > Что делают break и continue? break прекращает выполнение цикла (переходит на первую инструкцию после цикла), continue переходит в начало цикла (дальнейшие инструкции из тела цикла не выполняются для этой итерации).

```cs
static int F()
{
    while (true)
    {
       if (true)
       {
           return 0;
       }
       break;
    }
    return 1;
}
```
 > При выполнении return 0, прерывается не только цикл, но и последующее выполнение оставшегося кода функции, break и return 1 никогда не выполнятся.
