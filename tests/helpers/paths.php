<?php

if (!function_exists('guardian_root'))
{
    /**
     * Get the path to the package root folder of Guardian package.
     *
     * @param  string  $path
     * @return string
     */
    function guardian_root($path = '')
    {
        return realpath(__DIR__ . '/../../') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('guardian_test_path'))
{
    /**
     * Get the path to the tests directory of Guardian.
     *
     * @param  string  $path
     * @return string
     */
    function guardian_test_path($path = '')
    {
        return guardian_root('tests') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('test_base_path'))
{
    /**
     * Get the base path while testing
     *
     * @param  string  $path
     * @return string
     */
    function test_base_path($path = '')
    {
        return guardian_root('tests/base') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('test_app_path'))
{
    /**
     * Get the app path while testing
     *
     * @param string $path
     * @return string
     */
    function test_app_path($path = '')
    {
        return guardian_root('base/app') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('test_config_path'))
{
    /**
     * Get the config path while testing
     *
     * @param string $path
     * @return string
     */
    function test_config_path($path = '')
    {
        return guardian_root('base/config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('test_database_path'))
{
    /**
     * Get the database path while testing
     *
     * @param string $path
     * @return string
     */
    function test_database_path($path = '')
    {
        return guardian_root('base/database') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('test_resource_path'))
{
    /**
     * Get the path to resources directory while testing.
     *
     * @param string $path
     * @return string
     */
    function test_resources_path($path = '')
    {
        return guardian_root('base/resources') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
