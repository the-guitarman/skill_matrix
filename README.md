# Skill-Matrix

This is an inhouse skill matrix project with an LDAP connection to your 
user administration system.

## Installation

### Requirements

- git
- php72
- node >= v10.1.0
- npm >= v6.01

### Git

Clone oder deploy the project and move into the project folder:

```bash
$ git clone git@??? skill_matrix
$ cd skill_matrix
```

### Configuration

Generate the basic config file and generate an application key:

```bash
$ cp .env.example .env
$ php artisan key:generate
```

Edit `.env` to your needs
- local and test environment:
  - `APP_ENV=local`
  - `APP_DEBUG=true`
  - `APP_LOG=single`
  - `APP_LOG_LEVEL=debug`
  - `APP_URL=http://localhost`
  - `SESSION_SECURE_COOKIE=false`
- production environment:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_LOG=daily`
  - `APP_LOG_LEVEL=error`
  - `APP_URL=https://www.PROJEKT-DOMAIN.de`
  - `SESSION_SECURE_COOKIE=true`

#### Composer + Node Packages

Install all composer and node packages:

```bash
# in Development- und Test-Umgebung
$ composer install
$ npm install
$ npm run dev

$ # in Produktions-Umgebung
$ composer install --no-dev 
$ npm install
$ npm run prod
```

#### MySQL

Run these sqls:

- create the app database (developer and production systems)
    ```
    $ mysql -r root -p
    mysql> CREATE DATABASE IF NOT EXISTS skill_matrix DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
    ```

- create the app test database (depeloper and CI systems)
    ```
    $ mysql -r root -p
    mysql> CREATE DATABASE IF NOT EXISTS skill_matrix_test DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
    ```

- create db user (developer and production systems)
    ```
    mysql> CREATE USER 'skill_matrix'@'localhost' IDENTIFIED BY 'my-secret';
    mysql> GRANT ALL PRIVILEGES ON skill_matrix.* TO 'skill_matrix'@'localhost';
    mysql> quit
    ```
  - edit file `.env`: 
    - `DB_DATABASE=skill_matrix`
    - `DB_USERNAME=skill_matrix`
    - `DB_PASSWORD=my-secret`

- create db user (depeloper and CI systems)
    ```
    mysql> CREATE USER 'skill_matrix_test'@'localhost' IDENTIFIED BY 'skill_matrix_test';
    mysql> GRANT ALL PRIVILEGES ON skill_matrix_test.* TO 'skill_matrix_test'@'localhost';
    mysql> quit
    ```
  - edit file `.env.testing`:  
    - `DB_DATABASE=skill_matrix_test`
    - `DB_USERNAME=skill_matrix_test`
    - `DB_PASSWORD=skill_matrix_test`
  
- Migrationen ausführen

    ```bash
    $ php artisan migrate
    ```

#### LDAP

Edit `config/ldap.php` for `production` environment to your needs. Dev environment
uses the `default` configuration. Test environment uses a mock ldap object.

## Translations

### Linux

If the are problems, you may need to check the installed languages at your system: 

```bash
$ locale -a # lists all installed languages
$ sudo locale-gen de_DE.UTF-8 # installs german
$ sudo dpkg-reconfigure locales # reconfigures all installed languages
```

## Tips and Tools

### App-Name

To change the app name you may run:

```bash
$ php artisan app:name <neuer App-Name>
```

### Maintenance Mode

Switch the app into maintenance mode and back. In maintenance mode no one can use the app, 
but the users are faced with a nice information. 

Start maintenance mode:

```bash
$ php artisan down
```

Stop maintenance mode:

```bash
$ php artisan up
```

### List all routes

```bash
$ php artisan route:list
```

### Start console

```bash
$ php artisan tinker

>>> // init the app
>>> namespace App\Models;
>>> // show the first user
>>> User::first()
=> App\Models\User {#748...}
>>> exit
=> Exit:  Goodbye

$
```

### Start the server

Now you should be able to use Apache, Nginx or ... See [Web Server Configuration](https://laravel.com/docs/5.6#web-server-configuration).
For development you better start the build-in webserver:

```bash
$ php artisan serve
Laravel ... server started: <http://127.0.0.1:8000>
```

Type `http://localhost:8000` in your Browser.
Terminate the server with `Ctrl + c`.

### Run the tests

Therefore the file `.env.testing` is used: 

```bash
$ ./vendor/bin/phpunit

### empty the log file

```bash
$ cat /dev/null > storage/logs/laravel.log
```

### Drop and create the database

May be there is something totally wrong with your database and you want the 
start with a new clean database migration.

```bash
$ php artisan migrate:fresh --seed
```

## TODO

- [ ] SkillGroup Model
- [ ] Skill Model
- [ ] Model associations
- [ ] remove an account with all skills
- [ ] own skills are editable only
- [ ] overview about all inhouse skills