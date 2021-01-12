<?php

namespace Lightpack\Debug;

use Error;
use Exception;
use Throwable;

class ExceptionRenderer
{
    private $data = [];
    private $environment;
    private $errorLevels = [
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated',
    ];

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function render(Throwable $exc, string $errorType = 'Exception'): void
    {
        // Clean the output buffer first.
        while(\ob_get_level() !== 0) {
            \ob_end_clean();
        }

        if($this->environment !== 'development') {
            $this->renderProductionTemplate($exc->getCode());
        } else {
            $this->renderDevelopmentTemplate($exc, $errorType);
        }
    }

    private function getErrorType(int $code): string
    {
        return $this->errorLevels[$code] ?? 'Error';
    }

    private function getTrace(string $traceString): string
    {
        $traceFragments = preg_split('/#[\d+]/', $traceString);

        unset($traceFragments[0]);
        array_pop($traceFragments);

        $trace = '';

        foreach ($traceFragments as $key => $value) {
            list($tracePath, $traceInfo) = explode(':', $value);
            $trace .= '<div class="trace-item">';
            $trace .= '<span class="trace-count">' . (count($traceFragments) - $key) . '</span>';
            $trace .= '<span class="trace-info">' . $traceInfo . '</span><br>';
            $trace .= '<span class="trace-path">' . $tracePath . '</span>';
            $trace .= '</div>';
        }

        return $trace;
    }

    private function getRequestFormat(): string
    {
        if($_SERVER['CONTENT_TYPE'] ?? null) {
            if($_SERVER['CONTENT_TYPE'] === 'application/json') {
                return 'json';
            }
        }

        return 'http'; 
    }

    private function getCodePreview(string $file, int $line): string
    {
        $preview = '';
        $file = file($file);
        $line = $line;

        $start = ($line - 5 >= 0) ? $line - 5 : $line - 1;
        $end = ($line - 5 >= 0) ? $line + 4 : $line + 8;

        for ($i = $start; $i < $end; $i++) {
            if (! isset($file[$i])) {
                continue;
            }

            $text = $file[$i];

            if ($i == $line - 1) {
                $preview .= "<div class='error-line'>";
                $preview .= "<span class='line'>" . ($i + 1) . '</span>';
                $preview .= "<span class='text'>" . htmlentities($text, ENT_QUOTES) . '</span></div>';
                continue;
            }

            $preview .= "<div>";
            $preview .= "<span class='line'>" . ($i + 1) . '</span>';
            $preview .= "<span class='text'>" . htmlentities($text, ENT_QUOTES) . '</span></div>';
        }

        return $preview;
    }

    private function sendHeaders(int $statusCode)
    {
        if (!headers_sent()) {
            header("HTTP/1.1 $statusCode", true, $statusCode);

            if($this->getRequestFormat() === 'json') {
                header('Content-Type', 'application/json');
            } else {
                header('Content-Type', 'text/html');
            }
        }
    }

    private function renderTemplate(string $errorTemplate, array $data = [])
    {
        extract($data);
        ob_start();
        require $errorTemplate;
        echo ob_get_clean();
        \flush();
        exit();
    }

    private function renderProductionTemplate(int $statusCode)
    {
        if(!file_exists(DIR_VIEWS . '/errors/' . $statusCode . '.php')) {
            $statusCode = 500;
            $errorTemplate = __DIR__ . '/templates/' . $this->getRequestFormat() . '/production.php';
        } else {
            $errorTemplate = DIR_VIEWS . '/errors/layout.php';
        }

        $this->sendHeaders($statusCode);
        $this->renderTemplate($errorTemplate, ['template' => $statusCode]);
    }

    private function renderDevelopmentTemplate(Throwable $exc, string $errorType = 'Exception')
    {
        $errorTemplate = __DIR__ . '/templates/' . $this->getRequestFormat() . '/development.php';
        
        if($errorType === 'Error') {
            $errorType = $this->getErrorType($exc->getCode());
        }

        $data['type'] = $errorType;
        $data['code'] = $exc->getCode();
        $data['message'] = $exc->getMessage();
        $data['file'] = $exc->getFile();
        $data['line'] = $exc->getLine();
        $data['trace'] = $this->getTrace($exc);
        $data['format'] = $this->getRequestFormat();
        $data['environment'] = $this->environment;
        $data['code_preview'] = $this->getCodePreview($data['file'], $data['line']);
        $data['ex'] = $exc;

        $this->sendHeaders(500);
        $this->renderTemplate($errorTemplate, $data);
    }
}