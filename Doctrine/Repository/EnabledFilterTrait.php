<?php

namespace Ekyna\Bundle\CoreBundle\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * Trait EnabledFilterTrait
 * @package Ekyna\Bundle\CoreBundle\Doctrine\Repository
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait EnabledFilterTrait
{
    /**
     * @var bool
     */
    protected $_filterEnabled = true;

    /**
     * Sets if the query builder should be configured or not.
     *
     * @param $boolean
     */
    public function setFilterEnabled($boolean)
    {
        $this->_filterEnabled = (bool) $boolean;
    }

    /**
     * Configures the query builder.
     *
     * @param QueryBuilder $qb
     */
    public function addFilterEnabledClause(QueryBuilder $qb)
    {
        if ($this->_filterEnabled) {
            $qb
                ->andWhere($qb->expr()->eq($qb->getRootAliases()[0].'.enabled', ':enabled'))
                ->setParameter('enabled', true)
            ;
        }
    }
}
