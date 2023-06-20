<?php
header('Content-Type: image/svg+xml');
header('Cache-Control: no-cache');

function compress($buffer)
{
    $search = array('/>[^\S ]+/s', '/[^\S ]+</s', '/(\s)+/s', '/> </s', '/:\s+/', '/\{\s+/', '/\s+}/');
    $replace = array('>', '<', '\\1', '><', ':', '{', '}');
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
    $response = @file_get_contents("http://localhost:8080/api/" . $uri, false, $context);
    $ok = $response !== false;
    return $ok ? json_decode($response, true) : null;
}

function get($param, $default = null)
{
    return isset($_GET[$param]) ? $_GET[$param] : $default;
}

/**
 * @param $languages
 * @param $dark
 * @return array
 */
function extracted($languages, $dark)
{
    if (!$languages) {
        $languages = array(
            array("name" => "none", "percent" => 100, "color" => "#ebedf0", "text" => "empty"),
        );
    }
    $bar = ceil(count($languages) / 2);
    $height = 215 + ($bar > 4 ? ($bar - 4) * 20 : 0);

    $header = $dark ? "#fff" : "#434d58";
    $background = $dark ? "#000" : "#fffefe";
    ob_start('compress');
    return array($languages, $bar, $height, $header, $background);
}

function truncate($string, $max) {
    if (strlen($string) > $max) {
        return substr($string, 0, $max - 3) . '...';
    }
    return $string;
}