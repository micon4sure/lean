<?php
namespace lean;
class Text {
    /**
     * Get the first n characters from the left of a string.
     * Text::left('foobar', 3) and Text::left('foobar', 'foo') both return 'foo'
     *
     * @param string     $input
     * @param int|string $len
     *
     * @return string
     */
    public static function left($input, $len) {
        return substr($input, 0, is_string($len)
            ? self::len($len)
            : $len);
    }

    /**
     * Get the characters between n from the left and n from the right of a string.
     * Text::mid('foobarqux', 3, 3) and Text::mid('foobarqux', 'foo', 'qux) both return 'bar'
     *
     * @param string     $input
     * @param int|string $offsetLeft
     * @param int|string $offsetRight
     *
     * @return string
     */
    public static function mid($input, $offsetLeft, $offsetRight) {
        $offsetLeft = is_string($offsetLeft)
            ? self::len($offsetLeft)
            : $offsetLeft;
        $offsetRight = is_string($offsetRight)
            ? self::len($offsetRight)
            : $offsetRight;
        return substr($input, $offsetLeft, self::len($input) - ($offsetLeft + $offsetRight));
    }

    /**
     * Get the last n characters from a string.
     * Text::right('foobar', 3) and Text::right('foobar', 'bar') both return 'bar'
     *
     * @param string     $input
     * @param int|string $len
     *
     * @return string
     */
    public static function right($input, $len) {
        $strlen = is_string($len)
            ? self::len($len)
            : $len;
        return substr($input, -$strlen);
    }

    /**
     * Cut an offset of n characters off off a string from the left
     * Text::offsetLeft('foobar', 3) and Text::offsetLeft('foobar', 'foo') both return 'bar'
     *
     * @param string     $input
     * @param int|string $offset
     *
     * @return string
     */
    public static function offsetLeft($input, $offset) {
        $offset = is_string($offset)
            ? self::len($offset)
            : $offset;
        return substr($input, $offset, self::len($input) - $offset);
    }

    /**
     * Cut an offset of n characters off off a string from the right
     * Text::offsetLeft('foobar', 3) and Text::offsetLeft('foobar', 'bar') both return 'foo'
     *
     * @param string     $input
     * @param int|string $offset
     *
     * @return string
     */
    public static function offsetRight($input, $offset) {
        $offset = is_string($offset)
            ? self::len($offset)
            : $offset;
        return substr($input, 0, -$offset);
    }

    /**
     * Return the length of a string
     *
     * @param string      $input
     * @param string|null $encoding
     *
     * @return int
     */
    public static function len($input, $encoding = 'UTF-8') {
        if ($encoding === null) {
            return strlen($input);
        }
        return mb_strlen($input, $encoding);
    }

    /**
     * Split a camelcase string into a lowercase identifier
     * fooBarQux will become foo-bar-qux, as will FooBarQux
     *
     * @param string  $input
     * @param bool    $multi (unused as of now)
     * @param string  $seperator
     *
     * @return string
     */

    public static function splitCamelCase($input, $multi = true, $seperator = '-') {
        $input = lcfirst($input);
        // split by one or many uppercase characters
        $split = preg_split('#([A-Z]+)#', $input, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY);

        $i = 0;
        $result = '';
        foreach ($split as $part) {
            // for each part of the split
            if ($i % 2) {
                // even part
                if (self::len($part) == 1) {
                    $result .= $seperator . strtolower($part);
                }
                else {
                    // multiple uppercase characters
                    $result .= '-' . strtolower(self::offsetRight($part, 1));
                    if (count($split) > $i + 1) {
                        // there are more parts to come
                        $result .= $seperator . strtolower(self::right($part, 1));
                    }
                    else {
                        // last part, just append
                        $result .= strtolower(self::right($part, 1));
                    }
                }
            }
            else {
                // odd part
                $result .= $part;
            }
            $i++;
        }
        return $result;
    }

    /*
     * Shorten a string to a max of $length chars
     */
    public static function shorten($content, $length) {
        return preg_replace(sprintf('#^(.{%d}).{3,}#', $length - 3), '$1...', $content);
    }

    /**
     * Restore a splitted string to camelcase
     * foo-bar-qux will become FooBarQux with upper true and fooBarQux with false
     *
     * @param string $input
     * @param bool   $upper true = upper, false = lower camel case
     * @param string $seperator
     *
     * @return string
     */
    public static function toCamelCase($input, $upper = false, $seperator = '-') {
        $result = '';
        foreach (preg_split("#$seperator#", $input) as $part) {
            $result .= ucfirst($part);
        }
        return $upper ? $result : lcfirst($result);
    }

    /**
     * @param array $attributes
     * @return string
     */
    public function createAttributeString(array $attributes) {
        $parts = array();
        foreach ($attributes as $key => $val) {
            $parts[] = sprintf('%s="%s"', $key, htmlspecialchars($val));
        }
        return implode(' ', $parts);
    }
}
