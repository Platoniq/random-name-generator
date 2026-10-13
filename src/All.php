<?php
namespace Nubs\RandomNameGenerator;

use Cinam\Randomizer\Randomizer;

/**
 * A generator that uses all of the other generators randomly.
 */
class All extends AbstractGenerator implements Generator
{
    /** @type array The other generators to use. */
    protected $_generators;

    /** @type Cinam\Randomizer\Randomizer The random number generator. */
    protected $_randomizer;

    /**
     * Initializes the All Generator with the list of generators to choose from.
     *
     * @api
     * @param array $generators The random generators to use.
     * @param \Cinam\Randomizer\Randomizer $randomizer The random number generator.
     */
    public function __construct(array $generators, Randomizer $randomizer = null)
    {
        $this->_generators = $generators;
        $this->_randomizer = $randomizer;
    }

    /**
     * Constructs an All Generator using the default list of generators.
     * Only the Alliteration generator supports languages other than English.
     *
     * @api
     * @param \Cinam\Randomizer\Randomizer $randomizer The random number generator.
     * @param string $language The language code of the names ('en' or 'es').
     * @return \Nubs\RandomNameGenerator\All The constructed generator.
     */
    public static function create(Randomizer $randomizer = null, $language = 'en')
    {
        $generators = [new Alliteration($randomizer, $language)];
        if ($language === 'en') {
            $generators[] = new Vgng($randomizer);
        }

        return new self($generators, $randomizer);
    }

    /**
     * Gets a randomly generated name using the configured generators.
     *
     * @api
     * @return string A random name.
     */
    public function getName()
    {
        return $this->_getRandomGenerator()->getName();
    }

    /**
     * Get a random generator from the list of generators.
     *
     * @return \Nubs\RandomNameGenerator\Generator A random generator.
     */
    protected function _getRandomGenerator()
    {
        return $this->_randomizer ? $this->_randomizer->getArrayValue($this->_generators) : $this->_generators[array_rand($this->_generators)];
    }
}
