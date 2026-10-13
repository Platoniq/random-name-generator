<?php
namespace Platoniq\RandomNameGenerator;

use PHPUnit\Framework\TestCase;
use Cinam\Randomizer\Randomizer;
use Cinam\Randomizer\NumberGenerator;

/**
 * @coversDefaultClass \Platoniq\RandomNameGenerator\Alliteration
 * @covers ::<protected>
 */
class AlliterationTest extends TestCase
{
    /**
     * Verify basic behavior of getName().
     *
     * @test
     * @covers ::__construct
     * @covers ::getName
     *
     * @return void
     */
    public function testNameBasic()
    {
        $generator = new Alliteration();
        $parts = explode(' ', $generator->getName());
        $this->assertCount(2, $parts);
        $this->assertSame($parts[0][0], $parts[1][0]);
    }

    /**
     * Verify basic behavior of getName() with a forced random generator.
     *
     * @test
     * @covers ::__construct
     * @covers ::getName
     *
     * @return void
     */
    public function testNameForced()
    {
        $numberGenerator = $this->createMock(NumberGenerator::class);
        $numberGenerator->expects($this->exactly(2))->method('getInt')->willReturn(20, 5);
        $randomizer = new Randomizer($numberGenerator);

        $generator = new Alliteration($randomizer);
        $this->assertSame('Black Bear', $generator->getName());
    }

    /**
     * Verify basic behavior of getName() in Spanish.
     *
     * @test
     * @covers ::__construct
     * @covers ::getName
     *
     * @return void
     */
    public function testNameSpanish()
    {
        $generator = new Alliteration(null, 'es');
        $parts = explode(' ', $generator->getName());
        $this->assertCount(2, $parts);
        $this->assertSame($parts[0][0], $parts[1][0]);
    }

    /**
     * Verify getName() in Spanish puts the noun before the adjective.
     *
     * @test
     * @covers ::__construct
     * @covers ::getName
     *
     * @return void
     */
    public function testNameSpanishForced()
    {
        $numberGenerator = $this->createMock(NumberGenerator::class);
        $numberGenerator->expects($this->exactly(2))->method('getInt')->willReturn(39, 1);
        $randomizer = new Randomizer($numberGenerator);

        $generator = new Alliteration($randomizer, 'es');
        $this->assertSame('Iguana Imbatible', $generator->getName());
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
        new Alliteration(null, 'xx');
    }

    /**
     * Verify the Spanish word lists contain no offensive words and every
     * adjective has an alliterative noun.
     *
     * @test
     * @coversNothing
     *
     * @return void
     */
    public function spanishWordListsAreClean()
    {
        $adjectives = file(__DIR__ . '/../src/es/adjectives.txt', FILE_IGNORE_NEW_LINES);
        $nouns = file(__DIR__ . '/../src/es/nouns.txt', FILE_IGNORE_NEW_LINES);
        $banned = [
            'zorro', 'cerdo', 'puerco', 'burro', 'asno', 'mula', 'rata', 'perro', 'vaca', 'cabra', 'gallina',
            'buitre', 'víbora', 'serpiente', 'sanguijuela', 'gusano', 'piojo', 'cucaracha', 'hiena', 'besugo',
            'foca', 'ballena', 'morsa', 'cotorra', 'borrego', 'oveja', 'mono', 'gorila', 'pato', 'pavo', 'sapo',
            'mariquita', 'mariposa', 'marmota', 'feo', 'pobre', 'tonto', 'gordo', 'lento', 'raro', 'triste',
            'débil', 'torpe', 'cobarde', 'loco', 'salvaje', 'sucio', 'viejo', 'inútil', 'vago',
        ];

        $this->assertSame([], array_values(array_intersect($banned, array_merge($adjectives, $nouns))));
        foreach ($adjectives as $adjective) {
            $this->assertNotEmpty(preg_grep("/^{$adjective[0]}/", $nouns), "No noun for {$adjective}");
        }
    }

    /**
     * Verify basic behavior of __toString().
     *
     * @test
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::getName
     *
     * @return void
     */
    public function toStringBasic()
    {
        $generator = new Alliteration();
        $parts = explode(' ', (string)$generator);
        $this->assertCount(2, $parts);
        $this->assertSame($parts[0][0], $parts[1][0]);
    }
}
