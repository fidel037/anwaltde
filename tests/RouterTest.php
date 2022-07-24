<?php

namespace Tests;

use App\Controller\HomeController;
use App\Router\Router;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends TestCase {

    public function testValidateRoutesFails()
    {
        $router = new Router;
        $router->setRoutes([
            [
                'controller' => 'test',
                'method' => 'get',
                'action' => 'test',
                'path' => '/'
            ]
        ]);
        $this->expectException(\Exception::class);
        $this->callMethod($router, 'validateRoutes', []);
    }

    public function testValidateRoutesSuccess()
    {
        $router = new Router;
        $router->setRoutes([
            [
                'controller' => HomeController::class,
                'method' => 'get',
                'action' => 'index',
                'path' => '/'
            ]
        ]);
        $this->callMethod($router, 'validateRoutes', []);
        $this->assertTrue(true);
    }

    public function testValidateRequestFails()
    {
        $request = new Request();
        $request->setMethod('POST');
        $router = new Router;
        $this->expectException(\Exception::class);
        $this->callMethod($router, 'validateRequest', [$request, 'GET']);
    }

    public function testValidateRequestSuccess()
    {
        $request = new Request();
        $request->setMethod('POST');
        $router = new Router;
        $this->callMethod($router, 'validateRequest', [$request, 'POST']);
        $this->assertTrue(true);
    }

    public function testHandle()
    {
        /** @var Router */
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRequest', 'next'])
            ->getMock();
        $request = new Request();
        $request->setMethod('get');

        $router->method('getRequest')->willReturn($request);
        $router->expects($this->once())
            ->method('next')
            ->with(HomeController::class, 'index', $request)
            ->willReturn(true);
        $router->setRoutes([
            [
                'controller' => HomeController::class,
                'method' => 'get',
                'action' => 'index',
                'path' => '/'
            ]
        ]);
        $response = $router->handle();
        $this->assertTrue($response);
    }

    public function testGetRoutePass()
    {
        $router = new Router;
        $router->setRoutes([
            [
                'controller' => HomeController::class,
                'method' => 'get',
                'action' => 'index',
                'path' => '/'
            ]
        ]);
        $request = new Request();
        $request->setMethod('get');
        $this->callMethod($router, 'getRoute', [$request]);
        $this->assertTrue(true);
    }

    public function testGetRouteFails()
    {
        $router = new Router;
        $router->setRoutes([
            [
                'controller' => HomeController::class,
                'method' => 'post',
                'action' => 'index',
                'path' => '/'
            ]
        ]);
        $request = new Request();
        $request->setMethod('get');
        $this->expectException(\Exception::class);
        $this->callMethod($router, 'getRoute', [$request]);
    }
}
