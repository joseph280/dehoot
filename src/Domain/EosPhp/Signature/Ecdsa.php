<?php

namespace Domain\EosPhp\Signature;

use Exception;
use Domain\EosPhp\Shared\Utils;
use Domain\EosPhp\Shared\EllipticPHP\EC;

class Ecdsa
{
    public static function privateToPublic(string $privateWIF, string $prefix = 'EOS')
    {
        $privateHex = self::wifPrivateToPrivateHex($privateWIF);
        $ec = new EC('secp256k1');
        $key = $ec->keyFromPrivate($privateHex);

        return $prefix . Utils::checkEncode(hex2bin($key->getPublic(true, 'hex')), null);
    }

    public static function wifPrivateToPrivateHex(string $privateKey): string
    {
        return substr(Utils::checkDecode($privateKey), 2);
    }

    public static function sign(string $data, string $privateKey): string
    {
        $dataSha256 = hash('sha256', hex2bin($data));

        return self::signHash($dataSha256, $privateKey);
    }

    public static function signHash($dataSha256, $privateKey)
    {
        $privHex = self::wifPrivateToPrivateHex($privateKey);
        $ecdsa = new Signature();
        $i = 0;
        $r = 0;
        $s = 0;
        $nonce = 0;

        while (true) {
            // Sign message (can be hex sequence or array)
            $signature = $ecdsa->sign($dataSha256, $privHex, $nonce);
            // der
            $der = $signature->toDER('hex');
            // Switch der
            $lenR = hexdec(substr($der, 6, 2));
            $lenS = hexdec(substr($der, (5 + $lenR) * 2, 2));
            // Need 32
            if ($lenR == 32 && $lenS == 32) {
                $r = $signature->r->toString('hex');
                $s = $signature->s->toString('hex');
                $i = dechex($signature->recoveryParam + 4 + 27);

                break;
            }

            $nonce++;

            if ($nonce % 10 == 0) {
                throw new Exception('There was an error signing the data', 1);
            }
        }

        $r = str_pad($r, 64, '0', STR_PAD_LEFT);
        $s = str_pad($s, 64, '0', STR_PAD_LEFT);

        $binary = hex2bin($i . $r . $s);

        return 'SIG_K1_' . Utils::checkEncode($binary, 'K1');
    }
}
