<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Обработчик маршрута "/api/sum" для получения суммы чисел в переданных параметрах.
 */
class SumHandler implements RequestHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Обработка запроса и возвращение ответа
     *
     * @param ServerRequestInterface $request - получает HTTP запрос
     * @return ResponseInterface возвращает экземпляр класса JsonResponse c ответом
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $count = count($queryParams);
        if ($count < 2) {
            $this->logger->error(
                "(Expected minimum 2 param, got: $count )" . " got param: " . implode('+', $queryParams)
            );
            return new JsonResponse([
                'status' => 'error',
                'info' => "Not enough parameters passed in the request. Expected min 2, but called ($count)"
            ], 400);
        }
        $sum = array_sum($queryParams);

        $this->logger->info("Received params: " . implode(',', $queryParams) . " result: $sum");
        return new JsonResponse([
            'status' => 'successful',
            'result' => $sum,
        ]);
    }
}