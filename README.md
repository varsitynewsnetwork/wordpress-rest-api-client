# wordpress-rest-api-client

> A Wordpress REST API client for PHP

[![Travis](https://img.shields.io/travis/varsitynewsnetwork/wordpress-rest-api-client.svg?maxAge=2592000?style=flat-square)](https://travis-ci.org/varsitynewsnetwork/wordpress-rest-api-client)

For when you need to make [Wordpress REST API calls](http://v2.wp-api.org/) from
some other PHP project, for some reason.

## Installation

```text
composer require vnn/wordpress-rest-api-client
```

## Usage

Example:

```php
$client = new WpClient($url);
$client->setCredentials(new WpBasicCredentials('username', 'password'));

$user = $client->users()->get(15);

echo $user['username'];
```
