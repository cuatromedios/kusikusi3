# KusiKusi PHP

## Introduction
KusiKusi _(spider in Quechuan)_ is a boilerplate for creating **API-first applications based on hierarchical data**, like the data found on most websites (home / section / page). KusiKusi can be used as the backend for web sites, web applications, mobile apps or platforms containing all of them.

KusiKusi PHP has other related projects:

* **KusiKusi Front-End** (Generic UI to interact with the API. e.g CMS )
* **KusiKusi JS** (A KusiKusi compatible backend using Node and Mongo)
* **KusiKusi PHP** (This project)

#### Technical info
KusiKusi PHP is based on [Lumen](https://lumen.laravel.com)

##### Requirements
 * [PHP 7+](https://www.php.net)
 * [MySQL 5.6](https://www.mysql.com/) or [MariaDB 10.0.5](https://mariadb.com/).
 * [Composer] https://getcomposer.org/download/

#### History
KusiKusi (And before _Waakun_) is the name we in [Cuatromedios](http://www.cuatromedios.com/) used to reference the framework we developed internally for our own and our clients projects since 2008. About 50 web projects were developed using this framework.

For this new version, starting 2018 (well, to be exactly in December 2017) we decided to use a well know framework, Laravel, specifically its subproject Lumen in order to let more developers to understand its code. Then we chose [REST API with Lumen](https://github.com/barayuda/rest-api-with-lumen) boilerplate because its generic features for a REST API as te starting point to implement the soul of KusiKusi: An easy way to construct applications based on hierarchical data, where all models are based on the same structure.

## The KusiKusi way

The basic piece of information in KusiKusi is named an **Entity**. One Entity can be based in a certain model (e.g Home, Page, Post, Comment)

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
1. Edit the `.env` file to specify the database conection information
1. Create the database to be used and the database for testing, of course, using the names used in the `.env` file

#### Migrations and Seed
Run the Artisan migrate command with seed:
```
php artisan migrate --seed
```

If you are debuging, you may need to reset the database **Warning! will erase all your data**:
```
php artisan migrate:refresh --seed
```

(The `--seed` parameter is used to populate the database with initial data.)


## Contributing
Contributions, questions and comments are all welcome and encouraged. For code contributions submit a pull request.

## Credits
**Current version**
* Ramses Moreno

**All people have contributed to KusiKusi in past versions**
* Ramses Moreno
* Héctor Padilla
* Erick Olvera
* Henry Galvez
* Adolfo Tavizon ✝
* Fernando Pintado

**Projects this version is based on**
* Previous versions of KusiKusi and Waakun
* [REST API with Lumen 5.4](https://github.com/hasib32/rest-api-with-lumen)

## License
[MIT License](LICENCE.txt)