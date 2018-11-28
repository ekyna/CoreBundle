<?php

namespace Ekyna\Bundle\CoreBundle\Dql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer,
    Doctrine\ORM\Query\Parser,
    Doctrine\ORM\Query\SqlWalker;

/**
 * Class Year
 * @package Ekyna\Bundle\CoreBundle\Dql
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Year extends FunctionNode
{
    /**
     * @var mixed
     */
    public $date;

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return "YEAR(" . $sqlWalker->walkArithmeticPrimary($this->date) . ")";
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
