<?php

$images = array(
    'img/2.png',
    'img/3.png',
    'img/4.png',
    'img/5.png',
    'img/6.png',
    'img/7.png',
    'img/8.png',
    'img/9.png',
    'img/10.png',
);

if (headers_sent() === false) {
    header('Location: '.$images[array_rand($images)], true, 303);
}
