<?php

namespace Domain\EosPhp\Shared;

use Domain\EosPhp\Entities\Block\Block;
use Domain\EosPhp\Entities\Block\BlockHeader;
use Domain\EosPhp\Entities\Block\BlockTaposInfo;
use Domain\EosPhp\Entities\Blockchain\Blockchain;
use Domain\EosPhp\Entities\Transaction\Transaction;
use Domain\EosPhp\Entities\Transaction\TransactionHeader;

class Serialize
{
    public static $types = [
        'expiration' => 'f_uint32',
        'ref_block_num' => 'f_uint16',
        'ref_block_prefix' => 'f_uint32',
        'max_net_usage_words' => 'f_varuint32',
        'max_cpu_usage_ms' => 'f_uint8',
        'delay_sec' => 'f_uint8',
        'actions' => 'f_vector',
        'account' => 'f_name',
        'name' => 'f_name',
        'authorization' => 'f_vector',
        'actor' => 'f_name',
        'permission' => 'f_name',
        'data' => 'f_data',
        'transaction_extensions' => 'f_vector',
        'context_free_actions' => 'f_vector',
        'context_free_data' => 'f_vector',
    ];

    public static function reverseHex(string $hex)
    {
        return substr($hex, 6, 2) . substr($hex, 4, 2) . substr($hex, 2, 2) . substr($hex, 0, 2);
    }

    public static function transactionHeader(BlockTaposInfo | BlockHeader | Block $refBlock, Blockchain $blockchain, int $expireSeconds): TransactionHeader
    {
        $hex = self::reverseHex(substr($refBlock->id, 16, 8));
        $prefix = intval($hex, 16);

        date_default_timezone_set('UTC');

        return new TransactionHeader(
            expiration: strtotime($blockchain->head_block_time) + $expireSeconds,
            ref_block_num: $refBlock->block_num & 0xffff,
            ref_block_prefix: $prefix
        );
    }

    public static function transaction(Transaction | array $data)
    {
        return self::encode($data);
    }

    public static function encode(mixed $data, $name = '')
    {
        $buffer = '';

        if ($data && $name === '') {
            foreach ($data as $key => $value) {
                $buffer .= self::encode($value, $key);
            }
        } else {
            $method = self::$types[$name];
            $buffer .= call_user_func([new static, $method], $data);
        }

        return $buffer;
    }

    /**
     * @param int $i
     *
     * @return string
     */
    public static function f_uint8(int $i)
    {
        return bin2hex(pack('C', $i));
    }

    /**
     * @param int $i
     *
     * @return string
     */
    public static function f_uint16(int $i)
    {
        $i = pack('v', $i);

        return bin2hex(is_array($i) ? $i[1] : $i);
    }

    /**
     * @param int $i
     *
     * @return string
     */
    public static function f_uint32(int $i)
    {
        return bin2hex(pack('V', $i));
    }

    /**
     * @param $i
     *
     * @return string
     */
    public static function f_varuint32($i)
    {
        $t = '';

        while (true) {
            if ($i >> 7) {
                $t .= self::f_uint8(0x80 | ($i & 0x7f));
                $i = $i >> 7;
            } else {
                $t .= self::f_uint8($i);

                break;
            }
        }

        return $t;
    }

    /**
     * @param $i
     *
     * @return string
     */
    public static function f_vector($i)
    {
        $buffer = self::f_varuint32(count($i));

        foreach ($i as $key => $value) {
            $buffer .= self::encode($value);
        }

        return $buffer;
    }

    /**
     * @param $s
     *
     * @return string
     */
    public static function f_name($s)
    {
        $charToSymbol = function ($c) {
            if ($c >= ord('a') && $c <= ord('z')) {
                return ($c - ord('a')) + 6;
            }

            if ($c >= ord('1') && $c <= ord('5')) {
                return ($c - ord('1')) + 1;
            }

            return 0;
        };
        $a = array_fill(0, 8, 0);
        $bit = 63;

        for ($i = 0; $i < strlen($s); ++$i) {
            $c = $charToSymbol(ord($s[$i]));

            if ($bit < 5) {
                $c = $c << 1;
            }

            for ($j = 4; $j >= 0; --$j) {
                if ($bit >= 0) {
                    $a[floor($bit / 8)] |= (($c >> $j) & 1) << ($bit % 8);
                    --$bit;
                }
            }
        }
        $hex = '';

        foreach ($a as $value) {
            $hex .= self::f_uint8($value);
        }

        return $hex;
    }

    /**
     * @param $i
     *
     * @return string
     */
    public static function f_data($i)
    {
        return self::f_varuint32(strlen($i) / 2) . $i;
    }
}
