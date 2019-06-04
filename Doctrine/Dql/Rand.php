<?php

namespace Ekyna\Bundle\CoreBundle\Doctrine\Dql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer,
    Doctrine\ORM\Query\Parser,
    Doctrine\ORM\Query\SqlWalker;

/**
 * Class Rand
 * @package Ekyna\Bundle\CoreBundle\Doctrine\Dql
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Rand extends FunctionNode
{

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }
}
