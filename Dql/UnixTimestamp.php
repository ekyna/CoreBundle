<?php

namespace Ekyna\Bundle\CoreBundle\Dql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer,
    Doctrine\ORM\Query\Parser,
    Doctrine\ORM\Query\SqlWalker;

/**
 * Class UnixTimestamp
 * @package Ekyna\Bundle\CoreBundle\Dql
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UnixTimestamp extends FunctionNode
{
    /**
     * @var mixed
     */
    protected $dateExpression;

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'UNIX_TIMESTAMP(' . $sqlWalker->walkArithmeticExpression($this->dateExpression) . ')';
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->dateExpression = $parser->ArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
