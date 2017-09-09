<?php

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return value($default);
        }
        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'empty':
                return '';
            case 'null':
                return null;
        }
        return $value;
    }
}

if (!function_exists('container')) {
    /**
     * Calls the default Dependency Injection container.
     *
     * @param  mixed
     * @return mixed|\Phalcon\DiInterface
     */
    function container()
    {
        $default = Phalcon\Di::getDefault();
        $args = func_get_args();
        if (empty($args)) {
            return $default;
        }
        if (!$default) {
            trigger_error('Unable to resolve Dependency Injection container.', E_USER_ERROR);
        }
        return call_user_func_array([$default, 'getShared'], $args);
    }
}

if (!function_exists('singleton')) {
    /**
     * Calls the default Dependency Injection container.
     *
     * @param  mixed
     * @return mixed|\Phalcon\DiInterface
     */
    function singleton()
    {
        $container = container();
        $args = func_get_args();
        if (empty($args)) {
            return $container;
        }
        return call_user_func_array([$container, 'get'], $args);
    }
}