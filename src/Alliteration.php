<?php
namespace Nubs\RandomNameGenerator;

use Cinam\Randomizer\Randomizer;

/**
 * Defines an alliterative name generator.
 */
class Alliteration extends AbstractGenerator implements Generator
{
    /** @type array The definition of the potential adjectives. */
    protected $_adjectives;

    /** @type array The definition of the potential nouns. */
    protected $_nouns;

    /** @type Cinam\Randomizer\Randomizer The random number generator. */
    protected $_randomizer;

    /** @type string The language code of the word lists. */
    protected $_language;

    /**
     * Initializes the Alliteration Generator with the default word lists.
     *
     * @api
     * @param \Cinam\Randomizer\Randomizer $randomizer The random number generator.
     * @param string $language The language code of the word lists ('en' or 'es').
     * @throws \InvalidArgumentException If the language is not supported.
     */
    public function __construct(Randomizer $randomizer = null, $language = 'en')
    {
        $directory = __DIR__ . "/{$language}";
        if (!preg_match('/^[a-z]{2}$/', $language) || !is_dir($directory)) {
            throw new \InvalidArgumentException("Unsupported language: {$language}");
        }

        $this->_randomizer = $randomizer;
        $this->_language = $language;
        $this->_adjectives = file("{$directory}/adjectives.txt", FILE_IGNORE_NEW_LINES);
        $this->_nouns = file("{$directory}/nouns.txt", FILE_IGNORE_NEW_LINES);
    }

    /**
     * Gets a randomly generated alliterative name.
     *
     * @api
     * @return string A random alliterative name.
     */
    public function getName()
    {
        $adjective = $this->_getRandomWord($this->_adjectives);
        $noun = $this->_getNoun($adjective);

        return ucwords($this->_language === 'es' ? "{$noun} {$adjective}" : "{$adjective} {$noun}");
    }

    /**
     * Get a random noun that starts with the same letter as the adjective.
     *
     * @param string $adjective The adjective the noun will be paired with.
     * @return string The random noun.
     */
    protected function _getNoun($adjective)
    {
        return $this->_getRandomWord($this->_nouns, $adjective[0]);
    }

    /**
     * Get a random word from the list of words, optionally filtering by starting letter.
     *
     * @param array $words An array of words to choose from.
     * @param string $startingLetter The desired starting letter of the word.
     * @return string The random word.
     */
    protected function _getRandomWord(array $words, $startingLetter = null)
    {
        $wordsToSearch = $startingLetter === null ? $words : preg_grep("/^{$startingLetter}/", $words);
        return $this->_randomizer ? $this->_randomizer->getArrayValue($wordsToSearch) : $wordsToSearch[array_rand($wordsToSearch)];
    }
}
