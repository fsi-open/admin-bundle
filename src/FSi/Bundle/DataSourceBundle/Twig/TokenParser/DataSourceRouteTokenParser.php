<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\Twig\TokenParser;

use FSi\Bundle\DataSourceBundle\Twig\Node\DataSourceRouteNode;

class DataSourceRouteTokenParser extends \Twig_TokenParser
{
    /**
     * {@inheritDoc}
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        $dataSource = $this->parser->getExpressionParser()->parseExpression();
        $route = $this->parser->getExpressionParser()->parseExpression();
        $additional_parameters = new \Twig_Node_Expression_Array(array(), $stream->getCurrent()->getLine());

        if ($this->parser->getStream()->test(\Twig_Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();

            if ($this->parser->getStream()->test(\Twig_Token::PUNCTUATION_TYPE) || $this->parser->getStream()->test(\Twig_Token::NAME_TYPE)) {
                $additional_parameters = $this->parser->getExpressionParser()->parseExpression();
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new DataSourceRouteNode($dataSource, $route, $additional_parameters, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return 'datasource_route';
    }
}
