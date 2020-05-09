# Filmweb.pl API
Non-official API for Filmweb.pl

* Highly configurable via an external configuration file
* Includes [Monolog](https://github.com/Seldaek/monolog) for extended login
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

// Get detailed info about the film id: 126180
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
works with PHP 7.2 or above

### Author
[Orkan](https://github.com/orkan) - *Initial work*

### License
This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
