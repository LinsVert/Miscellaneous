<?php

class test {
    public static $kk;
    public static function get() {
        return self::$kk;
    }
    public static function set($value) {
        self::$kk = $value;
    }
}

$te1 = new test();
$te1::set('123');
unset($te1);
echo test::get();