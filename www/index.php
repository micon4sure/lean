<?php

include "../lean/autoload.php";
$i18n = new \lean\I18N(__DIR__, 'en');

$i18n->callback(function($key, \lean\I18N $i18n) {
    \lean\Dump::flat('asdasdasd');
    return $key . '!!!' . $i18n->locale();
});
?>

<?
include '/home/msa/dump/markdown.php';

echo Markdown(file_get_contents('./../README.markdown'));
?>