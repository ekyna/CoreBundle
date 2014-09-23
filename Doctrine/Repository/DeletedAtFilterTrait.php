<?php

namespace Ekyna\Bundle\CoreBundle\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait DeletedAtFilterTrait
 * @package Ekyna\Bundle\CoreBundle\Doctrine\Repository
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait DeletedAtFilterTrait
{
    /**
     * @var bool
     */
    protected $_filterDeletedAt = true;

    /**
     * Sets if the query builder should be configured or not.
     *
     * @param $boolean
     */
    public function setFilterDeletedAt($boolean)
    {
        $this->_filterDeletedAt = (bool) $boolean;
    }

    /**
     * Configures the query builder.
     *
     * @param QueryBuilder $qb
     */
    public function addFilterDeletedAtClause(QueryBuilder $qb)
    {
        if ($this->_filterDeletedAt) {
            $qb->andWhere($qb->expr()->isNull($qb->getRootAliases()[0].'.deletedAt'));
        }
    }
}
