<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable\Query;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Query\Expr;
use FSi\DoctrineExtensions\Exception\ConditionException;
use FSi\DoctrineExtensions\Exception\InvalidArgumentException;
use FSi\DoctrineExtensions\ORM\QueryBuilder as BaseQueryBuilder;
use FSi\DoctrineExtensions\Translatable\Exception\RuntimeException;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;

class QueryBuilder extends BaseQueryBuilder
{
    /**
     * @var \FSi\DoctrineExtensions\Translatable\TranslatableListener
     */
    private $listener;

    /**
     * @var array
     */
    private $aliasToClassMap = array();

    /**
     * @var array
     */
    private $translationsAliases = array();

    /**
     * @var array
     */
    private $translatableFieldsInSelect = array();

    /**
     * @inheritdoc
     */
    public function add($dqlPartName, $dqlPart, $append = false)
    {
        if ($this->isValidFromPart($dqlPartName, $dqlPart)) {
            $this->addFromExprToAliasMap($dqlPart);
        } elseif ($this->isValidJoinPart($dqlPartName, $dqlPart)) {
            $join = current($dqlPart);
            $this->validateJoinParent($join);
            $this->validateJoinAssociation($join);
            $this->addJoinExprToAliasMap($join);
        } elseif (in_array($dqlPartName, array('from', 'join'))) {
            throw new RuntimeException(sprintf(
                "Trying to add incompatible expression to DQL part '%s' in QueryBuilder",
                $dqlPartName
            ));
        }

        return parent::add($dqlPartName, $dqlPart, $append);
    }

    /**
     * @param string $join
     * @param string $joinType
     * @param mixed $locale
     * @param string $alias
     * @param string $localeParameter
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function joinTranslations($join, $joinType = Expr\Join::LEFT_JOIN, $locale = null, $alias = null, $localeParameter = null)
    {
        $this->validateJoinTranslations($join);

        $locale = $this->getCurrentLocale($locale);
        $alias = $this->getJoinTranslationsAlias($alias, $join, $locale);
        $condition = $this->getJoinTranslationsCondition($join, $alias, $localeParameter, $locale);
        $conditionType = $this->getJoinTranslationsConditionType($locale);

        $this->addJoinedTranslationsAlias($join, $locale, $alias);

        switch ($joinType) {
            case Expr\Join::INNER_JOIN:
                return $this->innerJoin($join, $alias, $conditionType, $condition);
            case Expr\Join::LEFT_JOIN:
                return $this->leftJoin($join, $alias, $conditionType, $condition);
            default:
                throw new InvalidArgumentException(sprintf('Unknown join type "%s"', $joinType));
        }
    }

    /**
     * @param string $join
     * @param string $joinType
     * @param string $alias
     * @param string $localeParameter
     * @return QueryBuilder
     */
    public function joinAndSelectCurrentTranslations($join, $joinType = Expr\Join::LEFT_JOIN, $alias = null, $localeParameter = null)
    {
        $locale = $this->getTranslatableListener()->getLocale();
        if (isset($locale)) {
            $this->joinAndSelectTranslationsOnce($join, $joinType, $locale, $alias, $localeParameter);
        }

        return $this;
    }

    /**
     * @param string $join
     * @param string $joinType
     * @param string $alias
     * @param string $localeParameter
     * @return QueryBuilder
     */
    public function joinAndSelectDefaultTranslations($join, $joinType = Expr\Join::LEFT_JOIN, $alias = null, $localeParameter = null)
    {
        $defaultLocale = $this->getTranslatableListener()->getDefaultLocale();
        if (isset($defaultLocale)) {
            $this->joinAndSelectTranslationsOnce($join, $joinType, $defaultLocale, $alias, $localeParameter);
        }

        return $this;
    }

