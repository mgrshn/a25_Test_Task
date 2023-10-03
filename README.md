Test task for A25:
Реализовать программу+API на Laravel или UmiCMS, которая считает зарплату сотрудников. Сотрудники работают на почасовой основе. Ежедневно они присылают кол-во отработанных часов. Выплата зарплаты сотрудникам осуществляется в произвольный момент времени по запросу. В дальнейшем предполагается введение оплаты на основе оклада с фиксированной суммой в неделю (пока не требует разработки).

Программа через API должна иметь возможность:
- Создавать сотрудников
- Принимать от сотрудника транзакции с кол-вом отработанных за день часов
- Выводить суммы зарплат, которые еще не были выплачены (сотрудник => сумма)
- Выплачивать всю накопившуюся сумму по запросу

Следует покрыть все приложение тестами (или, как минимум, предложить, каким образом это может быть реализовано). В качестве суммы за час возьмите любое число.

UseCases:
Создание сотрудника: <email>, <password>. Пароль в базе должен быть зашифрован.
Принятие транзакции: <employee_id> <hours>. Создает запись в базе.
Вывод суммы: входных данных нет; на выходе json формата `[ { employee_id : сумма выплат } ]`
Выплата всей накопившейся суммы: входных и выходных данных нет, все транзакции становятся погашенными

Выполненное задание нужно опубликовать в GIT и прислать ссылку. GIT должен содержать как минимум 2 коммита: начальный (инициализация приложения) и конечный (выполненное задание).


How to start:
Install composer->
Clone repository->
U need to configure .env file (connect ur db)->
Make migrations with command: php artisan migrate->
Use ready to use it.


Endpoints:
1. POST api/employees/new
Email and password are required.
Desc:
Creating new employee if input data are valid end response with employee_id. Else show validation errors.

2. POST api/personal-access-tokens
Email and password of the registered employee are required.
Desc:
Creating new personal-access-token for registered employee if input data are valid. Else show validation errors.
ATTENTION: you should save the generated token for later validation of your requests.

3. POST api/transactions/new
Employee_id and working hours are required. You also must send a personal-access-token to this endpoint. Send it as a Header Authorization: Bearer <Your token>.
Desc:
Creating new working transaction if input data and personal-access-token are valid. Else show validation|authentication errors.

4. GET api/transactions
Sends json with unpayed transactions like [{ employee_id : amount of payments }].

5. PUT api/transactions
Changes transactions status from unpayed to payed.