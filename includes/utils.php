<?php

function split($pattern, $str)
{
    return preg_split('/' . $pattern . '/', $str);
}

?>