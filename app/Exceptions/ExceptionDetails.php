<?php

namespace App\Exceptions;


class ExceptionDetails
{
    public static function filter(\Exception $e) {
        if ('local' === env('APP_ENV', config('app.env', 'production'))) {
            $result = [
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTraceAsString()
            ];
        } else {
            $result = $e->getMessage();
        }
        return $result;
    }
}