<?php

namespace Framework\Debug;

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
        $this->data['type'] = $errorType;
        $this->data['code'] = $exc->getCode();
        $this->data['message'] = $exc->getMessage();
        $this->data['file'] = $exc->getFile();
        $this->data['line'] = $exc->getLine();
        $this->data['trace'] = $this->getTrace($exc);
        $this->data['format'] = $this->getRequestFormat();
        $this->data['environment'] = $this->environment;
        $this->data['code_preview'] = $this->getCodePreview();
        $this->data['ex'] = $exc;
        
        if($errorType === 'Error') {
            $this->data['type'] = $this->getErrorType($exc->getCode());
        }

        $this->renderException($this->data);
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

    private function getCodePreview(): string
    {
        $preview = '';
        $file = file($this->data['file']);
        $line = $this->data['line'];

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

    private function renderException(array $data): void 
    {
        extract($data);
        ob_start();
        require __DIR__ . "/templates/{$format}/{$environment}.php";
        echo ob_get_clean();
        exit();
    }
}