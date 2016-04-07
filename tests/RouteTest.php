<?php
namespace Test;

use Ignaszak\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Route
     */
    private $route;

    public function setUp()
    {
        $this->route = Route::start();
    }

    public function testAddWithName()
    {
        $this->route->add('name1', '/pattern/subpattern', 'AnyHttpMethod');
        $this->assertEquals(
            'name1',
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );
        $this->route->add('name2', '/pattern');
        $this->assertEquals(
            'name2',
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );

        $this->assertEquals(
            [
                'name1' => [
                    'path' => '/pattern/subpattern',
                    'group' => '',
                    'method' => 'AnyHttpMethod'
                ],
                'name2' => [
                    'path' => '/pattern',
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddWithoutNAme()
    {
        $this->route->add(null, '/pattern/subpattern');
        $this->assertEquals(
            0,
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );
        $this->route->add(null, '/pattern');
        $this->assertEquals(
            1,
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );

        $this->assertEquals(
            [
                0 => [
                    'path' => '/pattern/subpattern',
                    'group' => '',
                    'method' => ''
                ],
                1 => [
                    'path' => '/pattern',
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testGet()
    {
        $this->route->get(null, '/pattern/subpattern');
        $this->assertEquals('GET', $this->route->getRouteArray()[0]['method']);
    }

    public function testPost()
    {
        $this->route->post(null, '/pattern/subpattern');
        $this->assertEquals('POST', $this->route->getRouteArray()[0]['method']);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddDuplicateName()
    {
        $this->route->add('name', '/anyPattern');
        $this->route->add('name', '/anyPattern');
    }

    public function testAddController()
    {
        $this->route->add('anyName', '/anyPattern')->controller('anyController');
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'controller' => 'anyController',
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddTokensToRoute()
    {
        $this->route->add('anyName', '/anyPattern')->tokens([
            'tokenName1' => 'pattern1',
            'tokenName2' => 'pattern2',
            'tokenName3' => 'pattern3'
        ]);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'tokens' => [
                        'tokenName1' => 'pattern1',
                        'tokenName2' => 'pattern2',
                        'tokenName3' => 'pattern3'
                    ],
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAttach()
    {
        $anyAttachment = function () {
        };
        $this->route->add('anyName', '/anyPattern')->attach($anyAttachment);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'callAttachment' => true,
                    'attachment' => $anyAttachment,
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testCallableAttach()
    {
        $anyAttachment = function () {
        };
        $this->route->add('anyName', '/anyPattern')
            ->attach($anyAttachment, false);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'callAttachment' => false,
                    'attachment' => $anyAttachment,
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testGroup()
    {
        $this->route->group('anyGroupName');
        $this->route->add(null, '/anyPattern');
        $this->route->add(null, '/anyPattern');
        $this->assertEquals(
            [
                [
                    'path' => '/anyPattern',
                    'group' => 'anyGroupName',
                    'method' => ''
                ],
                [
                    'path' => '/anyPattern',
                    'group' => 'anyGroupName',
                    'method' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testClearGroup()
    {
        $this->route->group('anyGroupName');
        $this->route->add(null, '/anyPattern');
        $this->route->group();
        $this->route->add(null, '/anyPattern');
        $this->assertEquals(
            [
                [
                    'path' => '/anyPattern',
                    'group' => 'anyGroupName',
                    'method' => ''
                ],
                [
                    'path' => '/anyPattern',
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }
}
