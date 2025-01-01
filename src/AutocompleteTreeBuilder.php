<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 30. 12. 2024
 * Time: 21:18
 */

namespace AceEditorBundle;

final readonly class AutocompleteTreeBuilder implements AutocompleteBuilderInterface
{
    public function __construct(
        private readonly array $tree,
        private string $separator = '.'
    ) {
    }

    public function buildWords(): array
    {
        return $this->populateAutocompleteTree($this->tree, "");
    }

    private function populateAutocompleteTree(array $tree, string $path): array
    {
        $autocompleteWorlds = [ ];
        if ($path) {
            $autocompleteWorlds[] = $path;
        }
        if ($path) {
            $path .= $this->separator;
        }
        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                $autocompleteWorlds = array_merge(
                    $autocompleteWorlds,
                    $this->populateAutocompleteTree($value, $path . $key)
                );
            } elseif (is_string($value)) {
                $autocompleteWorlds[] = $path  . $value;
            } else {
                $autocompleteWorlds[] = $path. $key;
            }
        }

        return $autocompleteWorlds;
    }
}
