<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\Form;

use FSi\Component\Files\WebFile;

use function mb_strlen;
use function mb_substr;
use function strip_tags;

final class DefaultTranslation
{
    private const MAX_PREFACE_LENGTH = 255;
    private const BASE_ALLOWED_TAGS = ['<b>', '<i>', '<strong>'];
    private const EXPANDED_ALLOWED_TAGS = ['<p>', '<ul>', '<ol>', '<li>'];

    private ?string $preface;
    /**
     * @var WebFile|int|string
     */
    private $value;

    /**
     * @param WebFile|int|string $value
     */
    public function __construct($value)
    {
        if (true === is_string($value)) {
            $strippedValue = strip_tags($value);
            if (self::MAX_PREFACE_LENGTH < mb_strlen($strippedValue)) {
                $allowedTags = array_merge(self::BASE_ALLOWED_TAGS, self::EXPANDED_ALLOWED_TAGS);
                $this->preface = mb_substr($strippedValue, 0, self::MAX_PREFACE_LENGTH);
            } else {
                $allowedTags = self::BASE_ALLOWED_TAGS;
                $this->preface = null;
            }

            $value = strip_tags($value, $allowedTags);
        } else {
            $this->preface = null;
        }

        $this->value = $value;
    }

    public function getPreface(): ?string
    {
        return $this->preface;
    }

    /**
     * @return WebFile|int|string
     */
    public function getValue()
    {
        return $this->value;
    }
}
