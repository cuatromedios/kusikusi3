# KusiKusi PHP

## Introduction
KusiKusi _(spider in Quechuan)_ is a framework for creating **API-first applications based on hierarchical data**, like the data found on most websites (home / section / page). KusiKusi can be used as the backend for web sites, web applications, mobile apps or platforms containing all of them. KusiKusi also has components to build server generated websites.

#### Objetive

Kusikusi has three main objectives for three different user profiles:
* **Developers:** An easy way to construct applications based on hierarchical data, from websites, to mobile app backends and other platforms.
* Developers/Designers **Customers** To receive a content manager for his/her web or mobile application easy to understand and use

#### Related projects

KusiKusi PHP has other related projects:

* **[KusiKusi PHP](https://github.com/cuatromedios/kusikusi):** This project,as an starting point for new projects
* **[KusiKusi PHP Kernel](https://github.com/cuatromedios/kusikusi-php-kernel)** The core engine of KusiKusi
* **[KusiKusi Front-End](https://github.com/cuatromedios/kusikusi-frontend):** Generic UI to interact with the API. e.g CMS

#### Technical info
KusiKusi PHP is based on [Lumen](https://lumen.laravel.com)

##### Requirements
 * [PHP 7+](https://www.php.net)
 * [MySQL 5.6](https://www.mysql.com/) or [MariaDB 10.0.5](https://mariadb.com/).
 * [Composer](https://getcomposer.org/download/)
 * [Node and NPM](https://nodejs.org) To speed up frontend developemnt using Webpack's watch functionality, css preprocessors and other goodies. 


## The KusiKusi way

The basic piece of information in KusiKusi is named an **Entity**, an entity can represent any piece of information in the app or website, from a page, section or home, to a user or image. Each entity is part of a hierarchical structure or the content tree: May have one parent and zero or any number of children.

## Installation

Use composer to create-project command in your terminal to download and install dependencies:

```shell script
$ composer create-project cuatromedios/kusikusi your-app-name -s dev
$ cd your-app-name
```

#### Create your database 

Create a database in your MySQL or MariaDB server, and setup user credentials to use.

#### Configure the Environment

Open for edit the `.env` file

1. Set the APP_NAME of your application
1. APP_ENV variable is set to local, should be changed to production once the project is in production mode
1. APP_DEBUG is set by default to true, it also should be set to false once in production mode
1. It is very important to set APP_KEY to a random 32 characters string. You can use the command to automatically add it to .env file `php artisan key:generate` (if using this command, please be sure to have saved and closed the `.env` file before if you made editions)
1. Use one of the defined time zones where the server is going to be running according to the table in https://www.php.net/manual/en/timezones.php
2. Set APP_URL where the app will be running
1. Set the variables DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD in order to your application to connect to the database server.


#### Migrations and Seed
Kusikusi as a Lumen application comes with migrations to set up the database and seeds to populate the tables with a default user and base nodes.
```shell script
$ php artisan migrate --seed
```

The base seeder will create an admin user, with admin@example.com as email and admin as password. You can change the password using 

```shell script
$ php artisan kk:set-password admin@example.com theNewPassword
```

#### Run the server

You can use any webserver/PHP installation to run your application, for example MAMP, WAMP, or a production ready LAMP / LEMP environment. Just be sure the public folder is set to the public/ folder of the app. 

Kusikusi needs to rewrite all calls to to not found files to either index.php or com/index.html, this way it can send the correct information to the user. Webservers like Apache may use the included `.htaccess`. Ngingx server needs a special configuration, you can use the included `nginx-site-example.conf` as a reference to compliment that configuration. but others like the PHP built-in  webserver may need a router. In this case you can also use the phprouter.php script:

```shell script
$ php -S 127.0.0.1:8000 -t public /full/path/to/phprouter.php
```

#### Website development

If your project is a website (instead of just an API for example), you may need to implement views and css files. For a better development experience you can use Laravel Mix (https://laravel.com/docs/master/mix). The use of Mix may vary depending on the CSS framework used or not used:

##### Not using any css framework
If you are not going to use any css framework you can still use the CSS preprocessor of your choice: Sass, Less or Stylus. By default Kusikusi is configured to use Stylus but can be changed easily.

1. Install node dependencies
   ```shell script
   $ npm install
   ``` 
1. Be sure the server is running
1. Look at the file `webpack.mix.js` or edit it if needed: the `mix.browserSync()` line should point to the address the webserver is running, for example `127.0.0.1:8000` Other functions you can find there is resources/img files are copied to public path, Javascript files are packed and Stylus files are transpiled to css. Here you can, for example, change Stylus for Sass files if you want.
1. To develop and watch for file changes in javascript, css and views files run Laravel Mix script using npm
   ```shell script
   $ npm run watch
   ``` 
1. You can edit any file in `resources/` directory like `resources/views/html/home.blade.php` or `resources/styles/app.styl` and watch the changes in the browser instantaneity.
1. Run `$npm run production` to process and minify files for production

You can also use other Frameworks like Bootstrap, Foundation, Bulma, UIKit, SemanticUI, but they may need special configurations to run alongsite Laravel Mix.

##### Capturing content

Kusikusi comes with a generic CMS UI that in most cases will satisfy all content management needs, the compiled version is found in public/cms directory. If for any readon you need to create an special content editor you can take the [source code](https://github.com/cuatromedios/kusikusi-frontend) as a base.

With the server running you can access for example to to `http://127.0.0.1:8000/public/cms` and use your admin credentials to log in. The default seed comes with three basic models you can use for a basic structure: `home`, `section` and `page`. Usimg the CMS UI you can, for example, add sections and pages to home and also nest other sections and pages to already created sections. For more information about creating your own models and configure the editor for them consult the documentation.

For every model there is a view in `resources/views/html/` you can customize. Also, you can edit `resources/views/html.blade.php` as the main template.

Enjoy! 

## Kusikusi API

Kusikusi can also be use via a REST API, both for websites projects but also as a headless CMS. All calls to the API should be authenticated, so the first thing you have to do is login. Use the HTTP client you prefer, like [CURL](https://curl.haxx.se/), a [PHP script](http://php.net/manual/es/function.file-get-contents.php) or [POSTMAN](https://www.getpostman.com/)

###### Login

*Call*
```
POST http://localhost:8000/api/user/login
{
  "email": "admin",
  "password": "THE-GNERATED-PASSWORD"
}

```

*Result*
```json
{
    "success": true,
    "data": {
        "token": "SVdMUVBFWThZWFJFMjYyaUdRQVF1MVUydXQ5ZGM2dzZUY0ZHelVVQQ==",
        "entity": {
            "id": "04bec380-6a67-4992-b9b8-bcf5d3758c8f",
            "model": "user",
            "isActive": 1,
            "relations": [ ],
            "data": {
                "name": "Admin ",
                "email": "admin",
                "profile": "admin"
            }
        }
    },
    "info": null
}
```

You will get a Json response with three properties: `success`, `data` and `info`. All responses from the API will return this structure, basically you get:
* **success** A Boolean value stating the call was succesful or not
* **data** The actual data returned by the call, or null if something is wrong
* **info** Optional more information the API may return, for example if an error occurried.

In the specific case of the **login** endpoint the data has two properties: `token` and `entity`. The value of the **`token`** property, should be used in any consecutive call in order to get authorized. 

Each entity has an unique generated ID, and some special entities, like website home entity created by the seeder have a manually generated ID for easy identification in the database, in this case `home`

Let's get the 'home' entity, please note you have to set the `Authorization` header with 'Bearer', space, and the token you got in the login:

*Call*
```
GET http://localhost:8000/api/entity/home
Authorization: TheTokenYouGotWhenUsingTheLoginEndPoint

```

*Result*
```json
{
    "success": true,
    "data": {
        "id": "home",
        "model": "home",
        "name": "Home",
        "parent": "root",
        "position": 0,
        "isActive": 1,
        "created_by": "seeder",
        "updated_by": "seeder",
        "published_at": "2018-04-15 18:02:26",
        "unpublished_at": "9999-12-31 23:59:59",
        "entity_version": 1,
        "tree_version": 2,
        "relations_version": 1,
        "full_version": 3,
        "created_at": "2018-04-15 18:02:27",
        "updated_at": "2018-04-15 18:02:27",
        "deleted_at": null,
        "contents": {
            "description": "The website description",
            "summary": "The other title",
            "title": "Website title",
            "url": "/"
        }
    },
    "info": null
}
```

You get all entity's information!

Want to get the home's children?

*Call*
```
GET http://localhost:8000/api/entity/home/children
Authorization: TheTokenYouGotWhenUsingTheLoginEndPoint

```

*Result*
```json
{
    "success": true,
    "data": [
        {
            "id": "f289b924-2422-4b52-b88e-b9ba312d30b5",
            "model": "section",
            "name": "Section title",
            "parent": "home",
            "position": 0,
            "isActive": 1,
            "created_by": "seeder",
            "updated_by": "seeder",
            "published_at": "2018-04-15 18:02:26",
            "unpublished_at": "9999-12-31 23:59:59",
            "entity_version": 1,
            "tree_version": 1,
            "relations_version": 1,
            "full_version": 2,
            "created_at": "2018-04-15 18:02:27",
            "updated_at": "2018-04-15 18:02:27",
            "contents": {
                "title": "Section title"
            },
            "relation": {
                "kind": "ancestor",
                "position": 0,
                "tags": [
                    ""
                ],
                "depth": 1
            }
        }
    ],
    "info": null
}
```

You get an array of entities.

Full documentation coming soon!

## Testings
To run the Kusikusi testings all you have to do is run the next command in your project directory, please use quotes in login and password specially if using special characters:

> WARNING! The tests are destructive, don't run on any project!
```
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
```
Where:
* **username:** is the user "admin" that was given to you after running the artisan migrate command.
* **password:** is the password that was given to you after running the same command.

Depending on your setup the command for running the test may change. If the past one doesn't work try one of these:
```
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/ 'email' 'password'
```
```
vendor\bin\phpunit --bootstrap vendor/autoload.php tests/ 'email' 'password'
```
## Contributing
Contributions, questions and comments are all welcome and encouraged. For code contributions submit a pull request.

#### History
KusiKusi is the name we in [Cuatromedios](http://www.cuatromedios.com/) used to reference the framework we developed internally for our own and our clients projects since 2008. About 50 web projects were developed using this framework.

For this new version, starting 2018 we decided to use a well know framework, Laravel, and specifically its subproject Lumen in order to let more developers to understand its code.

## Credits
**Current version by [Cuatromedios](https://www.cuatromedios.com/)**
* [Ramses Moreno](https://github.com/ramsesmoreno)
* [Joshua González](https://github.com/holtzheimer)

**All people have contributed to KusiKusi in past versions**
* Ramses Moreno
* Hector Padilla
* Erick Olvera
* Henry Galvez
* Adolfo Tavizon ✝
* Fernando Pintado

**Projects this version is based on**
* Previous versions of KusiKusi and Waakun
* [Lumen](https://lumen.laravel.com/)
* [Intervention Image](http://image.intervention.io/)
* [League Filesystem](https://github.com/thephpleague/flysystem)

## License
[MIT License](LICENCE.txt)
