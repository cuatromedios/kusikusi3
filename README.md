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


## The KusiKusi way

The basic piece of information in KusiKusi is named an **Entity**. One Entity can be based in a certain model (e.g Home, Page, Post, Comment). Each entity is part of a hierarchical structure, a tree: One parent and zero or any number of children.

## Getting Started

#### Clone the repository
Download or clone the repository to your development environment

#### Install dependencies
In your project directory:
```
$ composer install
```
#### Configure the Environment
1. Duplicate the `.env.example` file and name it `.env`
1. Set your application key to a random string. Typically, this string should be 32 characters long. If the application key is not set, your user encrypted data will not be secure!
1. Edit the `.env` file to specify the database conection information: host, port, database name username, password
1. Create the database to be used and the database for testing, of course, using the database name used in the `.env` file

#### Migrations and Seed
Run the Artisan migrate command with seed:
```
php artisan migrate --seed
```

If you are debuging, you may need to reset the database **Warning! will erase all your data**:
```
php artisan migrate:fresh --seed
```

The `--seed` parameter is used to populate the database with initial data, incluiding the admin user. You may need to use `composer dumpautoload` before if any migration files are added or changed.

Once the migration and seed finished, you will get in the console the password for "admin" user.

#### Run the server

Run the server as any other PHP Script, the home directory should be `public/` so the `public/index.php` file gets executed. Your web server may have different configurations, like the port. Let's assume its running in localhost, port 8080 in the next examples: 

#### Calling the API

Once all configuration is set, you can call the API. All calls to the API should be authenticated, so the first thing you have to do is login. Use the HTTP client you prefer, like [CURL](https://curl.haxx.se/), a [PHP script](http://php.net/manual/es/function.file-get-contents.php) or [POSTMAN](https://www.getpostman.com/)

###### Login

*Call*
```
POST http://localhost:8080/api/user/login
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
GET http://localhost:8080/api/entity/home
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
GET http://localhost:8080/api/entity/home/children
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
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/ 'email' 'password'
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
