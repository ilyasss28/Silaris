<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Exception as BaseException;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class My_Exceptions extends CI_Exceptions
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show_exception($exception) 
    {
        if (headers_sent()) {
            echo "Exception: " . htmlspecialchars($exception->getMessage() ?? '') . " in " . htmlspecialchars($exception->getFile() ?? '') . " on line " . $exception->getLine() . "<br>\n";
            return;
        }

        $run     = new Run();
        $handler = new PrettyPageHandler();
        
        $run->pushHandler($handler);
        
        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $jsonHandler = new JsonResponseHandler();
            $jsonHandler->setJsonApi(true);
            $run->pushHandler($jsonHandler);
        }

        $run->allowQuit(false);
        $run->writeToOutput(true);
        $run->handleException($exception);
        
        exit(1);
    }

    public function show_php_error($severity, $message, $filepath, $line) 
    {
        if (headers_sent()) {
            echo "Error: " . htmlspecialchars($message ?? '') . " in " . htmlspecialchars($filepath ?? '') . " on line " . $line . "<br>\n";
            return;
        }

        $run     = new Run();
        $handler = new PrettyPageHandler();
        $handler->setApplicationPaths([$filepath]);
        $handler->addDataTable($message, [
            'file' => $filepath,
            'line' => $line,
        ]);

        $run->pushHandler($handler);
        // Example: tag all frames inside a function with their function name
        $run->pushHandler(function ($exception, $inspector, $run) {
            $inspector->getFrames()->map(function ($frame) {
                if ($function = $frame->getFunction()) {
                    $frame->addComment("This frame is within function '$function'", 'cpt-obvious');
                }
                return $frame;
            });
        });
        
        if (\Whoops\Util\Misc::isAjaxRequest()) {
            $jsonHandler = new JsonResponseHandler();
            $jsonHandler->setJsonApi(true);
            $run->pushHandler($jsonHandler);
        }

        $run->allowQuit(false);
        $run->writeToOutput(true);
        
        $exception = new \ErrorException($message, 0, $severity, $filepath, $line);
        $run->handleException($exception);
        
        exit(1);
    }

}

/* End of file My_Exception.php */
/* Location: ./application/core/My_Exception.php */