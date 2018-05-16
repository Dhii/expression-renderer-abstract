# Dhii - Expression Renderer Abstract

[![Build Status](https://travis-ci.org/Dhii/expression-renderer-abstract.svg?branch=develop)](https://travis-ci.org/Dhii/expression-renderer-abstract)
[![Code Climate](https://codeclimate.com/github/Dhii/expression-renderer-abstract/badges/gpa.svg)](https://codeclimate.com/github/Dhii/expression-renderer-abstract)
[![Test Coverage](https://codeclimate.com/github/Dhii/expression-renderer-abstract/badges/coverage.svg)](https://codeclimate.com/github/Dhii/expression-renderer-abstract/coverage)
[![Latest Stable Version](https://poser.pugx.org/dhii/expression-renderer-abstract/version)](https://packagist.org/packages/dhii/expression-renderer-abstract)
[![This package complies with Dhii standards](https://img.shields.io/badge/Dhii-Compliant-green.svg?style=flat-square)][Dhii]

## Details
Abstract functionality for objects that can render expressions.

### Traits
- [`RenderExpressionTrait`] provides the basic functionality for reading an expression from a render context and passing
it on to an abstracted render method.
- [`RenderExpressionAndTermsCapableTrait`] provides functionality for rendering an expression's terms in sequence, then
compiling those results into a final render. The provided `_renderExpressionAndTerms()` method can be used to
complement `renderExpression()` in [`RenderExpressionTrait`].
- [`DelegateRenderTermCapableTrait`] provides functionality for rendering an expression or term by passing it onto a
delegate renderer, via an abstract delegate renderer getter method. The provided `_delegateRenderTerm()` method can be
used to complement `renderExpressionTerm()` in [`RenderExpressionAndTermsCapableTrait`].
- [`GetTermTypeRendererContainerTrait`] provides functionality for retrieving a renderer that corresponds to a term's type
from a container instance. The provided `_getTermTypeRenderer()` method can be used to complement
`getTermDelegateRenderer` in [`DelegateRenderTermCapableTrait`].
- [`OperatorStringAwareTrait`] provides awareness of an operator string via storage and retrieval methods.


[Dhii]:                                                 https://github.com/Dhii/dhii

[`RenderExpressionTrait`]:                              src/RenderExpressionTrait.php
[`RenderExpressionAndTermsCapableTrait`]:               src/RenderExpressionAndTermsCapableTrait.php
[`DelegateRenderTermCapableTrait`]:                     src/DelegateRenderTermCapableTrait.php
[`GetTermTypeRendererContainerTrait`]:                  src/GetTermTypeRendererContainerTrait.php
[`OperatorStringAwareTrait`]:                           src/OperatorStringAwareTrait.php
