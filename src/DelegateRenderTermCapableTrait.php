<?php

namespace Dhii\Expression\Renderer;

use Dhii\Expression\TermInterface;
use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Output\Exception\TemplateRenderExceptionInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Common functionality for objects that can delegate expression rendering by term type.
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
     * @return string|Stringable The rendered term.
     *
     * @throws RendererExceptionInterface If the renderer encountered an error.
     * @throws TemplateRenderExceptionInterface If the renderer failed to render the term.
     */
    protected function _delegateRenderTerm(TermInterface $term)
    {
        return $this->_getTermDelegateRenderer($term->getType())->render($term);
    }

    /**
     * Retrieves the delegate renderer for a given term.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $termType The term type for which to retrieve a renderer.
     *
     * @return TemplateInterface The renderer instance.
     */
    abstract protected function _getTermDelegateRenderer($termType);
}
