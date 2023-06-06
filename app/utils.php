<?php
header('Content-Type: image/svg+xml');
header('Cache-Control: no-cache');

function compress($buffer)
{
    $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s');
    $replace = array('>', '<', '\\1', '><');
    return preg_replace($search, $replace, $buffer);
}

function fetch($uri)
{
    $opts = array('http' =>
        array(
            'method'  => 'GET',
            'header'  => 'Content-type: application/json'
        )
    );

    $context  = stream_context_create($opts);
    $response = file_get_contents("http://localhost:8080/api/" . $uri, false, $context);
    return json_decode($response);
}

function get($param, $default = null)
{
    return isset($_GET[$param]) ? $_GET[$param] : $default;
}