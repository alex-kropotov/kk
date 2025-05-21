<?php
    /**
     * Created by PhpStorm.
     * User: sas
     * Date: 16.01.2019
     * Time: 19:23
     */
    namespace Tools\Database;

    class KeyGen
    {
        const BASE = 1450656000;
        const MSEC_RANGE = 4;
        const RAND_MAX = 999;
        const RAND_RANGE = 3;
        //static private $lastVal = 0;
        public static function getNewKey(): int
        {
            $nowArray = explode(" ", microtime());
            return (int)  (($nowArray[1]-self::BASE)
                .str_pad(floor($nowArray[0]*(10 ** self::MSEC_RANGE)), self::MSEC_RANGE, '0', STR_PAD_LEFT)
                .str_pad(rand(1, self::RAND_MAX), self::RAND_RANGE, '0', STR_PAD_LEFT)
            );
        }
    }
