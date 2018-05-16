<?php

namespace Dhii\Expression\Renderer;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Common functionality for objects that are aware of an operator string.
 *
 * @since [*next-version*]
 */
trait OperatorStringAwareTrait
{
    /**
     * The operator string.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable
     */
    protected $operatorString;

    /**
     * Retrieves the operator string associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable The operator string.
     */
    protected function _getOperatorString()
    {
        return $this->operatorString;
    }

    /**
     * Sets the operator string for this instance.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $operatorString The operator string to set.
     */
    protected function _setOperatorString($operatorString)
    {
        if (!is_string($operatorString) && !($operatorString instanceof Stringable)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Argument is not a string or stringable object'),
                null,
                null,
                $operatorString
            );
        }

        $this->operatorString = $operatorString;
    }

    /**
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
