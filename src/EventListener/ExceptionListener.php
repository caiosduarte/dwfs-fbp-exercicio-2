<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
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
            // loga o erro verdadeiro
            $messageLog = sprintf('*** Error: %s with code: %s',
                $exception->getMessage(),
                $exception->getCode()
            );
            
            $statusCode =  Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($exception->getCode() == 0 && strpos($exception->getMessage(), "No route found for") !== false) {
                $responseData['message'] = $exception->getMessage();
                $statusCode =  Response::HTTP_NOT_IMPLEMENTED;
            }
            $event->setResponse(new JsonResponse($responseData, $statusCode));
            //echo $messageLog;
        } else {
            $responseData['message'] = $exception->getMessage();
            if(count($exception->getErrors()) > 0) {
             $responseData['errors'] = $exception->getErrors();
            }
            $event->setResponse(new JsonResponse($responseData, $exception->getCode()));
        }        
    }
}