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
    * 
    * It takes any number of string texts to generate relative
    * URL to application basepath.
    */
    function url(string ...$fragments) {
        $path = implode('/', $fragments);
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
     * ------------------------------------------------------------     
     * HTML characters to entities converter.
     * ------------------------------------------------------------     
     * 
     * Often used to escape HTML output to protect against 
     * XSS attacks..
     */
    function _e(string $str) {
        return htmlentities($str, ENT_QUOTES, 'UTF-8');
    }
}

if(!function_exists('slugify')) {
    /**
     * ------------------------------------------------------------     
     * Converts an ASCII text to URL friendly slug.
     * ------------------------------------------------------------      
     * 
     * It will replace any non-word character, non-digit 
     * character, or a non-dash '-' character with empty. 
     * Also it will replace any number of space character 
     * with a single dash '-'.
     */
    function slugify(string $text) {
        $text = preg_replace(
            ['#[^\w\d\s-]+#', '#(\s)+#'], 
            ['', '-'], 
            $text
        );

        return strtolower(trim($text, ' -'));
    }
}
 
if (!function_exists('asset_url')) {
    /**
     * ------------------------------------------------------------
     * Generates relaive URL to /public/assets folder.
     * ------------------------------------------------------------
     * 
     * Usage: 
     * 
     * asset_url('css', 'styles.css');
     * asset_url('img', 'favicon.png');
     * asset_url('js', 'scripts.js');
     */
    function asset_url(string $type, string $file): ?string {
        return url('public/assets', $type, $file);
    }
}

if(!function_exists('humanize')) {
    /**
     * ------------------------------------------------------------     
     * Converts a slug URL to friendly text.
     * ------------------------------------------------------------      
     * 
     * It replaces dashes and underscores with whitespace 
     * character. Then capitalizes the first character.
     */
    function humanize(string $slug) {
        $text = str_replace(['_', '-'], ' ', $slug);
        $text = trim($text);

        return ucfirst($text);
    }
}

if (!function_exists('query_url')) {
    /**
     * ------------------------------------------------------------
     * Generates relaive URL with support for query params.
     * ------------------------------------------------------------
     * 
     * For example:
     * 
     * query_url('edit',, ['sort' => 'asc', 'status' => 'active']);
     * 
     * That  will produce: /users?sort=asc&status=active 
     */
    function query_url(...$fragments): string {
        if(!$fragments) {
            return url();
        }

        $params = end($fragments);

        if(is_array($params)) {
            $query = '?' . http_build_query($params);
            array_pop($fragments);
        }

        return url(...$fragments) . $query;
    }
}