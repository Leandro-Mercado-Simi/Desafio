/*------------------- 1 ---------------------*/
/*Crear una consulta que devuelva la cantidad de customers que se encuentran en cada country. 
La respuesta debe contener columnas como: states y quantities.*/

select country, state as states, count(*) as quantities from customers group by country, state;

/*------------------- 2 ---------------------*/
/*
Crear una consulta que devuelva la sumatoria de amount de los payments de cada uno de los customers de country=’USA’
y state=’Boston’ entre las fechas (paymentDate) 01/01/2004 y 01/06/2004. Además, la respuesta debe estar ordenada 
de forma descendente por totalAmount. La respuesta debe contener columnas como: customerNumber, 
customerName, totalAmount y creditLimit.
*/
select c.customerNumber, customerName, sum(amount) as totalAmount, creditLimit from customers c inner join payments p 
on c.customerNumber = p.customerNumber
where c.country='USA' and c.city='BOSTON' and paymentDate between '2004/01/01' and '2004/05/01' 
group by c.customerNumber
order by totalAmount DESC;

/*------------------- 3 ---------------------*/
/*
Crear una consulta que devuelva ítems que pertenecen a la orderNumber=10313. La respuesta debe contener columnas 
como: productCode, quantityOrdered, priceEach, orderLineNumber, productName, productLine y textDescription.
*/

SELECT 
od.orderNumber, 
od.productCode, 
od.quantityOrdered, 
od.priceEach, 
od.orderLineNumber, 
pr.productName, 
pr.productLine, 
prl.textDescription 
FROM orderdetails AS od
INNER JOIN orders AS ord ON od.orderNumber = ord.orderNumber
INNER JOIN products AS pr ON od.productCode = pr.productCode
INNER JOIN productlines AS prl ON pr.productline = prl.productline
WHERE od.orderNumber = 10313;

/*------------------- 4 ---------------------*/
/*
Crear una consulta que devuelva un listado de jefes y empleados que le reportan a él. La respuesta debe contener 
columnas como: employeeNumber, firstName y lastName (en una sola columna), email, jobTitle, officeCode y 
subordinate (que contiene una cadena de texto separada por coma de todos los employeeNumber que se encuentran 
a su cargo por medio del campo reportsTo).
*/
SELECT CONCAT(employeeNumber, " ", firstName , " ", lastName) AS info, 
email, 
jobTitle, 
officeCode, 
reportsTo as subordinate from employees;
