<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 1. 1. 2025
 * Time: 10:12
 */

namespace AceEditorBundle\Tests;

use AceEditorBundle\AutocompleteTreeBuilder;
use PHPUnit\Framework\TestCase;

class AutocompleteTreeBuilderTest extends TestCase
{
    public function testBuildWords(): void
    {
        $autocomplete = [
            "foo" => [
                "bar" => [
                    "baz" => true,
                ],
                "qux" => false,
                "quux" => ["corge", "grault"],
            ],
            "garply" => ["waldo"],
        ];
        $builder = new AutocompleteTreeBuilder($autocomplete);
        $this->assertEquals([
            0 => 'foo',
            1 => 'foo.bar',
            2 => 'foo.bar.baz',
            3 => 'foo.qux',
            4 => 'foo.quux',
            5 => 'foo.quux.corge',
            6 => 'foo.quux.grault',
            7 => 'garply',
            8 => 'garply.waldo',
        ], $builder->buildWords());
    }

    public function testSeparator(): void
    {
        $autocomplete = [
            "foo" => [
                "bar" => [
                    "baz" => true,
                ],
            ],
        ];
        $builder = new AutocompleteTreeBuilder($autocomplete, "->");
        $this->assertEquals([
            "foo",
            "foo->bar",
            "foo->bar->baz",
        ], $builder->buildWords());
    }
}
