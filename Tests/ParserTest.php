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

namespace ChillDev\Spintax\Tests;

use PHPUnit_Framework_TestCase;

use ChillDev\Spintax\Parser;

/**
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright 2014 © by Rafał Wrzeszcz - Wrzasq.pl.
 * @version 0.0.1
 * @since 0.0.1
 * @package ChillDev\Spintax
 */
class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function parse()
    {
        $source = 'I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.';
        $spintax = Parser::parse($source);

        $this->assertInstanceOf('ChillDev\\Spintax\\Content', $spintax, 'Parser::parse() should return instance of ChillDev\\Spintax\\Content.');
        $this->assertEquals($source, (string) $spintax, 'Parser::parse() should map the spintax source as 1-to-1 logical tree.');
    }

    /**
     * @test
     * @version 0.0.1
     * @since 0.0.1
     */
    public function replicate()
    {
        $source = 'I {love {PHP|Java|C|C++|JavaScript|Python}|hate Ruby}.';
        $this->assertEquals('I love PHP.', Parser::replicate($source, '0,0'));
        $this->assertEquals('I love PHP.', Parser::replicate($source, [0, 0]));

        $spintax = Parser::parse($source);
        $this->assertEquals('I love PHP.', Parser::replicate($spintax, '0,0'));
        $this->assertEquals('I love PHP.', Parser::replicate($spintax, [0, 0]));
    }
}
