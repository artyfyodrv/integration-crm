<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/api/sum', \Sync\Handlers\SumHandler::class, 'api.sum');
    $app->get('/api/auth', \Sync\Handlers\Kommo\AuthHandler::class, 'api.auth');
    $app->get('/api/contacts', \Sync\Handlers\Kommo\ContactsHandler::class, 'api.contacts');
    $app->get('/api/contact', \Sync\Handlers\Unisender\ContactHandler::class, 'api.contact');
    $app->post('/api/send', \Sync\Handlers\Kommo\SendHandler::class, 'api.send');
    $app->post('/api/addIntegration', \Sync\Handlers\Kommo\AddIntegrationHandler::class, 'api.addIntegration');
    $app->get('/api/accounts', \Sync\Handlers\AccountsHandler::class, 'api.accounts');
    $app->post('/widget', \Sync\Handlers\Kommo\WidgetHandler::class, 'widget');
    $app->post('/webhook', \Sync\Handlers\Kommo\WebhookHandler::class, 'webhook');
};
