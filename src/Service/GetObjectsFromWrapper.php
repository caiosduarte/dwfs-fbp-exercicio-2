<?php
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Envelope;

final class GetObjectsFromWrapper 
{
    public static function execute($wrapper) {
        $handled = $wrapper->last(HandledStamp::class);    
        return $handled->getResult();        
    }
}
