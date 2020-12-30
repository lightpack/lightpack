<?php

namespace Framework\View;

class Template
{
    private $content;
    private $data = [];

    public function setData(array $data = []): self
    {
        $this->data = $data;
        return $this;
    }

    public function render(string $file): string
    {
        $file = DIR_VIEWS . '/' . $file . '.php';
        $this->throwExceptionIfTemplateNotFound($file);
        
        // Queue up the content in buffer.
        ob_start();
        extract($this->data);
        require_once $file;
        return ob_get_clean();
        flush();
    }

    private function throwExceptionIfTemplateNotFound(string $template)
    {
        if (!file_exists($template)) {
            throw new \Framework\Exceptions\TemplateNotFoundException(
                sprintf("Error: Could not load template %s", $template)
            );
        }
    }
}