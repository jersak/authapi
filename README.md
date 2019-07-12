#  How to


## Installation
Requirements: 
* Composer
* Docker / Docker-compose.
---
1- Clone the repository. 

2- Navigate to the repository folder and run: -  `composer install`  -  `cp .env.example .env`  -  `docker-compose up -d`

Check if the three containers are up (app, web, database) using  `docker ps`. I'm not sure if this is a windows issue or specific to my computer, but sometimes at first run the database container stops. If the database container is not running, simply run  `docker-compose up -d`  again and it should be fine. 

3- Run the migrations to create the database :  `php artisan migrate`. 
 Add `--seed` to the command if you wish to create a sample user with username: `user@email.com` and password `secret`.

At this point the application should be accessible at  [http://localhost:8080/](http://localhost:8080/)

## Endpoints (assuming http://localhost:8080 as base url)
### Create a new user
POST - http://localhost:8080/users

Request payload (JSON):

    {
		"name": "John Doe",
		"email": "john@email.com",
		"password": "mysuperpass"
	}

Response payload (JSON):

    {
	    "id": 2
	    "name": "John Doe",
	    "email": "john@email.com",
	    "updated_at": "2019-07-12 04:46:10",
	    "created_at": "2019-07-12 04:46:10",
    }
    
### Authenticate a user
POST - http://localhost:8080/auth/login

Request payload (JSON):

    {
		"email": "john@email.com",
		"password": "mysuperpass"
	}

Response payload (JSON):

    {
	    "token": "eyJpdiI6Ik5jaFJCNXdcL3RcL0pqRWpKRVNCRzdhZz09IiwidmFsdWUiOiJJQjZmM2N3Mk9NdUJyNGVraHVYdjVzdEhBRHo5YjcrK0R2NWtvTGhGYjNNcnRNbXJRT2dvRXROR1h2aVdNTU9ReG1pa1wvNFFtYXMydXFJMzBkYjlxczNoSUU2RGlnaTNFeUtRclwvQk0wbENrT05cL0xoR2g1bnY3ancyWlJpQjY4R3lFWnE1UTYza0k4em1la3RESHZzYTEwZ3k3UStGZmw0bkpkcHo3QjRLamozUnZiRndQVUdWY1ZYQVJjWEVmREVNM29GUThHK0JtZDd0NDFsckh6eHVkWHNZWnEzM2ltOExHZFlBazJBdEZnPSIsIm1hYyI6ImI1YTdlNTY1YmFmY2E3YzdjZDRjZjAwMDlkOWM3ZDJkNjE4M2JhNDIzYTBhNzg0MDJiZTQ2YWRhNDE0YzI0YjEifQ=="
    }
### Sample endpoint that uses authentication
GET - http://localhost:8080/whoami

No need for a request payload. Authentication is made via the `Authorization` header by providing a `Bearer` token.
Sample HTTP request:

    GET /whoami HTTP/1.1
    Host: localhost:8080
    Authorization: Bearer "eyJpdiI6IkM3WW1oV0FGSTFHZWoyRytjbEwzNlE9PSIsInZhbHVlIjoibERZOE01b0VITzdWWTZoaFNNbHcwUVJzXC9xWTlEYWE2cE9WR09ReTA5S2syekxtMGd5d3prcFdcL0thVDNvdkNZUWJvNGwzVkIxR25IcXUxTDZrM0RUWlFMTmZiYzZMcnFpbitKOHNkMlBJRG5mT3d5K3ZsMXZYK3g3K2EzN1pGYlBiOU5NOE9uU0lXdmRBK1ZraCtUXC94RmM5dmQ1UklqcUMzTmUxcGx2amxFVzFZd3pkanFjYW5kS3VoMkdia1hMUmJvd0VGOFk3eE13c29kWGc1S0lWNHBcL3hvXC9Jc01kcFhlbllZaVNncVIwPSIsIm1hYyI6IjkyN2RjN2UwZmNhNzgwNDIwYzEyZWE4ZjdmYmQ0ZTExOTllYzE4Mzg2MTViODViNDI3NjZiODNmYTVjOTM5MGMifQ=="
    User-Agent: PostmanRuntime/7.15.0
    Accept: */*
    Cache-Control: no-cache
    Postman-Token: 4c44d70b-b941-4f4d-85fa-aa02872f2170,d0e3b86e-648c-497a-94cb-9b16654b628c
    Host: localhost:8080
    accept-encoding: gzip, deflate
    Connection: keep-alive
    cache-control: no-cache


Response payload

    {
	    "authenticated_as": {
	        "id": 2,
	        "name": "John Doe",
	        "email": "john@email.com",
	        "created_at": "2019-07-12 04:46:10",
	        "updated_at": "2019-07-12 04:46:10"
	    }
    }
