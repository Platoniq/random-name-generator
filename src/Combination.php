<?php
namespace Nubs\RandomNameGenerator;

/**
 * Defines a name generator that pairs any adjective with any noun, regardless
 * of their starting letters.
 */
class Combination extends Alliteration
{
    /**
     * Get a random noun, ignoring the adjective it will be paired with.
     *
     * @param string $adjective The adjective the noun will be paired with.
     * @return string The random noun.
     */
    protected function _getNoun($adjective)
    {
        return $this->_getRandomWord($this->_nouns);
    }
}
