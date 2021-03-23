<?php declare(strict_types=1);

use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestClassRequiresCoversFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SKIP, [
        'PHP_CodeSniffer\Standards\Generic\Sniffs\NamingConventions\UpperCaseConstantNameSniff'
        . '.ClassConstantNotUpperCase' => [
            'src/Constants/Region.php',
        ],
        'PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\ForbiddenFunctionsSniff.Found' =>
            ['examples/*'],
    ]);

    $containerConfigurator->import(__DIR__ . '/vendor/lmc/coding-standard/ecs.php');

    $services = $containerConfigurator->services();

    // Tests must have @test annotation
    $services->set(PhpUnitTestAnnotationFixer::class)
        ->call('configure', [['style' => 'annotation']]);

    // Require `@covers` annotation
    $services->set(PhpUnitTestClassRequiresCoversFixer::class);

    // Force line length
    $services->set(LineLengthFixer::class)
        ->call(
            'configure',
            [['line_length' => 120, 'break_long_lines' => true, 'inline_short_lines' => false]]
        );
};
