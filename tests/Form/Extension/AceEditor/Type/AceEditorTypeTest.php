<?php

declare(strict_types=1);

namespace AceEditorBundle\Tests\Form\Extension\AceEditor\Type;

use AceEditorBundle\AutocompleteTreeBuilder;
use AceEditorBundle\Form\Extension\AceEditor\Type\AceEditorType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AceEditorTypeTest extends TestCase
{
    /** @var AceEditorType<mixed> */
    private AceEditorType $formType;

    public function setUp(): void
    {
        $this->formType = new AceEditorType(false);
    }

    public function testGetParent(): void
    {
        $this->assertSame(TextareaType::class, $this->formType->getParent());
    }

    public function testOptionsWidthHeightUnitNormalizer(): void
    {
        $opts = new OptionsResolver();
        $this->formType->configureOptions($opts);

        $resolved = $opts->resolve(['width' => null, 'height' => null]);
        $this->assertSame(['value' => null, 'unit' => 'px'], $resolved['width']);
        $this->assertSame(['value' => null, 'unit' => 'px'], $resolved['height']);

        $resolved = $opts->resolve(['width' => 20, 'height' => '20']);
        $this->assertSame(['value' => 20, 'unit' => 'px'], $resolved['width']);
        $this->assertSame(['value' => '20', 'unit' => 'px'], $resolved['height']);

        $resolved = $opts->resolve(['width' => '50%']);
        $this->assertSame(['value' => '50', 'unit' => '%'], $resolved['width']);

        $resolved = $opts->resolve(['width' => '101foo']);
        $this->assertSame(['value' => '101foo', 'unit' => 'px'], $resolved['width']);
    }

    public function testPopulateAutocompleteWorlds(): void
    {
        $autocomplete = [
            "foo"=>[
                "bar"=>[
                    "baz"=>true,
                ],
                "qux"=>false,
                "quux"=>["corge","grault"],
            ],
            "garply"=>["waldo"],
        ];
        $opts = new OptionsResolver();
        $this->formType->configureOptions($opts);
        $resolved = $opts->resolve([
            'autocomplete_worlds'=>["foos"],
            'autocomplete_builder' => new AutocompleteTreeBuilder($autocomplete),
        ]);

        $view = new FormView();
        $form = $this->createMock(FormInterface::class);
        $this->formType->buildView($view, $form, $resolved);
        $worlds = $view->vars['autocomplete_worlds'];


        $expected = [
            0 => 'foos',
            1 => 'foo',
            2 => 'foo.bar',
            3 => 'foo.bar.baz',
            4 => 'foo.qux',
            5 => 'foo.quux',
            6 => 'foo.quux.corge',
            7 => 'foo.quux.grault',
            8 => 'garply',
            9 => 'garply.waldo',
        ];

        $this->assertSame($expected, $worlds);

    }
}
