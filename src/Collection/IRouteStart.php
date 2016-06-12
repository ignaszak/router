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

namespace Ignaszak\Router\Collection;

interface IRouteStart
{

    /**
     *
     * @param string $name
     * @param string $pattern
     * @throws Ignaszak\Router\RouterException
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function add(
        string $name = null,
        string $pattern,
        string $method = ''
    ): IRouteAdd;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function get(string $name = null, string $pattern): IRouteAdd;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function post(string $name = null, string $pattern): IRouteAdd;

    /**
     *
     * @param string $name
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function group(string $name): IRouteStart;

    /**
     *
     * @param string[] $tokens
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addTokens(array $tokens): IRouteStart;

    /**
     *
     * @param string[] $defaults
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addDefaults(array $defaults): IRouteStart;

    /**
     *
     * @param string[] $patterns
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addPatterns(array $patterns): IRouteStart;
}
