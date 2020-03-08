# PSR-16 Simple namespaced cache

[![Build Status](https://travis-ci.org/pextras/psr16-namespaced-cache.svg?branch=master)](https://travis-ci.org/pextras/psr16-namespaced-cache)

## Install
`composer require pextras/psr16-namespaced-cache`

## Usage
```php
// Your PSR-16 cache pool implementing Psr\SimpleCache\CacheInterface 
$cache = new SimpleCache();

$namespaced = new \Pextras\NamespacedCache\NamespacedCache($cache, 'foo');

// NamespacedCache implements Psr\SimpleCache\CacheInterface
$namespaced->get('bar');
$namespaced->set('baz', 123);
```
