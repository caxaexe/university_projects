# Анализ

**Что будет, если пользователь не введет строковое представление числа?**
  
```cs
string a = Console.ReadLine()!;
int b = int.Parse(a);
Console.WriteLine(b);
```
> пользователь будет наказан - жоски ексепшн

```cs
string a = Console.ReadLine()!;
int b;
bool success = int.TryParse(a, out b);
Console.WriteLine(success);
Console.WriteLine(b);
```
> succes = false, b = 0
  
  
**Как работает этот код? Что выведется в консоль?**

```cs
int a = 0;
F(a);
Console.WriteLine(a);

static void F(int a)
{
    a = 1;
}
```
> a = 0 тк а внутри функции локальная(хранится во временной ячейке памяти)

```cs

int a = 0;
F(out a);
Console.WriteLine(a);

static void F(out int b)
{
    b = 1;
}
```
> a = 1 тк по ссылке значение из фукнции передается в глобал переменную

```cs
int a = 0;
F(out a);
Console.WriteLine(a);

static void F(out int a)
{
    a = 1;
}
```
> a = 1, перменные за функцией и внутри независимы

```cs
A a;
F(out a);
Console.WriteLine(a.f1);
Console.WriteLine(a.f2);

static void F(out int b)
{
    b = 1;
}

struct A
{
    public int f1;
    public int f2;
}
```
> error CS1503: Аргумент 1: не удается преобразовать из "out A" в "out int".  
warning CS0649: Полю "A.f1" нигде не присваивается значение, поэтому оно всегда будет иметь значение по умолчанию 0.  
warning CS0649: Полю "A.f2" нигде не присваивается значение, поэтому оно всегда будет иметь значение по умолчанию 0.

```cs
A a;
F(out a);
Console.WriteLine(a.f1);
Console.WriteLine(a.f2);

static void F(out int a)
{
    a = 1;
}

struct A
{
    public int f1;
    public int f2;
}
```
> то же самое что и сверху, смена переменной в функции не зарешала

```cs
A a;
F(out a);
Console.WriteLine(a.f1);
Console.WriteLine(a.f2);

static void F(out int a)
{
    a = 1;
}

struct A
{
    public int f1;
    public int f2;
}
```
> не скомпилируется - не удается преобразовать тип int в A

```cs
A a;
F(out a);
Console.WriteLine(a.f1);
Console.WriteLine(a.f2);

static void F(out A b)
{
    b.f1 = 1;
}

struct A
{
    public int f1;
    public int f2;
}
```
> а не полностью инициализирована - нет b.f2

```cs
A a;
F(out a);
Console.WriteLine(a.f1);
Console.WriteLine(a.f2);

static void F(out A b)
{
    b.f1 = 1;
    b.f2 = 2;
}

struct A
{
    public int f1;
    public int f2;
}
```
> a.f1 = 1, a.f2 = 2

```cs
A a;
F(out a);
Console.WriteLine(a.f1);
Console.WriteLine(a.f2);

static void F(out A b)
{
    b = new()
    {
        f1 = 1,
        f2 = 2,
    };
}

struct A
{
    public int f1;
    public int f2;
}
```
> a.f1 = 1, a.f2 = 2

```cs
int a = 0;
F(out a);
Console.WriteLine(a);

static int F(out int b)
{
    b = 1;
    return 2;
}
```
> возвращаемое значение нигде не сохраняется так шо a = 1

```cs

int a = 0;
int b = 0;
b = F(out a);
Console.WriteLine(a);
Console.WriteLine(b);

static int F(out int c)
{
    c = 1;
    return 2;
}
```
> a = 1, b = 2 так как в перменную b сохраняем возвр значение

```cs
int a;
int b;
F(out a, out b);
Console.WriteLine(a);
Console.WriteLine(b);

static void F(out int c, out int d)
{
    c = 1;
    d = 2;
}
```
> a = 1, b = 2
  
  
*Создайте программу, где вводятся 3 числа больше 2, считается их произведение и выводится на экран.
Если пользователь вводит не число, или недопустимое значение, запрашивайте снова.
Используйте бесконечный цикл для ввода корректных чисел.*
