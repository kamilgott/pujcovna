<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Debug
 *
 * @author Administrator
 */
class Debug {
    //put your code here

    const deb = true;

    public static function p($obj) {
        if (isset($_SESSION['s_debug']) and $_SESSION['s_debug'])
            echo var_dump($obj);
    }

}

?>
