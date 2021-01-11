<?php

if (!function_exists('app')) {
    /*
    * ------------------------------------------------------------
    * Shortcut to $container->get() method.
    * ------------------------------------------------------------
    */
    function app(string $key) {
        global $container;
        return $container ? $container->get($key) : null;
    }
}

if (!function_exists('url')) {
    /*
    * ------------------------------------------------------------
    * Creates a relative URL.
    * ------------------------------------------------------------
    */
    function url() {
        $path = implode('/', func_get_args());
        $url = app('request')->basepath() . '/' . $path;
        
        return '/' . trim($url, '/');
    }
}

if (!function_exists('redirect')) {
    /*
    * ------------------------------------------------------------
    * Redirect to URI.
    * ------------------------------------------------------------
    */
    function redirect($uri = '', $code = 302) {
        $uri = url($uri);
        header('Location: ' . $uri, TRUE, $code);
        exit;
    }
}

if (!function_exists('csrf_input')) {
    /*
    * ------------------------------------------------------------
    * Returns an HTML input for CSRF token.
    * ------------------------------------------------------------
    */
    function csrf_input() {
        return '<input type="hidden" name="csrf_token" value="' . app('session')->token() . '">';
    }
}

if(!function_exists('_e')) {
    /**
     * HTML characters to entities converter. Often used to
     * escape output.
     */
    function _e(string $str) {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }
}