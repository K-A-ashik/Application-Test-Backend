# Application-Test-Backend
###### This projects will create, update and delete order's through API from backend.

## Backend :
###### Backend is writen using below technical specifications :  
PHP version 7.4

- I have implemented **RESTfull** API in the backend.
- To **GET, ADD, UPDATE and DELETE** Order's.
- In the root project you will find a file named **orders.php**.
- Order.php act as an entry point to the Backend.
- My backend endpoint is **[http://localhost/backend-main/orders](http://localhost/backend-main/orders)**.
- Please adjust your **.htaccess** file accordingly.
- For all the operations, the End Point is same. But differentiated based on the **HTTP Method**.
- I have writtern explainatory comments in all the files.
- I have used **OOPS** concept while developing this API.

## Backend Test description 
As requested I have implented testing using **Codeception** Tool.
And I have implemented REST API testing.

- In project root you will find a folder named **test**. Inside that all the test cases are written.
- Open ```tests/api/CreateOrderCest.php``` file.
- You will find Four functions written for testing Four End Points.

## Testing commands
Run ```php vendor/bin/codecept run api``` to run all the test cases.