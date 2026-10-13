<?php
namespace Platoniq\RandomNameGenerator;

use PHPUnit\Framework\TestCase;
use Cinam\Randomizer\Randomizer;

/**
 * @coversDefaultClass \Platoniq\RandomNameGenerator\All
 * @covers ::<protected>
 */
class AllTest extends TestCase
{
    /**
     * Verify basic behavior of getName().
     *
     * @test
     * @covers ::__construct
     * @covers ::create
     * @covers ::getName
     * @uses \Platoniq\RandomNameGenerator\Alliteration
     * @uses \Platoniq\RandomNameGenerator\Vgng
     *
     * @return void
     */
    public function testNameBasic()
    {
        $generator = All::create();
        $name = $generator->getName();
        $this->assertMatchesRegularExpression('/.+/', $name);
    }

    /**
     * Verify basic behavior of getName() with a forced random generator.
     *
     * @test
     * @covers ::__construct
     * @covers ::create
     * @covers ::getName
     * @uses \Platoniq\RandomNameGenerator\Alliteration
     *
     * @return void
     */
    public function testNameForced()
    {
        $numberGenerator = $this->createMock('\Cinam\Randomizer\NumberGenerator');
        $numberGenerator->expects($this->exactly(2))->method('getInt')->willReturn(20, 5);
        $randomizer = new Randomizer($numberGenerator);

        $generator = new All([new Alliteration($randomizer)]);
        $this->assertSame('Black Bear', $generator->getName());
    }

    /**
     * Verify getName() in Spanish uses only the Alliteration generator.
     *
     * @test
     * @covers ::__construct
     * @covers ::create
     * @covers ::getName
     * @uses \Platoniq\RandomNameGenerator\Alliteration
     *
     * @return void
     */
    public function testNameSpanish()
    {
        $generator = All::create(null, 'es');
        $parts = explode(' ', $generator->getName());
        $this->assertCount(2, $parts);
        $this->assertSame($parts[0][0], $parts[1][0]);
    }

    /**
     * Verify basic behavior of __toString().
     *
     * @test
     * @covers ::__construct
     * @covers ::create
     * @covers ::__toString
     * @covers ::getName
     * @uses \Platoniq\RandomNameGenerator\Alliteration
     * @uses \Platoniq\RandomNameGenerator\Vgng
     *
     * @return void
     */
    public function toStringBasic()
    {
        $generator = All::create();
        $name = (string)$generator;
        $this->assertMatchesRegularExpression('/.+/', $name);
    }
}
