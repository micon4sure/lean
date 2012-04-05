<?
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER['REQUEST_URI']))
	return false;
include __DIR__ . '/index.php';
