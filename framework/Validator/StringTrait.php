<?php

namespace Lightpack\Validator;

/**
 * Set of common string manipulation methods. The reason to use this as a triat are:
 *
 * 1. We do not want to provide static methods wrapped into as utility class.
 * 2. We want to abstract the algorithm used to manipulate the string.
 */
trait StringTrait
{
    /**
     * Checks if the provided string is empty.
     *
     * @access protected
     * @param  string  $string  The string to check.
     * @return boolean
     */
    protected function notEmpty($string)
    {
        return trim($string) !== '';
    }
    
    /**
     * Checks if the datatype is string.
     *
     * @access protected
     * @param  string  $string  The string to check.
     * @return boolean
     */
    protected function isString($string)
    {
        return is_string($string);
    }
    
    /**
     * Returns an array from a csv.
     *
     * @access protected
     * @param  string  $string     The csv string.
     * @param  string  $delimiter  The delimiter used.
     * @return boolean
     */
    protected function explodeString($string, $delimeter)
    {
        return str_getcsv($string, $delimeter);
    }

    /**
     * Returns a replaced string.
     *
     * @access protected
     * @param  string   $what  The string to replace.
     * @param  string  $with   The string as substitute.
     * @param  string  $string The actual subject string.
     * @return string
     */
    protected function stringReplace($what, $with, $string)
    {
        return str_replace($what, $with, $string);
    }

    /**
     * Converts a slug URL to friendly text.
     * 
     * It replaces dashes and underscores with whitespace 
     * character. Then capitalizes the first character.
     * 
     * @param   string  $slug
     * @return string
     */
    protected function humanize(string $slug): string {
        $text = str_replace(['_', '-'], ' ', $slug);
        $text = trim($text);

        return ucfirst($text);
    }
}