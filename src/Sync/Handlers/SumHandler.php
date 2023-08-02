<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Обработчик маршрута "/api/sum" для получения суммы чисел в переданных параметрах.
 */
class SumHandler implements RequestHandlerInterface
{
    /**
     * Обработка запроса и возвращение ответа
     *
     * @param ServerRequestInterface $request - получает HTTP запрос
     * @return ResponseInterface возвращает экземпляр класса JsonResponse c ответом
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $date = date('Y-m-d');
        $log = new Logger('LOGGER');
        $log->pushHandler(new StreamHandler("logs/$date/requests.log",));
        $queryParams = $request->getQueryParams();
        $sum = array_sum($queryParams);
        $log->info(implode(',', $queryParams) . " result: $sum");

        return new JsonResponse([
            'result' => $sum
        ]);
    }
}