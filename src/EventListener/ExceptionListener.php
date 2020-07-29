<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

use App\Error\AppError;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $responseData = [
            'status' => 'error',
            'message' => 'Internal server error.'            
        ];         

        if (!$exception instanceof AppError) {
            // loga o erro
            sprintf('*** Error: %s with code: %s',
                $exception->getMessage(),
                $exception->getCode()
            );
            $event->setResponse(new JsonResponse($responseData, Response::HTTP_INTERNAL_SERVER_ERROR));
        } else {
            $responseData['message'] = $exception->getMessage();
            $event->setResponse(new JsonResponse($responseData, $exception->getCode()));
        }        
    }
}