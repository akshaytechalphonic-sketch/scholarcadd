<?php

namespace App\Helpers;

class Ccavenue
{

    // public static function encrypt($plainText, $key)
    // {
    //     $key = hex2bin(md5($key));
    //     $iv = pack("C*", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    //     return bin2hex(openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    // }

    // public static function decrypt($encryptedText, $key)
    // {
    //     $key = hex2bin(md5($key));
    //     $iv = pack("C*", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    //     return openssl_decrypt(hex2bin($encryptedText), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    // }

    protected static $initVector = "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0a\x0b\x0c\x0d\x0e\x0f";

    // public static function encrypt($plainText, $key)
    // {
    //     return openssl_encrypt(
    //         $plainText,
    //         'AES-128-CBC',
    //         $key,
    //         0,
    //         self::$initVector
    //     );
    // }

    public static function encrypt($plainText, $workingKey)
    {
        $key = hex2bin(md5($workingKey));
        return openssl_encrypt(
            $plainText,
            'AES-128-CBC',
            $key,
            OPENSSL_RAW_DATA,
            self::$initVector
        );
    }

    public static function decrypt($encryptedText, $key)
    {
        return openssl_decrypt(
            $encryptedText,
            'AES-128-CBC',
            $key,
            0,
            self::$initVector
        );
    }
}
