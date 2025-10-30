import re

# создать класс Emlpoyee, все переменные приаватные,  а методы calculateAge - публичный и calculateSalary - приватный, в методах установить pass
class Employee:
    def __init__(self, name, phone, birth_date, email, specialty):
        self._name = name
        self._phone = phone
        self._birth_date = birth_date
        self._email = email
        self._specialty = specialty

    def calculateAge(self):
        pass

    def _calculateSalary(self):
        pass

# создать сеттеры и геттеры для установения значений переменным и для выдачи этих значений, использовать функцию property() и декораторы, объяснить разницу
# функция используется для более сложных свойств, декоратор наоборот, оба вызывают геттеры и сеттеры

    @property
    def name(self): # get_name
        return self._name

    @name.setter
    def name(self, value): # set_name
        if re.match("^[А-Яа-яЁёA-Za-z]+$", value):
            self._name = value
        else:
            print("Неверный формат. Имя должно состоять только из букв.")

    # name = property(get_name, set_name)

    @property
    def phone(self): # get_phone
        return self._phone

    @phone.setter
    def phone(self, value): # set_phone
        if re.match("^\\+373\d{8}$", value):
            self._phone = value
        else:
            print("Неверный формат. Соблюдайте следующие условия ввода : +373xxxxxxxx.")

    # phone = property(get_phone, set_phone)

    @property
    def birthdate(self): # get_birthdate
        return self._birth_date

    @birthdate.setter
    def birthdate(self, value): # set_birthdate
        if re.match("^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[0-2])\.(19[6-9][0-9]|200[0-7])$", value):
            self._birth_date = value
        else:
            print("Неверный формат. Соблюдайте следующие условия ввода: дд.мм.гггг, допустимые года между 1960 и 2007 (других в лес).")

    # birthdate = property(get_birthdate, set_birthdate)

    @property
    def email(self): # get_email
        return self._email

    @email.setter
    def email(self, value): # set_email
        if re.match("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}$", value):
            self._email = value
        else:
            print("Невереный формат. Используйте действительный адрес электронной почты.")

    # email = property(get_email, set_email)

    @property
    def specialty(self): # get_specialty
        return self._specialty

    @specialty.setter
    def specialty(self, value): # set_specialty
        if re.match("^[А-Яа-яЁёA-Za-z -]{4,20}$", value):
            self._specialty = value
        else:
            print("Неверный формат. Название специальности должно содержать только буквы от 4 до 20.")

    # specialty = property(get_specialty, set_specialty)


# определить производные классы HourlyEmployee и SalaryEmployee
class HourlyEmployee(Employee):
    def __init__(self, name, phone, birth_date, email, specialty, hoursworked, hourlyrate):
        super().__init__(name, phone, birth_date, email, specialty) # super позволяет наследовать метод из родительского класса
        self._hoursworked = hoursworked
        self._hourlyrate = hourlyrate

# подсчитать зарплату как количество проработанных часов, умноженных на оплату за час
    def calculateSalary(self):
        return self._hoursworked * self._hourlyrate


class SalaryEmployee(Employee):
    def __init__(self, name, phone, birth_date, email, specialty, monthlysalary):
        super().__init__(name, phone, birth_date, email, specialty)
        self._monthlysalary = monthlysalary

# фиксированная месячная зарплата
    def calculateSalary(self):
        return self._monthlysalary

