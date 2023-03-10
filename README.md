This laravel develop by ArminEyvazi as a pre interview test for paystar.ir

_____
The Design Patterns used in This app:

1-Strategy Pattern 

2-Repository Pattern
_____

There is a simple schedule in the app to handle if the user payed invoice

after 2 hours invoice will deleted automatically And my target is created a job to handle 

send email to user "if you forget pay invoice please back and pay it ,we keep your invoice for a hours"

To run this laravel App please run:

php artisan key:gen

1-copy .env.example => .env 

2-write your database connection

3-composer install && composer update

4-php artisan migrate

5-npm install 
5-npm run dev

6-please just run at port:8000 


if you are run in linux befor run

php artisan serve check your open port 

with: sudo lsof -t -i:8000

6-php artisan serve 

7-php artisan test 

8-php artisan migrate:fresh 

9-php artisan migrate --seed 

user:paystar@gmail.com

password:password.


Documnet:https://docs.paystar.ir/docs/tutorial-basics/IPG/
