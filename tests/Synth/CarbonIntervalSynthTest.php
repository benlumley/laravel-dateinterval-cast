<?php

use PHPUnit\Framework\TestCase;
use Atymic\DateIntervalCast\Synth\CarbonIntervalSynth;
use Carbon\CarbonInterval;

class CarbonIntervalSynthTest extends TestCase
{
    public function testMatch()
    {
        // Mock the Synth class constructor
        $synth = $this->createPartialMock(CarbonIntervalSynth::class, ['__construct']);

        $carbonInterval = CarbonInterval::days(2);
        $dateInterval = new \DateInterval('P2D');

        $this->assertTrue($synth::match($carbonInterval), "Should match CarbonInterval");
        $this->assertTrue($synth::match($dateInterval), "Should match DateInterval");
        $this->assertFalse($synth::match('Not an interval'), "Should not match non-interval values");
    }

    public function testDehydrate()
    {
        $synth = $this->createPartialMock(CarbonIntervalSynth::class, ['__construct']);

        $carbonInterval = CarbonInterval::days(2);
        $expectedCarbonDehydration = [
            'P2D',
            ['type' => 'carbon'],
        ];

        $this->assertEquals(
            $expectedCarbonDehydration,
            $synth->dehydrate($carbonInterval),
            "Dehydration of CarbonInterval should produce expected output"
        );

        $dateInterval = new \DateInterval('P2D');
        $expectedDateDehydration = [
            'P2D',
            ['type' => 'native'],
        ];

        $this->assertEquals(
            $expectedDateDehydration,
            $synth->dehydrate($dateInterval),
            "Dehydration of DateInterval should produce expected output"
        );
    }

    public function testHydrate()
    {
        $synth = $this->createPartialMock(CarbonIntervalSynth::class, ['__construct']);

        $carbonMeta = ['type' => 'carbon'];
        $carbonValue = 'P2D';
        $hydratedCarbon = $synth->hydrate($carbonValue, $carbonMeta);

        $this->assertInstanceOf(CarbonInterval::class, $hydratedCarbon, "Hydrated object should be an instance of CarbonInterval");
        $this->assertEquals(CarbonInterval::days(2), $hydratedCarbon, "Hydrated CarbonInterval should match the expected interval");

        $dateMeta = ['type' => 'native'];
        $dateValue = 'P2D';
        $hydratedDate = $synth->hydrate($dateValue, $dateMeta);

        $this->assertInstanceOf(\DateInterval::class, $hydratedDate, "Hydrated object should be an instance of DateInterval");
        $this->assertEquals(new \DateInterval('P2D'), $hydratedDate, "Hydrated DateInterval should match the expected interval");
    }
}
