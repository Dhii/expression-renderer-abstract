<?php

namespace Dhii\Expression\Renderer;

use Dhii\Expression\TermInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Output\Exception\TemplateRenderExceptionInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use OutOfRangeException;

/**
 * Common functionality for objects that can delegate term rendering to a renderer.
 *
 * @since [*next-version*]
 */
trait DelegateRenderTermCapableTrait
{
    /**
     * Delegates the rendering for the given term  to another renderer.
     *
     * @since [*next-version*]
     *
     * @param TermInterface $term The term to render.
     *
     * @throws RendererExceptionInterface       If the renderer encountered an error.
     * @throws TemplateRenderExceptionInterface If the renderer failed to render the term.
     *
     * @return string|Stringable The rendered term.
     */
    protected function _delegateRenderTerm(TermInterface $term)
    {
        try {
            return $this->_getTermDelegateRenderer($term)->render($term);
        } catch (OutOfRangeException $outOfRangeException) {
            throw $this->_createRendererException(
                $this->__('Could not find a delegate renderer for the given term.'),
                null,
                $outOfRangeException
            );
        }
    }

    /**
     * Retrieves the delegate renderer for a given term.
     *
     * @since [*next-version*]
     *
     * @param TermInterface $term The term type for which to retrieve a renderer.
     *
     * @return TemplateInterface The renderer instance.
     *
     * @throws OutOfRangeException If no renderer can be retrieved for the given term type.
     */
    abstract protected function _getTermDelegateRenderer(TermInterface $term);

    /**
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     *
     * @return RendererExceptionInterface The new exception.
     */
    abstract protected function _createRendererException(
        $message = null,
        $code = null,
        RootException $previous = null
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
