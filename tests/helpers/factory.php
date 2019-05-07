<?php

if (!function_exists('create'))
{
    /**
     * Use Model Factory to create the Model instance with fake data.
     * The created instance will be persisted to database.
     *
     * @param string $class
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Factory
     */
    function create($class, $attributes = [])
    {
        return factory($class)->create($attributes);
    }
}

if (!function_exists('make'))
{
    /**
     * Use Model Factory to create the Model instance with fake data.
     * The created instance will not be persisted to database.
     *
     * @param string $class
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Factory
     */
    function make($class, $attributes = [])
    {
        return factory($class)->make($attributes);
    }
}

if (!function_exists('raw'))
{
    /**
     * Use Model Factory to create array of the Model attributes with fake data.
     *
     * @param string $class
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Factory
     */
    function raw($class, $attributes = [])
    {
        return factory($class)->raw($attributes);
    }
}

if (!function_exists('createMany'))
{
    /**
     * Use Model Factory to create multiple the Model instances with fake data.
     * The created instances will be persisted to database.
     *
     * @param string $class
     * @param integer $number
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Factory
     */
    function createMany($class, $number, $attributes = [])
    {
        return factory($class, $number)->create($attributes);
    }
}

if (!function_exists('makeMany'))
{
    /**
     * Use Model Factory to make multiple the Model instances with fake data.
     * The created instances will not be persisted to database.
     *
     * @param string $class
     * @param integer $number
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Factory
     */
    function makeMany($class, $number, $attributes = [])
    {
        return factory($class, $number)->make($attributes);
    }
}

if (!function_exists('createBin'))
{
    /**
     * Create a new Postbin
     *
     * @return string postbin id
     */
    function createBin()
    {
        $http = new \GuzzleHttp\Client;

        return $http->post('https://postb.in/api/bin');
    }
}

if (!function_exists('readBin'))
{
    /**
     * Fetch the contents of the postbin identified by the id.
     *
     * @param string $id
     * @return mixed
     */
    function readBin($binId)
    {
        $http = new \GuzzleHttp\Client;

        return $http->get("https://postb.in/api/bin/{$binId}");
    }
}
