<?php
namespace Platoniq\RandomNameGenerator;

use PHPUnit\Framework\TestCase;
use Cinam\Randomizer\Randomizer;
use Cinam\Randomizer\NumberGenerator;

/**
 * @coversDefaultClass \Platoniq\RandomNameGenerator\Combination
 * @covers ::<protected>
 */
class CombinationTest extends TestCase
{
    /**
     * Verify basic behavior of getName().
     *
     * @test
     * @covers ::getName
     *
     * @return void
     */
    public function testNameBasic()
    {
        $generator = new Combination();
        $this->assertCount(2, explode(' ', $generator->getName()));
    }

    /**
     * Verify getName() pairs words with different starting letters.
     *
     * @test
     * @covers ::getName
     *
     * @return void
     */
    public function testNameForced()
    {
        $numberGenerator = $this->createMock(NumberGenerator::class);
        $numberGenerator->expects($this->exactly(2))->method('getInt')->willReturn(0, 100);
        $randomizer = new Randomizer($numberGenerator);

        $generator = new Combination($randomizer);
        $this->assertSame('Adorable Gemsbok', $generator->getName());
    }

    /**
     * Verify getName() in Spanish puts the noun before the adjective.
     *
     * @test
     * @covers ::getName
     *
     * @return void
     */
    public function testNameSpanishForced()
    {
        $numberGenerator = $this->createMock(NumberGenerator::class);
        $numberGenerator->expects($this->exactly(2))->method('getInt')->willReturn(0, 80);
        $randomizer = new Randomizer($numberGenerator);

        $generator = new Combination($randomizer, 'es');
        $this->assertSame('Tigre Adorable', $generator->getName());
    }

    /**
     * Verify names are not restricted to alliteration.
     *
     * @test
     * @covers ::getName
     *
     * @return void
     */
    public function testNameIsNotRestrictedToAlliteration()
    {
        foreach (['en', 'es'] as $language) {
            $generator = new Combination(null, $language);
            $initials = [];
            for ($i = 0; $i < 200; $i++) {
                $parts = explode(' ', $generator->getName());
                $initials[] = $parts[0][0] === $parts[1][0];
            }

            $this->assertContains(false, $initials, "All {$language} names were alliterative");
        }
    }

    /**
     * Verify an unsupported language is rejected.
     *
     * @test
     * @covers ::__construct
     *
     * @return void
     */
    public function constructUnsupportedLanguage()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Combination(null, 'xx');
    }
}
