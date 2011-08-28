<?php

// can't set anything that hasn't been declared when the extension loads.
ini_set('redis.arrays.names', 'users,friends');
ini_set('redis.arrays.hosts', 'users[]=localhost:6379&users[]=localhost:6380&users[]=localhost:6381&users[]=localhost:6382&friends[]=localhost');
ini_set('redis.arrays.functions', 'users=user_hash');
ini_set('redis.arrays.index', 'users=1,friends=0');

// different redis arrays
$ra = new RedisArray('users');
$ra = new RedisArray(array('localhost:6379', 'localhost:6380', 'localhost:6381'));

// before resizing the array
$ra = new RedisArray(array('localhost'));
var_dump($ra->set('hello', 'world'));
var_dump($ra->get('hello'));

$ra = new RedisArray(array('localhost:6380', 'localhost:6381', 'localhost:6382'), array('previous' => array('localhost'), 'index' => TRUE)); // after resizing
var_dump($ra->get('hello'));


var_dump($ra->_hosts());
var_dump($ra->_target('a'));
var_dump($ra->_target('b'));
var_dump($ra->_target('c'));

$r0 = new Redis;
$r0->connect('127.0.0.1', 6379);
$r0->set('c', 'z');

$r1 = new Redis;
$r1->connect('127.0.0.1', 6380);
$r1->set('b', 'y');

$r2 = new Redis;
$r2->connect('127.0.0.1', 6381);
$r2->set('a', 'x');

var_dump(array('x', 'y', 'z') === $ra->mget(array('a', 'b', 'c')));
$ra->mset(array('a' => 'X', 'b' => 'Y', 'c' => 'Z'));
var_dump(array('X', 'Y', 'Z') === $ra->mget(array('a', 'b', 'c')));

$ra->del(array('a','c'));
$ra->del('a','c');

var_dump($ra->mget(array('a', 'b', 'c')));

$ra->_rehash();
?>
