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
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);


    /** ==================================================================================================
     * Football
     */
    $routes->connect('/list-news', ['controller' => 'News', 'action' => 'listNews']);
    $routes->connect('/fixtures', ['controller' => 'Schedules', 'action' => 'fixtures']);
    $routes->connect('/summary/*', ['controller' => 'Schedules', 'action' => 'summary']);
    $routes->connect('/report/*', ['controller' => 'Schedules', 'action' => 'report']);
    $routes->connect('/commentary/*', ['controller' => 'Schedules', 'action' => 'commentary']);
    $routes->connect('/soccer/commentary', ['controller' => 'Schedules', 'action' => 'commentary']);
    $routes->connect('/football/commentary', ['controller' => 'Schedules', 'action' => 'commentary']);
    $routes->connect('/matchstats/*', ['controller' => 'Schedules', 'action' => 'matchstats']);
    $routes->connect('/football/matchstats', ['controller' => 'Schedules', 'action' => 'matchstats']);
    $routes->connect('/soccer/matchstats', ['controller' => 'Schedules', 'action' => 'matchstats']);
    $routes->connect('/lineups/*', ['controller' => 'Schedules', 'action' => 'lineups']);
    $routes->connect('/video/*', ['controller' => 'Schedules', 'action' => 'video']);
    $routes->connect('/chart', ['controller' => 'Schedules', 'action' => 'chart']);
    $routes->connect('/football/report', ['controller' => 'Schedules', 'action' => 'report']);
    $routes->connect('/football/categories/*', ['controller' => 'Schedules', 'action' => 'categories']);
    $routes->connect('/privacy-policy.html', ['controller' => 'Schedules', 'action' => 'policy']);
    $routes->connect('/policy', ['controller' => 'Schedules', 'action' => 'policy']);
    $routes->connect('/policy-dn-media-corp', ['controller' => 'Schedules', 'action' => 'policydn']);
    /**
     * End Football
     *====================================================================================================*/

    /** ==================================================================================================
     * Lien quan
     */
    $routes->connect('/tuong', ['controller' => 'Garena', 'action' => 'tuong']);
    $routes->connect('/get-type-tuong', ['controller' => 'Garena', 'action' => 'getTypesTuong']);
    $routes->connect('/search-tuong', ['controller' => 'Garena', 'action' => 'searchTuong']);
    $routes->connect('/chi-tiet-tuong/*', ['controller' => 'Garena', 'action' => 'chiTietTuong']);
    $routes->connect('/ngoc', ['controller' => 'Garena', 'action' => 'ngoc']);
    $routes->connect('/get-filter-ngoc', ['controller' => 'Garena', 'action' => 'getFilterNgoc']);
    $routes->connect('/doc-chieu', ['controller' => 'Garena', 'action' => 'docChieu']);
    $routes->connect('/trang-bi', ['controller' => 'Garena', 'action' => 'trangBi']);
    $routes->connect('/get-filter-trang-bi', ['controller' => 'Garena', 'action' => 'getFilterTrangBi']);
    $routes->connect('/tin-tuc', ['controller' => 'Garena', 'action' => 'tintuc']);
    $routes->connect('/chi-tiet-tin-tuc', ['controller' => 'Garena', 'action' => 'newsDetail']);
    $routes->connect('/cam-nang', ['controller' => 'Garena', 'action' => 'camnang']);
    $routes->connect('/chi-tiet-cam-nang', ['controller' => 'Garena', 'action' => 'camnangDetail']);
    /**
     * End Lien quan
     *====================================================================================================*/


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
