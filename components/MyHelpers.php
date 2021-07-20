<?php

namespace components;

use DateTime;

class MyHelpers
{
    /**
     * Converti une date format fr en date format sql
     * @param $date date format fr
     * @return $date date format sql
     */
    public static function convertDateFrtoSql($date = '') {
        $date = trim($date);

        if (!empty($date)) {
            $objetDate = DateTime::createFromFormat('d/m/Y', $date);
            $date = $objetDate->format('Y-m-d');
        } else {
            throw new \yii\web\HttpException(500, 'La date est vide !');
        }

        return $date;
    }
    /**
     * Converti un nombre format fr en nombre format sql
     * @param $number number format fr
     * @return $number number format sql
     */
    public static function convertNumberFrtoSql($number = '') {
        $number = trim($number);

        if (!empty($number)) {
            $number = str_replace(',', '.', $number);
            $number = str_replace(' ', '', $number);
        } else {
            throw new \yii\web\HttpException(500, 'Le nombre est vide !');
        }

        return $number;
    }

    /**
     * Génération du nom de l'image
     * @param $image object
     * @return $nom string
     */
    public static function createImageName($image = '') {
        if (!empty($image)) {
            $nom = time() . '_' . bin2hex(random_bytes(10)) . '.' . $image->extension;
        } else {
            throw new \yii\web\HttpException(500, "L'image est vide !");
        }

        return $nom;
    }
}
