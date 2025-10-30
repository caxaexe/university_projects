from lab6 import Employee, HourlyEmployee, SalaryEmployee


emp1 = Employee("Джон Доу", "+37312345678", "01.01.1980", "john.doe@example.com", "Инженер")
emp2 = Employee("Алиса Смит", "+37398765432", "15.05.1990", "alice.smith@example.com", "Менеджер")


hourlyemp1 = HourlyEmployee("Боб Джонсон", "+37355556789", "20.12.1985", "bob.johnson@example.com", "Техник", 40, 15)
hourlyemp2 = HourlyEmployee("Ива Браун", "+37355511223", "10.10.1975", "eve.brown@example.com", "Помощник", 30, 20)


salaryemp1 = SalaryEmployee("Дэвид Ли", "+37355544332", "05.06.1965", "david.lee@example.com", "Менеджер", 5000)
salaryemp2 = SalaryEmployee("Грейс Тейлор", "+37355566778", "25.09.1970", "grace.taylor@example.com", "Программист", 6000)


print("Зарплата следующих сотрудников:")
print(hourlyemp1.name, ":", hourlyemp1.calculateSalary())
print(hourlyemp2.name, ":", hourlyemp2.calculateSalary())

print("\nФиксированная месячная зарплата следующих сотрудников:")
print(salaryemp1.name, ":", salaryemp1.calculateSalary())
print(salaryemp2.name, ":", salaryemp2.calculateSalary())
