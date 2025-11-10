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

Что значит функциональное и семантическое?


```cs
if (1)
{
    Console.WriteLine("Hello");
}
```

```cs
if (false)
{
    Console.WriteLine("A");
    Console.WriteLine("B");
}
```

```cs
if (false)
    Console.WriteLine("A");
    Console.WriteLine("B");
```

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

```cs
if (true)
    Console.WriteLine("A");
else
    Console.WriteLine("B");
Console.WriteLine("C");
```

```cs
if (true)
    Console.WriteLine("A");
else
{
    Console.WriteLine("B");
}
```

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

   // Implicit continue.
   // continue;
}
```

Что делают break и continue

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
