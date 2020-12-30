<?php

namespace Framework\Http;

class Session
{
    public function __construct(string $name)
    {
        ini_set('session.use_only_cookies', TRUE);
        ini_set('session.use_trans_sid', FALSE);
        session_name($name);
        session_start();

        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    }
    
    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key = null, $default = null)
    {
        if($key === null) {
            return $_SESSION;
        }

        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function delete(string $key)
    {
        if($_SESSION[$key] ?? null) {
            unset($_SESSION[$key]);
        }
    }

    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    public function flash(string $key, $value = null)
    {
        if($value) {
            $this->set($key, $value);
            return;
        }

        $flash = $this->get($key);
        $this->delete($key);
        return $flash;
    }

    public function verifyAgent(): bool 
    {
        if(self::get('user_agent') == $_SERVER['HTTP_USER_AGENT']) {
            return TRUE;
        }
        return FALSE;
    }

    public function token()
    {
        $token = bin2hex(openssl_random_pseudo_bytes(8));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public function verifyToken(): bool
    {
        if (
            isset($_SESSION) && 
            (!isset($_POST['csrf_token']) || ($_POST['csrf_token'] !== $_SESSION['csrf_token']))
        ) {
            return false;
        }
        
        return true;
    }

    public function destroy()
    {
        session_unset();

        if(ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}