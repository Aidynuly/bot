![PHP](https://img.shields.io/badge/php-7.3.25-blue)
![MariaDB](https://img.shields.io/badge/mysql-10.3.25-blue)

### Overview
Telegram **bot** that calculates policy sum for Kazakhstan citizens

### Stack

- PHP 7.3.25
- MariaDB 10.3.25

### Links: 
- Telegram bot - [@avtoadv_bot](https://t.me/avtoadv_bot)
- Web-site - [Avtoadvokat](https://avtoadvokat.kz/)

### Deploy instructions 

- clone project
```bash
$ git clonegit@gitlab.com:rocketfirm/exclusive-qurylys-backend.git
```
- install dependencies via composer
```bash
$ composer install
```
- import structure.sql 
```bash
$ mysql -u user -p db_name < structure.sql
```
- configure credentials in .env file
```bash
$ cp .env.example .env
```

