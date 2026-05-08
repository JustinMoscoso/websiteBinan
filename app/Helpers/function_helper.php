<?php

function isValidMimeImg($mimeType)
{
    $mimeTypes = ['image/png',
        'image/jpg',
        'image/jpeg'];
    return in_array($mimeType, $mimeTypes);
}

function generateRandomCode($length = 6)
{
//    $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
//    $characters = '123456789abcdefghijklmnpqrstuvwxyz';
    $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
    $code = '';
    $charactersLength = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }
    return $code;
}