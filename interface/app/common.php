<?php

//公共方法

if(!function_exists ('pd')) {
    /**
     * 打印函数
     *
     * @param $var    打印的变量
     */
    function pd($var)
    {

        echo '<pre style="background: #ccc;padding: 8px;border-radius: 6px">';
        if (is_bool($var) || is_null($var)) {
            var_dump($var);
        } else {
            print_r($var);
        }
        echo '</pre>';

        return 1;
    }
}
