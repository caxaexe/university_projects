# задание 3a - определить три функции : добавление товара в список, удаление товара и вывод всех элементов

def addProduct (products, name):
  products.append(name)
  print("Товар '", name, "' добавлен в список.")

def removeProduct (products, index):
  if index in products:
      products.remove(index)
      print("Товар '", index, "' удалён из списка.")
  else:
      print("Данного товара нет в списке.")

def printProduct (products):
  print("Список товаров :", products)



def menu():
  products = []

# задание 3b - создать меню из 4 опций
  
  while True :
    print("\n\tМеню :")
    print("1. Добавить товар")
    print("2. Удалить товар")
    print("3. Вывести список товаров")
    print("4. Выход")
    choice = int(input("\nВведите номер пункта меню: "))


# задание 3c - создать цикл, в котором пользователь вводит пункт меню и программа выводит определенную функцию
    
    if choice == 1 :
      name = input("\nВведите название товара: ")
      addProduct(products, name)
    elif choice == 2 :
      index = input("\nВведите название товара, который хотите удалить: ")
      removeProduct(products, index)
    elif choice == 3 :
      printProduct(products)
    elif choice == 4 :
      break
    else :
      print("\nОшибка. Повторите попытку.")
