<?php

/**
 * This file is part of the ChillDev Spintax library.
 *
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
 * @version 0.0.1
 * @since 0.0.1
 * @package ChillDev\Spintax
 */

namespace ChillDev\Spintax;

/**
 * Spintax parser.
 *
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
 * @version 0.0.1
 * @since 0.0.1
 * @package ChillDev\Spintax
 */
class Parser
{
    /**
     * Parses spintax article.
     *
     * @param string $string Source content.
     * @return Content Parsed spintax tree.
     * @version 0.0.1
     * @since 0.0.1
     */
    public static function parse($string)
    {
        $root = new Content();

        // initialize parser nodes
        $current = $root;
        $parent = null;
        $parents = [];
        $tokens = '{}|';
        // this is for simplicity, as default flow is the same like this
        $previous = '}';

        // loop through the string looking for spintax tokens
        $part = strpbrk($string, $tokens);
        while (false !== $part) {
            $token = $part[0];
            $content = substr($string, 0, -strlen($part));
            $string = substr($part, 1);

            switch ($token) {
                // start of new choice
                case '{':
                    // first save plaintext content
                    if ($previous == '|') {
                        $current = new Content($content);
                        $parent->addChild($current);
                    } elseif (!empty($content)) {
                        $current->setContent($content);
                    }

                    // stack parent
                    $parents[] = $parent = $current;
                    break;

                // end of subset
                case '}':
                    if ($previous == '|') {
                        $parent->addChild(new Content($content));
                    } else {
                        $current->setNext(new Content($content));
                    }

                    // un-stack parent
                    $parent = $current = array_pop($parents);

                    // move forward
                    $node = new Content();
                    $current->setNext($node);
                    $current = $node;
                    break;

                // next option
                case '|':
                    $current = new Content($content);

                    $parent->addChild($current);
                    break;
            }
            $previous = $token;

            $part = strpbrk($string, $tokens);
        }

        $current->setContent($string);
        return $root;
    }

    /**
     * Reproduces article content for specified path.
     *
     * @param string|Content $content Source content (or already parsed spintax tree).
     * @param string|int[] $path Path to use to generate the article.
     * @return string Generated article content.
     * @version 0.0.1
     * @since 0.0.1
     */
    public static function replicate($content, $path)
    {
        // parse content
        if (!$content instanceof Content) {
            $content = static::parse($content);
        }

        // build path
        if (!is_array($path)) {
            $path = explode(',', $path);
        }

        return $content->generate($path);
    }
}
