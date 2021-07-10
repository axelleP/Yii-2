<?php

namespace components;

use DateTime;

class MyHelpers
{
   //reconverti la date fr au format sql
    public static function convertDateFrtoSql($date = '') {
        if (!empty($date)) {
            $objetDate = DateTime::createFromFormat('d/m/Y', $date);
            $date = $objetDate->format('Y-m-d');
        } else {
            throw new \yii\web\HttpException(500, 'La date est vide !');
        }

        return $date;
    }
}
