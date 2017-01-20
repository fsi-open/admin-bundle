<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable\Model;

interface TranslatableRepositoryInterface
{
    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int $limit
     * @param int $offset
     * @param mixed $locale
     * @return array
     */
    public function findTranslatableBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, $locale = null);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param mixed $locale
     * @return array
     */
    public function findTranslatableOneBy(array $criteria, array $orderBy = null, $locale = null);

    /**
     * Creates query builder for this entity joined with associated translation
     * entity and constrained to current locale of TranslatableListener if it
     * has been set. It also adds second join to translation entity constrained
     * to default locale of TranslatableListener if it has been set.
     *
     * @param string $alias
     * @param string $translationAlias
     * @param string $defaultTranslationAlias
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createTranslatableQueryBuilder($alias, $translationAlias = 't', $defaultTranslationAlias = 'dt');

    /**
     * Returns true if a translation entity for specified base entity and locale exists
     *
     * @param object $object
     * @param mixed $locale
     * @param string $translationAssociation
     * @return bool
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\RuntimeException
     */
    public function hasTranslation($object, $locale, $translationAssociation = 'translations');

    /**
     * Returns existing or newly created translation entity for specified base
     * entity and locale
     *
     * @param object $object
     * @param mixed $locale
     * @param string $translationAssociation
     * @return object existing or new translation entity for specified locale
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\RuntimeException
     */
    public function getTranslation($object, $locale, $translationAssociation = 'translations');

    /**
     * @param $object
     * @param string $translationAssociation
     * @return \Doctrine\Common\Collections\Collection
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\RuntimeException
     */
    public function getTranslations($object, $translationAssociation = 'translations');

    /**
     * @param object $object
     * @param mixed $locale
     * @param string $translationAssociation
     * @return object|null
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\RuntimeException
     */
    public function findTranslation($object, $locale, $translationAssociation = 'translations');
}
