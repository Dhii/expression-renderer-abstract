<?php

namespace Dhii\Expression\Renderer\FuncTest;

use Dhii\Expression\Renderer\OperatorStringAwareTrait as TestSubject;
use \InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class OperatorStringAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Expression\Renderer\OperatorStringAwareTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function createInstance()
    {
        // Create mock
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods(['__', '_createInvalidArgumentException'])
                     ->getMockForTrait();

        $mock->method('__')->willReturnArgument(0);
        $mock->method('_createInvalidArgumentException')->willReturnCallback(
            function($msg = '', $code = 0, $prev = null) {
                return new InvalidArgumentException($msg, $code, $prev);
            }
        );

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the getter and setter methods to ensure correct assignment and retrieval.
     *
     * @since [*next-version*]
     */
    public function testGetSetOperatorString()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $input = uniqid('operator-');

        $reflect->_setOperatorString($input);

        $this->assertSame($input, $reflect->_getOperatorString(), 'Set and retrieved value are not the same.');
    }

    /**
     * Tests the getter and setter methods to ensure correct assignment and retrieval of stringable objects.
     *
     * @since [*next-version*]
     */
    public function testGetSetOperatorStringable()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $stringable = $this->getMockBuilder('Dhii\Util\String\StringableInterface')
                      ->setMethods(['__toString'])
                        ->getMockForAbstractClass();
        $stringable->method('__toString')->willReturn(uniqid('operator-'));

        $reflect->_setOperatorString($stringable);

        $this->assertSame($stringable, $reflect->_getOperatorString(), 'Set and retrieved value are not the same.');
    }

    /**
     * Tests the getter and setter methods with an invalid value to assert whether an exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testGetSetOperatorStringInvalid()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $input = new stdClass();

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_setOperatorString($input);
    }
}
