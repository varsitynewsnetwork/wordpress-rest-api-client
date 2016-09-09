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
use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\Http\GuzzleAdapter;
use Vnn\WpApiClient\WpClient;

require 'vendor/autoload.php';

$client = new WpClient(new GuzzleAdapter(new GuzzleHttp\Client()), 'http://yourwordpress.com');
$client->setCredentials(new WpBasicAuth('user', 'securepassword'));

$user = $client->users()->get(2);

print_r($user);
```
