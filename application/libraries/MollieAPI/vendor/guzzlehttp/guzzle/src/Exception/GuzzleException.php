<?php

namespace _PhpScoper5e55118e73ab9\GuzzleHttp\Exception;

use Throwable;
if (\interface_exists(\Throwable::class)) {
    interface GuzzleException extends \Throwable
    {
    }
} else {
    /**
     * @method string getMessage()
     * @method \Throwable|null getPrevious()
     * @method mixed getCode()
     * @method string getFile()
     * @method int getLine()
     * @method array getTrace()
     * @method string getTraceAsString()
     */
    interface GuzzleException
    {
    }
}
