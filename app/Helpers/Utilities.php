<?php
/**
 * Created by PhpStorm.
 * User: manuel
 * Date: 28/08/17
 * Time: 03:33 PM
 */

namespace App\Helpers;
use DateTime;

class Utilities
{
    public function convertFormat12Hour($hour)
    {
        $date = new DateTime($hour);
        return $date->format('h:ia') ;
    }

    public function convertFormat24Hour($hour,$s=true)
    {
        $date = new DateTime($hour);
        $second=($s==true)?':s':'';
        return $date->format("H:i$second") ;
    }
}