# blog
A simple blog project with VueJS and Lumen (PHP)

### Dependencies
- npm >= 6.9.0 ```sudo apt-get install nodejs```
- MySQL database server
-- There are likely multiple options that will work. I used the mysql-server ubuntu package:
```
sudo apt-get install mysql-server
sudo systemctl start mysql
```
- PHP >= 7.2
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- PHP CLI Extension
- On linux, you can use something like the following to install php with all of the required extensions:
```
sudo apt-get install php php-cli php-mbstring php-pdo php-common
```

### Installation
- First install the dependencies listed above if you do not already have them installed already.
- Clone the repository and cd into the 'Blog' directory in the terminal
- Setup the database (using your mysql username and password - the default username is root, leave password blank):
```
mysql -u username -p
CREATE DATABASE blog;
exit;
mysql -u username -p blog < blog.sql
```
- Install Javascript packages using `npm install`
- Go into the 'api' directory in the terminal and run `php composer.phar install` to install PHP packages
- Open the file api/.env.example. Update the DB_USERNAME and DB_PASSWORD parameters to match your database credentials.
- You can also set the APP_KEY and JWT_secret parameters to random strings, but it should work fine without doing this (but is insecure for production). Finally, save .env.example as just .env.

### Run the program
- Run the API:
```
cd .../Blog/api/public
php -S localhost:8888
```
- Run the Front-End (in a new terminal window):
```
cd .../Blog
npm run serve
```
- Open localhost:8080
- The hard-coded username/password is "user"/"password"
