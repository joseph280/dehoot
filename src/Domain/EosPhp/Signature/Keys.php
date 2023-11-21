<?php

namespace Domain\EosPhp\Signature;

use Exception;
use StephenHill\Base58;

class Keys
{
    public function __construct()
    {
    }

    public static function GetPublicFromWIF(string $privateWIF): string
    {
        $base58 = new Base58();
        $keyBin = $base58->decode($privateWIF);
        $keyHex = bin2hex($keyBin);

        $networkPrefix = substr($keyHex, 0, 2);
        $privateKey = substr($keyHex, 2, 64);
        $checksum = substr($keyHex, 66);

        if (strlen($checksum) !== 8) {
            throw new Exception('Invalid checksum on WIF');
        }

        $privateKeyWithPrefix = $networkPrefix . $privateKey;

        $digestHash = openssl_digest(hex2bin($privateKeyWithPrefix), 'sha256', true);
        $digestHash2 = bin2hex(openssl_digest($digestHash, 'sha256', true));

        $publicHash = substr($digestHash2, 0, 8);

        if ($publicHash !== $checksum) {
            throw new Exception('Invalid checksum on WIF');
        }

        $pemPrivateKey = self::GeneratePemFromPrivateKey($privateKey);

        $openSslPrivate = openssl_pkey_get_private($pemPrivateKey);
        $details = openssl_pkey_get_details($openSslPrivate);

        $x = bin2hex($details['ec']['x']);
        $y = bin2hex($details['ec']['y']);

        $publicPrefix = '03';

        if ((int) hexdec($y) % 2 == 0) {
            $publicPrefix = '02';
        }

        $publicHex = $publicPrefix . $x;

        return hex2bin($publicHex);
    }

    public static function GeneratePemFromPrivateKey(string $secret): string
    {
        $der_data = hex2bin('302e0201010420' . $secret . 'a00706052b8104000a');
        $pem = chunk_split(base64_encode($der_data), 64, "\n");
        $pem = "-----BEGIN EC PRIVATE KEY-----\n" . $pem . "-----END EC PRIVATE KEY-----\n";

        return $pem;
    }
}
