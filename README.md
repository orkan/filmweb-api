![orkan/filmweb-api logo](https://user-images.githubusercontent.com/129182/81475756-67fc3200-920e-11ea-88b6-fc740191d41f.png)

# Filmweb.pl API
Non-official API for Filmweb.pl

* Highly configurable via an external configuration file
* Includes [Monolog](https://github.com/Seldaek/monolog) for extended logging
* Works in SAPI and CLI mode
* Advanced PHP error handling

## Installation
`$ composer require orkan/filmweb-api`

## Basic Usage
```php
<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Api\Method\isLoggedUser;

// Login to Filmweb
$filmweb = new Filmweb( $login, $password );
$api = $filmweb->getApi();

// Get user info
$api->call( 'isLoggedUser' );
$user = $api->getData( 'json' );
$userId = $user[ isLoggedUser::USERID ];

// Get list of voted films
$api->call( 'getUserFilmVotes', array( $userId ) );
$films = $api->getData( 'json' );

// Get detailed info about movie ID:126180
$api->call( 'getFilmInfoFull', array( 126180 ) );
$film = $api->getData( 'json' );

// ...

print_r( $films );
print_r( $film );
```

## Third Party Packages
* [Monolog](https://github.com/Seldaek/monolog)

## About
### Requirements
This API library works with PHP 7.2 or above

### Author
Orkan - orkans@gmail.com - https://github.com/orkan

### License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
