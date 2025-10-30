#задание 1.3
name = input("Введите ваше имя : ")
print("Привет, " + name)

#задание 1.4, 1.5
int = 1
print("Число", int, "принадлежит ",type(int))
float = 1.5
print("Число", float, "принадлежит",type(float))
str = "Казнить нельзя помиловать"
print("Это однострочное предложение :", str, "принадлежит",type(str))
longstr = """Казнить
нельзя
помиловать"""
print("Это многострочное предложение :", longstr, "принадлежит",type(longstr))

#задание 1.6
string = "строка, длину которой нужно определить :с" #41
print(len(string))

#задание 1.7
print(string.upper())

#задание 1.8
print(string[8:13])

#задание 9
txt = "More results from text..."
substr = txt[4:12]
print(substr)
print(substr.strip())
#в 30 строке выведется _results
#в 31 строке лишние пробелы уберутся из-за метода strip

#задание 10
txt = "More results from text..."
print(txt.split())
#метод split в этом случае разбивает строку на подстроки

#задание 11
age = 36
txt = "My name is Mary, and I am {}"
print(txt.format(age))
#на месте фигурных скобок выведется число - возраст



