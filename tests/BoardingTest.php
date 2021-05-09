<?php

namespace App\Tests;

use App\Controller\BoardingController;
use PHPUnit\Framework\TestCase;


class BoardingTest extends TestCase
{
    public function testSolvePartOneExample(): void
    {
        $day5 = new BoardingController();

        self::assertSame(883, $day5->getSeatNumber('BBFBBBFLRR'));
    }
}
