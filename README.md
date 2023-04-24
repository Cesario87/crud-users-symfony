# SaaS Project

DESCRIPTION
This project is an API Rest, with a CRUD to relating users and clients in a SaaS (Software as a service).

AUTHOR 🧑
- [César Alba](https://github.com/Cesario87)

TECHNOLOGIES & TECHNIQUES 👨‍💻
- Symfony 5.4.22
- PHP 8.2.5
- XAMPP + MySQL
- Postman
- Docker

# Installation:
## Commands to run locally:
After cloning the repository you will need to run the following:
(Note that you will need to add an .env file with the data indicated in the .env.example)
```bash
composer install
```
```bash
bin/console doctrine:migrations:migrate
```
```bash
symfony server:start
```

## Routes examples 🌐
### GET
### /api/users
Shows all users
### /api/users/10
Show user
### /api/users?itemsPerPage=20&page=2&sortBy=id&sortOrder=ASC
Shows page 2 with 20 items per page, sorted by id an ASC
### /api/users?itemsPerPage=2&clientId=2
Shows page 2 of clients associated to a client with id = 2
### /api/clients
Shows all clients

### POST
### /api/users
Creates a user
### /api/clients
Creates a client
### /api/users/{id}
Modifies existing user
### DELETE
### /api/users/{id}
Removes existing user