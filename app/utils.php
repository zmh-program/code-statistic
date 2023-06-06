<?php

function compress_output($buffer)
{
    $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s');
    $replace = array('>', '<', '\\1', '><');
    return preg_replace($search, $replace, $buffer);
}
