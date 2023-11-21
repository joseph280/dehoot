<?php

namespace Domain\EosPhp\Shared\EllipticPHP\Curve;

class PresetCurve
{
    public $curve;

    public $g;

    public $n;

    public $hash;

    public function __construct($options)
    {
        if ($options['type'] === 'short') {
            $this->curve = new ShortCurve($options);
        } elseif ($options['type'] === 'edwards') {
            $this->curve = new EdwardsCurve($options);
        } else {
            $this->curve = new MontCurve($options);
        }

        $this->g = $this->curve->g;
        $this->n = $this->curve->n;
        $this->hash = $options['hash'] ?? null;
    }
}
