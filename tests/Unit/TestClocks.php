<?php

declare(strict_types=1);

namespace Quillstack\Clock\Tests\Unit;

use DateTimeImmutable;
use Quillstack\Clock\FrozenClock;
use Quillstack\Clock\SystemClock;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;

class TestClocks
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        //
    }

    public function theSystemClockReadsTheWallClock()
    {
        $before = time();
        $now = (new SystemClock())->now();

        $this->assertBoolean->isTrue($now->getTimestamp() >= $before);
        $this->assertBoolean->isTrue($now->getTimestamp() <= time());
    }

    public function afrozenClockStandsStillUntilItIsMoved()
    {
        $clock = new FrozenClock(new DateTimeImmutable('@1700000000'));

        $this->assertEqual->equal(1700000000, $clock->now()->getTimestamp());
        $this->assertEqual->equal(1700000000, $clock->now()->getTimestamp());
        $this->assertEqual->equal(1700000060, $clock->sleep(60)->now()->getTimestamp());
    }

    /**
     * Without a moment to start from it stands at one nobody will mistake for now.
     */
    public function itStartsSomewhereKnown()
    {
        $this->assertEqual->equal(1000000, (new FrozenClock())->now()->getTimestamp());
    }
}
