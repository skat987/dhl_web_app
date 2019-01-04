<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
   
    /**
     * Default routes
     */
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login'], ['_name' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);

    /**
     * Firm entity routes
     */
    $routes->connect('/admin/home', ['controller' => 'Firms', 'action' => 'index'], ['_name' => 'adminHome']);
    $routes->connect('/admin/firm/:id', ['controller' => 'Firms', 'action' => 'view'], ['_name' => 'firmView'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/admin/firm/:id/edit', ['controller' => 'Firms', 'action' => 'edit'], ['_name' => 'adminFirmEdit'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/admin/firm/:id/delete', ['controller' => 'Firms', 'action' => 'delete'], ['_name' => 'adminFirmDelete'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/admin/firms/add', ['controller' => 'Firms', 'action' => 'add'], ['_name' => 'adminFirmAdd']);
    
    /**
     * User entity routes
     */
    $routes->connect('/admin/users', ['controller' => 'Users', 'action' => 'index'], ['_name' => 'adminUsers']);
    $routes->connect('/admin/users/:id', ['controller' => 'Users', 'action' => 'view'], ['_name' => 'userView'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/admin/users/edit/:id', ['controller' => 'Users', 'action' => 'edit'], ['_name' => 'userEdit'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/admin/users/delete/:id', ['controller' => 'Users', 'action' => 'delete'], ['_name' => 'userDelete'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/admin/users/add', ['controller' => 'Users', 'action' => 'add'], ['_name' => 'userAdd']);

    /**
     * CustomerFile entity routes
     */
    $routes->connect('/customer-files/firm-:firm_id/add', ['controller' => 'CustomerFiles', 'action' => 'add'], ['_name' => 'addCustomerFile'])
        ->setPatterns(['firm_id' => '\d+'])
        ->setPass(['firm_id']);
    $routes->connect('/customer-files/:id/delete', ['controller' => 'CustomerFiles', 'action' => 'delete'], ['_name' => 'deleteCustomerFile'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);
    $routes->connect('/customer-files/firm-:firm_id/add-directory', ['controller' => 'CustomerFiles', 'action' => 'addDirectory'], ['_name' => 'addDirectory'])
        ->setPatterns(['firm_id' => '\d+'])
        ->setPass(['firm_id']);
    $routes->connect('/customer-files/:firm_id-:dir_name/delete', ['controller' => 'CustomerFiles', 'action' => 'deleteDirectory'], ['_name' => 'deleteDirectory'])
        ->setPatterns(['firm_id' => '\d+'])
        ->setPass(['firm_id', 'dir_name']);
    $routes->connect('/customer-files/:id/download', ['controller' => 'CustomerFiles', 'action' => 'downloadCustomerFile'], ['_name' => 'downloadCustomerFile'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});
