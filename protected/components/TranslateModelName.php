<?php

class TranslateModelName{
    public static function getTranstalion($modname){
        switch ($modname) {
            case('Docflows'):
                $rez = '"Документообіг"';
                break;
            default:
                $rez = $modname;
                
        }
        return $rez;
    }
}
Yii::t
?>
