# Filmweb.pl API
Non-official API for Filmweb.pl

## Usage:

```php
// Login to Filmweb
$filmweb = new Filmweb($login, $password);
$api = $filmweb->getApi();

// Get user info
$api->call('isLoggedUser');
$user = $api->getData();
$userId = $user[ isLoggedUser::USERID ];

// Get list of voted films
$api->call('getUserFilmVotes', [ $userId ]);
$films = $api->getData();

// Get detailed info about the film id: 126180
$api->call('getFilmInfoFull', [ 126180 ]);
$film = $api->getData();

// ...

print_r($films);
print_r($film);
```

## Installation

`composer require orkan/filmweb-api`


## Author

* [Orkan](https://github.com/orkan) - *Initial work*

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
