<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Constants;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Pionyr\PionyrCz\Constants\ArticleCategory
 */
class ArticleCategoryTest extends TestCase
{
    /** @test */
    public function shouldPrintCategoryDescription(): void
    {
        $category = new ArticleCategory(ArticleCategory::MEZINARODNI);

        $this->assertSame('Mezinárodní', $category->__toString());
    }
}
