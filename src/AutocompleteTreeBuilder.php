<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 30. 12. 2024
 * Time: 21:18
 */

namespace AceEditorBundle;

final class AutocompleteTreeBuilder implements AutocompleteBuilderInterface
{
    public function __construct(
        /** @var array<mixed> */
        private readonly array $tree,
        private string $separator = '.'
    ) {
    }

    /** @return string[] */
    public function buildWords(): array
    {
        return $this->populateAutocompleteTree($this->tree, "");
    }

    /**
     * @param array<mixed> $tree
     * @param string $path
     * @return string[]
     */
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