    /**
     * @param string $alias
     * @param string $field
     * @param mixed $value
     * @param mixed $locale
     * @return QueryBuilder
     * @throws ConditionException
     */
    public function addTranslatableWhere($alias, $field, $value, $locale = null)
    {
        $meta = $this->getClassMetadata($this->getClassByAlias($alias));
        $checkField = $field;
        if ($this->isTranslatableProperty($alias, $field)) {
            $meta = $this->getTranslationMetadata($alias, $field);
            $checkField = $this->getTranslationField($alias, $field);
        }
        if ($meta->isCollectionValuedAssociation($checkField)) {
            $this->addTranslatableWhereOnCollection($alias, $field, $value, $locale);
        } else {
            $this->addTranslatableWhereOnField($alias, $field, $value, $locale);
        }

        return $this;
    }

    /**
     * @param string $alias
     * @param string $field
     * @param string $order
     * @param mixed $locale
     * @return QueryBuilder
     */
    public function addTranslatableOrderBy($alias, $field, $order = null, $locale = null)
    {
        $this->addOrderBy(
            $this->getTranslatableFieldExprWithOptionalHiddenSelect($alias, $field, true, $locale),
            $order
        );

        return $this;
    }

    /**
     * @param string $alias
     * @param string $property
     * @param mixed $locale
     * @return string
     */
    public function getTranslatableFieldExpr($alias, $property, $locale = null)
    {
        return $this->getTranslatableFieldExprWithOptionalHiddenSelect($alias, $property, false, $locale);
    }

    /**
     * @param string $alias
     * @param string $field
     * @param bool $addHiddenSelect
     * @param mixed $locale
     * @return string
     */
    private function getTranslatableFieldExprWithOptionalHiddenSelect($alias, $field, $addHiddenSelect, $locale = null)
    {
        if (!$this->isTranslatableProperty($alias, $field)) {
            return sprintf('%s.%s', $alias, $field);
        }

        $this->validateCurrentLocale($locale);
        $this->joinCurrentTranslationsOnce($alias, $field, $locale);
        if (!$this->hasDefaultLocaleDifferentThanCurrentLocale($locale)) {
            return $this->getTranslatableFieldSimpleExpr($alias, $field, $locale);
        }

        $this->joinDefaultTranslationsOnce($alias, $field);
        if ($addHiddenSelect) {
            return $this->getHiddenSelectTranslatableFieldConditionalExpr($alias, $field, $locale);
        } else {
            return $this->getTranslatableFieldConditionalExpr($alias, $field, $locale);
        }
    }

    /**
     * @param string $alias
     * @param string $field
     * @param string $exprTemplate
     * @param bool $doJoin
     * @param mixed $locale
     * @return string
     */
    private function getTranslatableCollectionExpr($alias, $field, $exprTemplate, $doJoin, $locale = null)
    {
        if (!$this->isTranslatableProperty($alias, $field)) {
            return sprintf($exprTemplate, sprintf('%s.%s', $alias, $field));
        }

        $this->validateCurrentLocale($locale);
        $this->joinCurrentTranslationsOnce($alias, $field, $locale);
        $currentLocale = $this->getCurrentLocale($locale);
        if (!$this->hasDefaultLocaleDifferentThanCurrentLocale($locale)) {
            return $this->getTranslatableCollectionTranslationExpr($alias, $field, $exprTemplate, $doJoin, $currentLocale);
        }

        $this->joinDefaultTranslationsOnce($alias, $field);
        $defaultLocale = $this->getTranslatableListener()->getDefaultLocale();

        $currentTranslationsAlias = $this->getJoinedCurrentTranslationsAlias($alias, $field, $currentLocale);
        $translationIdentity = $this->getClassMetadata($this->getClassByAlias($currentTranslationsAlias))
            ->getSingleIdentifierFieldName();

        return sprintf(
            'CASE WHEN %s.%s IS NOT NULL AND %s THEN TRUE WHEN %s THEN TRUE ELSE FALSE END = TRUE',
            $currentTranslationsAlias,
            $translationIdentity,
            $this->getTranslatableCollectionTranslationExpr($alias, $field, $exprTemplate, $doJoin, $currentLocale),
            $this->getTranslatableCollectionTranslationExpr($alias, $field, $exprTemplate, $doJoin, $defaultLocale)
        );
    }

