<?php

namespace Dhii\Expression\Renderer;

use Dhii\Expression\TermInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Output\Exception\TemplateRenderExceptionInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
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
        return $this->_getTermDelegateRenderer($term)->render($term);
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
}
