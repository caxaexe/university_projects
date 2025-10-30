# Анализ

**Что произойдет в программе? Что выведется?**
  
```cs
bool a = true;
Console.WriteLine(a);
```
> текстовое тру

```cs
bool a = null;
```
> не скомпилируется, бул не может содержать нулл

```cs
bool a = 1 == 2;
Console.WriteLine(a);
```
> a = false

```cs
int x = 3;
int y = 4;
bool b = x == y;
Console.WriteLine(b);
```
> b = false

```cs
int x = 3;
int y = 4;
bool b = x * 2 == y + 4;
Console.WriteLine(b);
```
> b = false, 6 != 8

```cs
bool a = 1 > 2;
a = 3 == 3;
Console.WriteLine(a);
```
> a перезапишется с false на true

```cs
bool a = true;
F(a);

static void F(bool x)
{
    Console.WriteLine(x);
}
```
> текстовое true

```cs
F(5 > 3);

static void F(bool flag)
{
    Console.WriteLine(flag);
}
```
> true тк верное выражение

```cs
bool result = F();
Console.WriteLine(result);

static bool F()
{
    return true;
}
```
> true

```cs
bool result = IsGreater(5, 3);
Console.WriteLine(result);

static bool IsGreater(int a, int b)
{
    return a > b;
}
```
> true

```cs
bool a = true;
bool b = false;
bool c = a == b;
Console.WriteLine(c);
```
> c = false, true != false

```cs
bool a = false;
bool b = !a;
Console.WriteLine(b);
```
> true

```cs
bool a = true;
bool b = false;
bool c = a && b;
Console.WriteLine(c);
```
> c = false, логическое true и false = false

```cs
bool result = A() && B();
 
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
> A B, result = true

```cs
bool result = A() && B();
 
static bool A()
{
    Console.WriteLine("A");
    return true;
}
 
static bool B()
{
    Console.WriteLine("B");
    return false;
}
```
> A B, result = false

```cs
bool result = A() && B();

static bool A()
{
    Console.WriteLine("A");
    return false;
}

static bool B()
{
    Console.WriteLine("B");
    return true;
}
```
> A тк первое false и второе не выполнится, result = false

```cs
bool result = A() || B();

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
> A, result = true
