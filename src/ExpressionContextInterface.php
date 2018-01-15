<?php

namespace Dhii\Expression\Renderer;

use Psr\Container\ContainerInterface;

/**
 * Something that represents a context for an expression.
 *
 * @since [*next-version*]
 */
interface ExpressionContextInterface extends ContainerInterface
{
    /**
     * The key in the container from which the expression can be retrieved.
     *
     * @since [*next-version*]
     */
    const K_EXPRESSION = 'expression';
}
