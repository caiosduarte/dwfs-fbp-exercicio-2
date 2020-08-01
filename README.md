# fbp-exercicio-2

Após clonar o projeto:

$ composer install

$ php -S localhost:8080 -t public/

## Exemplos:

### CREATE

PUT localhost:8080/users

Json body: 
{
	"name": "Caio",
	"email": "caio@caio.com",
	"telephones": [
		{"number": "12345679"},
		{"number": "12345680"}
	]
}

Json body retorno:
{
  "id": 6,
  "name": "Caio",
  "email": "caio6@caio.com",
  "telephones": [
    "12345679",
    "12345680"
  ],
  "createdDate": "01\/08\/2020 00:21:16"
}

Header retorno:

Host: localhost:8080
Date: Sat, 01 Aug 2020 02:25:14 GMT
Connection: close
X-Powered-By: PHP/7.4.7
Location: http://localhost:8080/users/6
Cache-Control: no-cache, private
Date: Sat, 01 Aug 2020 02:25:14 GMT
Content-Type: application/json
X-Debug-Token: c401f1
X-Debug-Token-Link: http://localhost:8080/_profiler/c401f1
X-Robots-Tag: noindex
Set-Cookie: sf_redirect=%7B%22token%22%3A%22c401f1%22%2C%22route%22%3A%22app_createuseraction__invoke%22%2C%22method%22%3A%22POST%22%2C%22controller%22%3A%22App%5C%5CController%5C%5CCreateUserAction%22%2C%22status_code%22%3A201%2C%22status_text%22%3A%22Created%22%7D; path=/; httponly; samesite=lax





