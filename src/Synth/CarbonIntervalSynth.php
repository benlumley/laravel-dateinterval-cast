<?php

namespace Atymic\DateIntervalCast\Synth;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Livewire\Mechanisms\HandleComponents\Synthesizers\CarbonSynth;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class CarbonIntervalSynth extends Synth {
    public static $types = [
        'native' => DateTimeInterval::class,
        'carbon' => CarbonInterval::class,
    ];

    public static $key = 'cbn';

    static function match($target) {
        foreach (static::$types as $type => $class) {
            if ($target instanceof $class) return true;
        }

        return false;
    }

    function dehydrate($target) {
        return [
            $target->spec(),
            ['type' => array_search(get_class($target), static::$types)],
        ];
    }

    function hydrate($value, $meta) {
        return new static::$types[$meta['type']]($value);
    }
}
