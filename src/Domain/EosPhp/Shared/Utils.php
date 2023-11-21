<?php

namespace Domain\EosPhp\Shared;

use StephenHill\Base58;

class Utils
{
    public static function checkDecode($key, $keyType = 'sha256x2')
    {
        $b58 = new Base58();
        $keyBin = $b58->decode($key);
        $key = substr($keyBin, 0, -4);
        // check
        $checksum = substr($keyBin, -4);

        if ($keyType === 'sha256x2') {
            // legacy
            $newCheck = substr(hash('sha256', hash('sha256', $key, true), true), 0, 4);
        } else {
            $check = $key;

            if ($keyType) {
                $check .= $keyType;
            }
            $newCheck = substr(hash('ripemd160', $check, true), 0, 4); //PVT
        }

        if ($checksum !== $newCheck) {
            throw new \Exception('The private key has wrong format.', 1);
        }

        return bin2hex($key);
    }

    public static function checkEncode($bin, $keyType = 'sha256x2')
    {
        $b58 = new Base58();

        if ($keyType === 'sha256x2') {
            // legacy
            $checksum = substr(hash('sha256', hash('sha256', $bin, true), true), 0, 4);

            return $b58->encode($bin . $checksum);
        } else {
            $check = $bin;

            if ($keyType) {
                $check .= $keyType;
            }
            $_checksum = substr(hash('ripemd160', $check, true), 0, 4); //PVT

            return $b58->encode($bin . $_checksum);
        }
    }
}
