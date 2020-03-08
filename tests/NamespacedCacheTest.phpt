<?php

declare(strict_types=1);

use Cache\Adapter\PHPArray\ArrayCachePool;
use Pextras\NamespacedCache\NamespacedCache;
use Tester\Assert;

require_once __DIR__ . '/../vendor/autoload.php';

$cache = new ArrayCachePool();

$namespaced = new NamespacedCache($cache, 'foo');

// test set
$namespaced->set('bar', 'Hello');
Assert::true($namespaced->has('bar'));
Assert::same('Hello', $namespaced->get('bar'));
Assert::same('Hello', $cache->get('|foo|bar'));

// test delete
$namespaced->delete('bar');
Assert::false($namespaced->has('bar'));
Assert::false($cache->has('|foo|bar'));

// test clear
$namespaced->set('k1', 'Lorem');
$namespaced->set('k2', 'Ipsum');
$namespaced->clear();
Assert::false($namespaced->has('k1'));
Assert::false($namespaced->has('k2'));

// test setMultiple
$arr = ['x1' => 'Foo', 'x2' => 'Bar'];
$namespaced->setMultiple($arr);
Assert::true($namespaced->has('x1'));
Assert::true($namespaced->has('x2'));
Assert::same('Foo', $namespaced->get('x1'));
Assert::same('Bar', $namespaced->get('x2'));

// test getMultiple
$multiple = $namespaced->getMultiple(['x1', 'x2']);
foreach ($multiple as $key => $value) {
    Assert::true(array_key_exists($key, $arr));
    Assert::same($arr[$key], $value);
}

// test deleteMultiple
$namespaced->deleteMultiple(['x1', 'x2']);
Assert::false($namespaced->has('x1'));
Assert::false($namespaced->has('x2'));
