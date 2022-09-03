<?php

namespace App\Helper;

class ArrayUtils
{
    public static function indexArray(array $array, $field)
    {
        $field = "get" . ucfirst($field);
        $arrIndexed = array();
        foreach ($array as $element) {
            $arrIndexed[$element->$field()] = $element;
        }

        return $arrIndexed;
    }
}