<?php

namespace Ekyna\Bundle\CoreBundle\Dql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * UnixTimestamp
 *
 * @link labs.ultravioletdesign.co.uk
 * @author Rob Squires <rob@ultravioletdesign.co.uk>
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
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'UNIX_TIMESTAMP(' . $sqlWalker->walkArithmeticExpression($this->dateExpression) . ')';
    }

    /**
     * {@inheritdoc}
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->dateExpression = $parser->ArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