    /**
     * @param string $alias
     * @param string $field
     * @param string $exprTemplate
     * @param bool $doJoin
     * @param mixed $locale
     * @return string
     */
    private function getTranslatableCollectionTranslationExpr($alias, $field, $exprTemplate, $doJoin, $locale)
    {
        $translationsAssociation = $this->getTranslationAssociation($alias, $field);
        $translationsJoin = $this->getTranslationsJoin($alias, $translationsAssociation);
        $translationsAlias = $this->getJoinedTranslationsAlias($translationsJoin, $locale);
        if ($doJoin) {
            $joinAlias = $this->getCollectionJoinAlias($translationsAlias, $field);
            $this->leftJoin(sprintf('%s.%s', $translationsAlias, $field), $joinAlias);

            return sprintf($exprTemplate, $joinAlias);
        } else {
            return sprintf($exprTemplate, sprintf('%s.%s', $translationsAlias, $field));
        }
    }

    /**
     * @return \FSi\DoctrineExtensions\Translatable\TranslatableListener
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\RuntimeException
     */
    private function getTranslatableListener()
    {
        if (!isset($this->listener)) {
            $evm = $this->getEntityManager()->getEventManager();
            foreach ($evm->getListeners() as $listeners) {
                foreach ($listeners as $listener) {
                    if ($listener instanceof TranslatableListener) {
                        $this->listener = $listener;
                    }
                }
            }
        }

        if (isset($this->listener)) {
            return $this->listener;
        }

        throw new RuntimeException('Cannot find TranslatableListener in EntityManager\'s EventManager');
    }

