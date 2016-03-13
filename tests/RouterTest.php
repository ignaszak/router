<?php

namespace Test;

use Ignaszak\Router\Router;
use Test\Mock\MockTest;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Start
     */
    private $router;

    public function setUp()
    {
        $this->router = new Router(
            $this->getMockBuilder('Ignaszak\Router\Route')
                ->disableOriginalConstructor()->getMock()
        );
    }

    public function testConstructor()
    {
        $conf = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'conf'
        );
        $formatter = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'formatter'
        );
        $parser = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'parser'
        );
        $link = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'link'
        );
        $this->assertInstanceOf('Ignaszak\Router\Conf\Conf', $conf);
        $this->assertInstanceOf(
            'Ignaszak\Router\Interfaces\IFormatterStart',
            $formatter
        );
        $this->assertInstanceOf(
            'Ignaszak\Router\Parser\Parser',
            $parser
        );
        $this->assertInstanceOf('Ignaszak\Router\Link', $link);
    }

    public function testSetBaseURI()
    {
        $stub = $this->getMockBuilder('Conf')->getMock();
        $stub->baseURI = false;
        MockTest::inject($this->router, 'conf', $stub);
        $this->router->baseURI = 'anyBaseUrl';
        $this->assertEquals('anyBaseUrl', $stub->baseURI);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidProperty()
    {
        $stub = $this->getMockBuilder('Conf')->getMock();
        MockTest::inject($this->router, 'conf', $stub);
        $this->router->invalidProperty = 'anyValue';
    }

    public function testAddTokenAndAddTokens()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Parser\RouteFormatter')
            ->disableOriginalConstructor()
            ->setMethods(['addToken', 'addTokens'])
            ->getMock();
        $stub->expects($this->once())->method('addToken');
        $stub->expects($this->once())->method('addTokens');
        MockTest::inject($this->router, 'formatter', $stub);
        $this->router->addToken('name', 'pattern');
        $this->router->addTokens([
            'token' => 'pattern'
        ]);
    }

    public function testAddPatternAndAddPatterns()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Parser\RouteFormatter')
            ->disableOriginalConstructor()
            ->setMethods(['addPattern', 'addPatterns'])
            ->getMock();
        $stub->expects($this->once())->method('addPattern');
        $stub->expects($this->once())->method('addPatterns');
        MockTest::inject($this->router, 'formatter', $stub);
        $this->router->addPattern('name', 'pattern');
        $this->router->addPatterns([
            'name' => 'pattern'
        ]);
    }

    public function testRun()
    {
        $stub = $this->getMockBuilder('Formatter')
            ->setMethods(['sort', 'format'])
            ->getMock();
        $stub->expects($this->once())->method('format');
        $stub->expects($this->once())->method('sort');
        MockTest::inject($this->router, 'formatter', $stub);

        $stub = $this->getMockBuilder('Parser')
            ->setMethods(['run'])
            ->getMock();
        $stub->expects($this->once())->method('run');
        MockTest::inject($this->router, 'parser', $stub);

        $this->router->run();
    }
}
