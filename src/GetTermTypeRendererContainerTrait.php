<?php

namespace Dhii\Expression\Renderer;

use ArrayAccess;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

/**
 * Common functionality for objects that can provide a term type renderer via a container.
 *
 * @since [*next-version*]
 */
trait GetTermTypeRendererContainerTrait
{
    /**
     * Retrieves the renderer for a given term.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable                                  $termType The term type for which to retrieve a renderer.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context  The context.
     *
     * @return TemplateInterface The renderer instance.
     *
     * @throws ContainerExceptionInterface If an error occurred while reading from the container.
     * @throws NotFoundExceptionInterface If no renderer was found for the given term type.
     */
    protected function _getTermTypeRenderer($termType, $context = null)
    {
        $container = $this->_getTermTypeRendererContainer();

        return $this->_containerGet($container, $termType);
    }

    /**
     * Retrieves the container that contains the renderers, keyed by term type.
     *
     * @since [*next-version*]
     *
     * @return array|ArrayAccess|stdClass|ContainerInterface The container that contains the renderer instances, keyed
     *                                                       by term type.
     */
    abstract protected function _getTermTypeRendererContainer();

    /**
     * Retrieves a value from a container or data set.
     *
     * @since [*next-version*]
     *
     * @param array|ArrayAccess|stdClass|ContainerInterface $container The container to read from.
     * @param string|int|float|bool|Stringable              $key       The key of the value to retrieve.
     *
     * @throws InvalidArgumentException If the container or key arguments are invalid.
     * @throws ContainerExceptionInterface If an error occurred while reading from the container.
     * @throws NotFoundExceptionInterface  If the key was not found in the container.
     *
     * @return mixed The value mapped to the given key.
     */
    abstract protected function _containerGet($container, $key);
}
