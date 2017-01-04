<?php

// Array of image paths, feel free to add/remove to/from this list
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

// Redirect to a random image from the above array using status code "303 See Other" 
if (headers_sent() === false) {
    header('Location: '.$images[array_rand($images)], true, 303);
}
