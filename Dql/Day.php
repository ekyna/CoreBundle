<?php

namespace Ekyna\Bundle\CoreBundle\Dql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * "DAY" "(" SimpleArithmeticExpression ")". Modified from DoctrineExtensions\Query\Mysql\Year
 *
 * @package     Ekyna\Bundle\CoreBundle\Dql
 * @author      Rafael Kassner <kassner@gmail.com>
 * @author      Sarjono Mukti Aji <me@simukti.net>
 * @license     MIT License
 */
class Day extends FunctionNode
{
    /**
     * @var mixed
     */
    public $date;

    /**
     * {@inheritdoc}
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return "DAY(" . $sqlWalker->walkArithmeticPrimary($this->date) . ")";
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
