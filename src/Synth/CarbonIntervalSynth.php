<?php

namespace Atymic\DateIntervalCast\Synth;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class CarbonIntervalSynth extends Synth {
    public static $types = [
        'native' => \DateInterval::class,
        'carbon' => CarbonInterval::class,
    ];

    public static $key = 'cbnint';

    static function match($target) {
        foreach (static::$types as $type => $class) {
            if ($target instanceof $class) return true;
        }

        return false;
    }

    function dehydrate($target) {
        return [
            $target instanceof CarbinInterval ?  $target->spec() : $this->getDateIntervalSpec($target),
            ['type' => array_search(get_class($target), static::$types)],
        ];
    }

    function hydrate($value, $meta) {
        return new static::$types[$meta['type']]($value);
    }

    protected function getDateIntervalSpec(\DateInterval $interval): string
    {
        $spec = 'P';

        if ($interval->y) {
            $spec .= $interval->y . 'Y';
        }
        if ($interval->m) {
            $spec .= $interval->m . 'M';
        }
        if ($interval->d) {
            $spec .= $interval->d . 'D';
        }

        if ($interval->h || $interval->i || $interval->s) {
            $spec .= 'T';
            if ($interval->h) {
                $spec .= $interval->h . 'H';
            }
            if ($interval->i) {
                $spec .= $interval->i . 'M';
            }
            if ($interval->s) {
                $spec .= $interval->s . 'S';
            }
        }

        return $spec;
    }
}