    /**
     * @param $alias
     * @param $property
     * @return string
     */
    private function isTranslatableProperty($alias, $property)
    {
        $translatableProperties = $this->getTranslatableMetadata($this->getClassByAlias($alias))
            ->getTranslatableProperties();

        foreach ($translatableProperties as $translationAssociation => $properties) {
            if (isset($properties[$property])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $locale
     * @throws \RuntimeException
     */
    private function validateCurrentLocale($locale = null)
    {
        $locale = $this->getCurrentLocale($locale);

        if (!isset($locale)) {
            throw new RuntimeException('At least current locale must be set on TranslatableListener');
        }
    }

    /**
     * @param string $dqlPartName
     * @param mixed $dqlPart
     * @return bool
     */
    private function isValidFromPart($dqlPartName, $dqlPart)
    {
        return ($dqlPartName == 'from') && ($dqlPart instanceof Expr\From);
    }

    /**
     * @param \Doctrine\ORM\Query\Expr\From $from
     */
    private function addFromExprToAliasMap(Expr\From $from)
    {
        $this->aliasToClassMap[$from->getAlias()] = $from->getFrom();
    }

    /**
     * @param string $dqlPartName
     * @param mixed $dqlPart
     * @return bool
     */
    private function isValidJoinPart($dqlPartName, $dqlPart)
    {
        return ($dqlPartName == 'join') && is_array($dqlPart) && (current($dqlPart) instanceof Expr\Join);
    }

    /**
     * @param \Doctrine\ORM\Query\Expr\Join $join
     */
    private function addJoinExprToAliasMap(Expr\Join $join)
    {
        $alias = $this->getJoinParentAlias($join->getJoin());
        $association = $this->getJoinAssociation($join->getJoin());

        $this->aliasToClassMap[$join->getAlias()] = $this->getClassMetadata($this->getClassByAlias($alias))
            ->getAssociationTargetClass($association);
    }

    /**
     * @param string $alias
     * @return string
     * @throws \RuntimeException
     */
    private function getClassByAlias($alias)
    {
        if (!isset($this->aliasToClassMap[$alias])) {
            throw new RuntimeException(sprintf(
                'Alias "%s" is not present in QueryBuilder',
                $alias
            ));
        }

        return $this->aliasToClassMap[$alias];
    }

    /**
     * @param string $class
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    private function getClassMetadata($class)
    {
        return $this->getEntityManager()->getClassMetadata($class);
    }

    /**
     * @param string $class
     * @return \FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata
     */
    private function getTranslatableMetadata($class)
    {
        return $this->getTranslatableListener()
            ->getExtendedMetadata($this->getEntityManager(), $class);
    }

    /**
     * @param Expr\Join $join
     * @return string
     * @throws \RuntimeException
     */
    private function validateJoinParent(Expr\Join $join)
    {
        $alias = $this->getJoinParentAlias($join->getJoin());
        if (!isset($this->aliasToClassMap[$alias])) {
            throw new RuntimeException(
                sprintf(
                    "Cannot find alias %s in QueryBuilder (%s)",
                    $alias,
                    $this->getDQL()
                )
            );
        }
    }

    /**
     * @param Expr\Join $join
     * @return array
     * @throws \RuntimeException
     */
    private function validateJoinAssociation(Expr\Join $join)
    {
        $alias = $this->getJoinParentAlias($join->getJoin());
        $association = $this->getJoinAssociation($join->getJoin());
        $parentClassMetadata = $this->getClassMetadata($this->getClassByAlias($alias));
        if (!$parentClassMetadata->hasAssociation($association)) {
            throw new RuntimeException(
                sprintf(
                    "Cannot find association named %s in class %s",
                    $association,
                    $parentClassMetadata->getName()
                )
            );
        }
    }

    /**
     * @param $join
     * @throws \RuntimeException
     */
    private function validateJoinTranslations($join)
    {
        $translatableAlias = $this->getJoinParentAlias($join);
        $translationAssociation = $this->getJoinAssociation($join);
        $translatableMetadata = $this->getTranslatableMetadata($this->getClassByAlias($translatableAlias));
        $translatableProperties = $translatableMetadata->getTranslatableProperties();

        if (!isset($translatableProperties[$translationAssociation])) {
            throw new RuntimeException(
                sprintf(
                    "'%s' is not an association with translation entity in class '%s'",
                    $translationAssociation,
                    $this->aliasToClassMap[$translatableAlias]
                )
            );
        }
    }

    /**
     * @param string $join
     * @return string
     */
    private function getJoinParentAlias($join)
    {
        return substr($join, 0, strpos($join, '.'));
    }

    /**
     * @param string $join
     * @return string
     */
    private function getJoinAssociation($join)
    {
        return substr($join, strpos($join, '.') + 1);
    }

    /**
     * @param string $alias
     * @param string $join
     * @param mixed $locale
     * @return string
     */
    private function getJoinTranslationsAlias($alias, $join, $locale)
    {
        if (isset($alias)) {
            return $alias;
        }

        return sprintf('%s%s', str_replace('.', '', $join), (string) $locale);
    }

    /**
     * @param mixed $locale
     * @return string
     */
    private function getJoinTranslationsConditionType($locale = null)
    {
        if (isset($locale)) {
            return Expr\Join::WITH;
        } else {
            return Expr\Join::ON;
        }
    }

    /**
     * @param string $join
     * @param string $alias
     * @param string $localeParameter
     * @param mixed $locale
     * @return null|string
     */
    private function getJoinTranslationsCondition($join, $alias, $localeParameter, $locale)
    {
        if (!isset($locale)) {
            return null;
        }

        $localeParameter = $this->getJoinTranslationsLocaleParameter($alias, $localeParameter);
        $this->setParameter($localeParameter, $locale);
        return $this->getJoinTranslationsLocaleCondition($alias, $join, $localeParameter);
    }

    /**
     * @param $alias
     * @param $localeParameter
     * @return string
     */
    private function getJoinTranslationsLocaleParameter($alias, $localeParameter)
    {
        if (isset($localeParameter)) {
            return $localeParameter;
        }

        return sprintf('%sloc', $alias);
    }

    /**
     * @param string $alias
     * @param string $join
     * @param string $localeParameter
     * @return string
     */
    private function getJoinTranslationsLocaleCondition($alias, $join, $localeParameter)
    {
        $translatableAlias = $this->getJoinParentAlias($join);
        $translationAssociation = $this->getJoinAssociation($join);
        $translationClass = $this->getClassMetadata($this->getClassByAlias($translatableAlias))
            ->getAssociationTargetClass($translationAssociation);
        $translationMetadata = $this->getTranslatableMetadata($translationClass);
        return sprintf('%s.%s = :%s', $alias, $translationMetadata->localeProperty, $localeParameter);
    }

    /**
     * @param string $join
     * @param mixed $locale
     * @param string $alias
     */
    private function addJoinedTranslationsAlias($join, $locale, $alias)
    {
        if (!isset($this->translationsAliases[$join])) {
            $this->translationsAliases[$join] = array();
        }
        $this->translationsAliases[$join][$locale] = $alias;
    }

    /**
     * @param string $join
     * @param mixed $locale
     * @return bool
     */
    private function hasJoinedTranslationsAlias($join, $locale)
    {
        return isset($this->translationsAliases[$join][$locale]);
    }

    /**
     * @param string $join
     * @param mixed $locale
     * @return string
     */
    private function getJoinedTranslationsAlias($join, $locale)
    {
        if (isset($this->translationsAliases[$join][$locale])) {
            return $this->translationsAliases[$join][$locale];
        }
    }

    /**
     * @param $alias
     * @param $property
     * @return string
     */
    private function getTranslationField($alias, $property)
    {
        $translatableProperties = $this->getTranslatableMetadata($this->getClassByAlias($alias))
            ->getTranslatableProperties();

        foreach ($translatableProperties as $translationAssociation => $properties) {
            if (isset($properties[$property])) {
                return $properties[$property];
            }
        }

        $this->throwUnknownTranslatablePropertyException($alias, $property);
    }

    /**
     * @param $alias
     * @param $property
     * @return string
     */
    private function getTranslationAssociation($alias, $property)
    {
        $translatableProperties = $this->getTranslatableMetadata($this->getClassByAlias($alias))
            ->getTranslatableProperties();

        foreach ($translatableProperties as $translationAssociation => $properties) {
            if (isset($properties[$property])) {
                return $translationAssociation;
            }
        }

        $this->throwUnknownTranslatablePropertyException($alias, $property);
    }

    /**
     * @param string $alias
     * @param string $property
     * @param mixed $locale
     */
    private function joinCurrentTranslationsOnce($alias, $property, $locale = null)
    {
        $translationAssociation = $this->getTranslationAssociation($alias, $property);
        $join = $this->getTranslationsJoin($alias, $translationAssociation);
        $locale = $this->getCurrentLocale($locale);

        $this->joinTranslationsOnce($join, Expr\Join::LEFT_JOIN, $locale);
    }

    /**
     * @param string $alias
     * @param string $property
     */
    private function joinDefaultTranslationsOnce($alias, $property)
    {
        $translationAssociation = $this->getTranslationAssociation($alias, $property);
        $join = $this->getTranslationsJoin($alias, $translationAssociation);
        $this->joinTranslationsOnce($join, Expr\Join::LEFT_JOIN, $this->getTranslatableListener()->getDefaultLocale());
    }

    /**
     * @param string $join
     * @param string $joinType
     * @param mixed $locale
     * @param string $alias
     * @param string $localeParameter
     * @internal param string $property
     */
    private function joinTranslationsOnce($join, $joinType, $locale, $alias = null, $localeParameter = null)
    {
        if (!$this->hasJoinedTranslationsAlias($join, $locale)) {
            $this->joinTranslations($join, $joinType, $locale, $alias, $localeParameter);
        }
    }

    /**
     * @param string $join
     * @param string $joinType
     * @param mixed $locale
     * @param string $alias
     * @param string $localeParameter
     * @internal param string $property
     */
    private function joinAndSelectTranslationsOnce($join, $joinType, $locale, $alias = null, $localeParameter = null)
    {
        if (!$this->hasJoinedTranslationsAlias($join, $locale)) {
            $this->joinTranslations($join, $joinType, $locale, $alias, $localeParameter);
            $this->addSelect($alias);
        }
    }

    /**
     * @param string $alias
     * @param string $property
     * @throws \RuntimeException
     */
    private function throwUnknownTranslatablePropertyException($alias, $property)
    {
        throw new RuntimeException(
            sprintf(
                'Unknown translatable property "%s" in class "%s"',
                $property,
                $this->getClassByAlias($alias)
            )
        );
    }

    /**
     * @param $locale
     * @return bool
     */
    private function hasDefaultLocaleDifferentThanCurrentLocale($locale = null)
    {
        $locale = $this->getCurrentLocale($locale);

        return (null !== $this->getTranslatableListener()->getDefaultLocale()) &&
            ($locale !== $this->getTranslatableListener()->getDefaultLocale());
    }

    /**
     * @param string $alias
     * @param string $property
     * @param mixed $locale
     * @return string
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    private function getTranslatableFieldConditionalExpr($alias, $property, $locale = null)
    {
        $currentTranslationsAlias = $this->getJoinedCurrentTranslationsAlias($alias, $property, $locale);
        $defaultTranslationsAlias = $this->getJoinedDefaultTranslationsAlias($alias, $property);
        $translationIdentity = $this->getClassMetadata($this->getClassByAlias($currentTranslationsAlias))
            ->getSingleIdentifierFieldName();
        $translationField = $this->getTranslationField($alias, $property);

        return sprintf(
            'CASE WHEN %s.%s IS NOT NULL THEN %s.%s ELSE %s.%s END',
            $currentTranslationsAlias,
            $translationIdentity,
            $currentTranslationsAlias,
            $translationField,
            $defaultTranslationsAlias,
            $translationField
        );
    }

    /**
     * @param string $alias
     * @param string $property
     * @param mixed $locale
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    private function getHiddenSelectTranslatableFieldConditionalExpr($alias, $property, $locale = null)
    {
        $hiddenSelect = sprintf('%s%s', $alias, $property);
        if (!isset($this->translatableFieldsInSelect[$hiddenSelect])) {
            $this->addSelect(sprintf(
                '%s HIDDEN %s',
                $this->getTranslatableFieldConditionalExpr($alias, $property, $locale),
                $hiddenSelect
            ));
            $this->translatableFieldsInSelect[$hiddenSelect] = $hiddenSelect;
        }

        return $this->translatableFieldsInSelect[$hiddenSelect];
    }

    /**
     * @param string $alias
     * @param string $property
     * @return array
     */
    private function getJoinedDefaultTranslationsAlias($alias, $property)
    {
        $translationAssociation = $this->getTranslationAssociation($alias, $property);
        $join = $this->getTranslationsJoin($alias, $translationAssociation);
        $defaultLocale = $this->getTranslatableListener()->getDefaultLocale();
        $defaultTranslationsAlias = $this->getJoinedTranslationsAlias($join, $defaultLocale);

        return $defaultTranslationsAlias;
    }

    /**
     * @param string $alias
     * @param string $property
     * @param mixed $locale
     * @return string
     */
    private function getJoinedCurrentTranslationsAlias($alias, $property, $locale = null)
    {
        $translationAssociation = $this->getTranslationAssociation($alias, $property);
        $join = $this->getTranslationsJoin($alias, $translationAssociation);
        $locale = $this->getCurrentLocale($locale);

        return $this->getJoinedTranslationsAlias($join, $locale);
    }

    /**
     * @param string $alias
     * @param string $property
     * @param mixed $locale
     * @return string
     */
    private function getTranslatableFieldSimpleExpr($alias, $property, $locale = null)
    {
        $locale = $this->getCurrentLocale($locale);
        $translationsAssociation = $this->getTranslationAssociation($alias, $property);
        $translationsJoin = $this->getTranslationsJoin($alias, $translationsAssociation);

        return sprintf(
            '%s.%s',
            $this->getJoinedTranslationsAlias($translationsJoin, $locale),
            $this->getTranslationField($alias, $property)
        );
    }

    /**
     * @param mixed $locale
     * @return mixed
     */
    private function getCurrentLocale($locale = null)
    {
        if (isset($locale)) {
            return $locale;
        }

        return $this->getTranslatableListener()->getLocale();
    }

    /**
     * @param $alias
     * @param $translationAssociation
     * @return string
     */
    private function getTranslationsJoin($alias, $translationAssociation)
    {
        return sprintf('%s.%s', $alias, $translationAssociation);
    }

    /**
     * @param string $alias
     * @param string $field
     * @param mixed $value
     * @param mixed $locale
     */
    private function addTranslatableWhereOnCollection($alias, $field, $value, $locale = null)
    {
        $parameter = $this->getTranslatableValueParameter($alias, $field);

        if (null === $value) {
            $fieldExpr = 'SIZE(%s) = 0';
            $collectionExpr = $this->getTranslatableCollectionExpr($alias, $field, $fieldExpr, false, $locale);
            $this->andWhere($collectionExpr);
        } elseif (is_array($value)) {
            if ($this->isTranslatableProperty($alias, $field)) {
                $fieldExpr = $this->expr()->in('%s', $parameter);
                $collectionExpr = $this->getTranslatableCollectionExpr($alias, $field, $fieldExpr, true, $locale);
                $this->andWhere($collectionExpr);
            } else {
                $fieldExpr = $this->getTranslatableFieldExpr($alias, $field, $locale);
                $joinAlias = $this->getCollectionJoinAlias($alias, $field);
                $this->leftJoin($fieldExpr, $joinAlias);
                $this->andWhere($this->expr()->in($joinAlias, $parameter));
            }
            $this->setParameter($parameter, $value);
        } else {
            $fieldExpr = sprintf('%s MEMBER OF %s', $parameter, '%s');
            $collectionExpr = $this->getTranslatableCollectionExpr($alias, $field, $fieldExpr, false, $locale);
            $this->andWhere($collectionExpr);
            $this->setParameter($parameter, $value);
        }
    }

    /**
     * @param string $alias
     * @param string $field
     * @param mixed $value
     * @param mixed $locale
     */
    private function addTranslatableWhereOnField($alias, $field, $value, $locale = null)
    {
        $fieldExpr = $this->getTranslatableFieldExpr($alias, $field, $locale);
        $parameter = $this->getTranslatableValueParameter($alias, $field);

        if (null === $value) {
            $this->andWhere($this->expr()->isNull($fieldExpr));
        } elseif (is_array($value)) {
            $this->andWhere($this->expr()->in($fieldExpr, $parameter));
            $this->setParameter($parameter, $value);
        } else {
            $this->andWhere($this->expr()->eq($fieldExpr, $parameter));
            $this->setParameter($parameter, $value);
        }
    }

    /**
     * @param string $alias
     * @param string $field
     * @return string
     */
    private function getTranslatableValueParameter($alias, $field)
    {
        return sprintf(':%s%sval', $alias, $field);
    }

    /**
     * @param $alias
     * @param $field
     * @return string
     */
    private function getCollectionJoinAlias($alias, $field)
    {
        return sprintf('%s%sjoin', $alias, $field);
    }

    /**
     * @param string $alias
     * @param string $field
     * @return \Doctrine\ORM\Mapping\ClassMetadata
     */
    private function getTranslationMetadata($alias, $field)
    {
        return $this->getClassMetadata(
            $this->getClassMetadata($this->getClassByAlias($alias))
                ->getAssociationTargetClass(
                    $this->getTranslationAssociation($alias, $field)
                )
        );
    }
}
