<?php
/**
 *
 * PHP Version 7.0
 *
 * @copyright 2016 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Ignaszak\Router\Interfaces;

interface IRouteAdd
{

    /**
     *
     * @param string $controller
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function controller(string $controller): IRouteAdd;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function token(string $name, string $pattern): IRouteAdd;

    /**
     *
     * @param string[] $tokens
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function tokens(array $tokens): IRouteAdd;

    /**
     *
     * @param \Closure $closure
     * @param bool $call
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function attach(\Closure $closure, bool $call = false): IRouteAdd;
}
