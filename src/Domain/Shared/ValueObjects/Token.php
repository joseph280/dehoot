<?php

namespace Domain\Shared\ValueObjects;

class Token
{
    public readonly ?float $value;

    public readonly string $formatted;

    public readonly string $formattedShorten;

    public readonly string $formattedWithToken;

    public readonly string $token;

    public function __construct(float | string $value, ?string $tokenName = null)
    {
        $this->value = is_string($value) ? $this->getValueFromString($value) : $value;
        $this->token = $tokenName ?? config('services.token.name');

        $tokenDecimals = (int) config('services.token.decimals');
        $needsShorten = ((float) $this->value) >= 1000;

        $this->formatted = number_format($this->value ?? 0, $tokenDecimals, '.', '');
        $this->formattedShorten = $needsShorten ? $this->numberShorten($this->value) : $this->formatted;
        $this->formattedWithToken = $this->formatted . ' ' . $this->token;
    }

    public static function from(float | string $value, ?string $tokenName = null): self
    {
        return new self($value, $tokenName);
    }

    private function getValueFromString(string $value): string
    {
        $regex = '/[0-9]*\\.[0-9]+/i';
        $result = [];
        preg_match($regex, $value, $result);

        return $result[0] ? $result[0] : 0;
    }

    private function numberShorten($quantity): string
    {
        $shortenQuantity = '';

        $divisors = [
            pow(1000, 0) => '',
            pow(1000, 1) => 'K',
            pow(1000, 2) => 'M',
            pow(1000, 3) => 'B',
            pow(1000, 4) => 'T',
        ];

        foreach ($divisors as $divisor => $shorthand) {
            $maxNumber = $divisor * 1000;

            if (abs($quantity) < $maxNumber) {
                $finalQuantity = $quantity / $divisor;

                $maxNumber > 1000
                    ? $shortenQuantity = round($finalQuantity, 2) . $shorthand
                    : $shortenQuantity = (string) $finalQuantity;

                break;
            }
        }

        return $shortenQuantity;
    }
}
