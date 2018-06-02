<?php declare(strict_types=1);

namespace Pionyr\PionyrCz\Entity;

class ArticlePreview extends AbstractArticle
{
    public static function createFromResponseData(\stdClass $responseData): self
    {
        $object = new static();

        static::setCommonArticleResponseDataToObject($responseData, $object);

        return $object;
    }

    /**
     * @return ArticlePreview[]
     */
    public static function createFromResponseDataArray(array $responseDataArray): array
    {
        $entities = [];
        foreach ($responseDataArray as $responseData) {
            $entities[] = self::createFromResponseData($responseData);
        }

        return $entities;
    }
}
