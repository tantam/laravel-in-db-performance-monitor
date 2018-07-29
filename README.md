# Laravel In DB Performance Monitor
Monitor your laravel application performance by logging requests in your database then analyze it. The log includes request parameters, actions, SQL queries and errors beside that you can know the requests with raw SQL queries.

### Requirements
    Laravel >=5.0

### Installation

1- Run `composer require asamir/laravel-in-db-performance-monitor`

2- Run `php artisan in-db-performance-monitor:init`

3- Add and configure the **inDbMonitorConn** connection in **config/database.php file**

    'connections' => [
        //...
		'inDbMonitorConn' => [
            'driver' => 'mysql',
            'host' => env('IN_DB_MONITOR_DB_HOST', ''),
            'port' => env('IN_DB_MONITOR_DB_PORT', '3306'),
            'database' => env('IN_DB_MONITOR_DB_DB', ''),
            'username' => env('IN_DB_MONITOR_DB_USERNAME', ''),
            'password' => env('IN_DB_MONITOR_DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => 'asamir_',
            'strict' => false,
            'engine' => null,
        ],
	]

4- Add the this middleware in **app/Http/Kernel.php** file 

    protected $middleware = [
        //...
        \ASamir\InDbPerformanceMonitor\InDbPerformanceMonitorMiddleware::class
    ];

5- Add this line in **app/Exceptions/Handler.php** => **public function report(Exception $exception)**

    //...
    \ASamir\InDbPerformanceMonitor\LogErrors::inDbLogError($exception);

6- Run `php artisan migrate`

7- For **laravel < 5.5** add this provider in **config/app.php**

	'providers' => [
		\\...
		\ASamir\InDbPerformanceMonitor\InDbPerformanceMonitorProvider::class,
	]

**Now you can make requests and monitor it at /admin-monitor**

## Documentation
### Configurations

- The **inDbMonitorConn** connection is where the requests logs will be set, so it can be isolated in another database away from the application database or you can set it in the same database no problem.

- The package creates **inDbPerformanceMonitor.php** file in your config folder which has options
	- **IN\_DB\_MONITOR\_WORK** => If true the package will work and log the comming requests (default = true)
	
	- **IN\_DB\_MONITOR\_TOKEN** => Holds the admin-monitor passowrd token (default password = monitor)

	- **IN\_DB\_MONITOR\_NEGLICT\_START\_WITH** => Array of routes to neglict from log (e.x. /test so any request start with /test will not be  logged in the DB)
	
	- **IN\_DB\_MONITOR\_LOG\_PACKAGE\_QUERIES** => If true log queries made by the package in your laravel log (default = false)
	
- **Hint:** You will find the package env variables created in your .env file

### Demo Screen shots

1- **/admin-monitor** => login page (default password = monitor)
![alt 01-Login.png](screenshots/01-Login.png)

2- **/admin-monitor/requests** => list all logged requests

- **Search box:** search in (Action, Route, URL, Session ID, Type[POST,GET,...], Archive Tag) and in (Error Message, Error File) in case of checked **Has errors** check box

- **Not Elequent:** I mean free queries like `\DB::select(\DB::raw('select * from applicants where id = 10'));` this queries are vulnerable to SQL injection attacks. The number in red color represents the number of free queries in the request. 

- **Archive button:** Give tag => date('YmdHis') to requests with archive tag = 0 

- In the table you will find number of queries, queries total time, and request execution time.

- You can filter by creation date from => to

- If you hovered the Error label, it will display the error message, file, and line number.

- The scenario of the requests in the image below
	- Create applicant 
	- Edit Applicant
	- Search Applicant
	- Delete Applicant

![alt 03-Requests-edited.png](screenshots/03-Requests-edited.png)

- The scenario of the requests in the image below
	- Archive old requests so the appear with tag 20180729010530 and the others with tag 0 => So we can filter them 
	- Make request to **/add/{a}/{b}** which adds two integers. I passed to it 4 and 5+, so it throwed exception because 5+ is not integer.   
	- Make request to **/api/applicants** which returns json response. Hint: in this request the session id changes because it is not under the web middleware.

![alt 08-Requests-JSON-ERROR.png](screenshots/08-Requests-JSON-ERROR.png)

3- **/admin-monitor/request/{id}** => Shows the request details, queries, and error beside that it allows you to rerun the queries.


	Hint => You can quickly get your last request by Latest By Session ID Or IP
	
	By Session ID => Get last request made by your session id
	
	By IP => Get last request made by your ip (requests made by api or routes
	which don't use the web middleware will generate different session id at
	every request, so you can get them by your IP).



- Request of create applicant
![alt 04-Request-POST.png](screenshots/04-Request-POST.png)

![alt 05-Request-POST-Run.png](screenshots/05-Request-POST-Run.png)

- Request of search applicants
![alt 04-Request-POST.png](screenshots/04-Request-POST.png)

![alt 07-Request-GET-Run.png](screenshots/07-Request-GET-Run.png)

- Request of error add
![alt 09-Error-v2.png](screenshots/09-Error-v2.png)

4- **/admin-monitor/statistics-report** => Statistics report about the requests performance
![alt 10-Statistics Report.png](screenshots/10-Statistics Report.png)

5- **/admin-monitor/changePassword** => To change monitor panel password
![alt 11-Change_Password.png](screenshots/11-Change_Password.png)