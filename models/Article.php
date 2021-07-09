<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use DateTime;

class Article extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'article';
    }

    public static function getTabLabels() {
        return [
            'a_date_creation' => 'Date création',
            'a_nom' => 'Nom',
            'a_description' => 'Description',
            'a_prix' => 'Prix',
            'a_quantite' => 'Quantité',
            'a_image' => 'Image'
        ];
    }

    public function getLabel($attribute) {
        return Article::getTabLabels()[$attribute];
    }

    // only fields in rules() are searchable
    public function rules()
    {
        return [
            [['a_date_creation', 'a_nom', 'a_description', 'a_prix', 'a_quantite', 'a_image'], 'safe'],

            [['a_date_creation'], 'date'],
            [['a_nom'], 'string', 'length' => [0, 50]],
            [['a_description'], 'string', 'length' => [0, 250]],
            [['a_prix'], 'number'],
            [['a_quantite'], 'integer'],
            ['a_image', 'image', 'extensions' => 'png, jpg'],

            [['a_nom', 'a_description', 'a_prix', 'a_quantite', 'a_image'], 'required', 'except' => 'search'],
        ];
    }

    public function afterFind() {
        /* formattage pour la vue utilisateur */
        $formatter = Yii::$app->formatter;//config par défaut dans web.php
        $this->a_date_creation = $formatter->asDate($this->a_date_creation);
        $this->a_prix = $formatter->asCurrency($this->a_prix);
        $this->a_quantite = $formatter->asInteger($this->a_quantite);
    }

    public function search($params)
    {
        $query = Article::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'a_date_creation' => SORT_DESC,
                ]
            ],
        ]);

        //résultats sans filtre
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        //reconverti la date au format bdd pour la recherche
        if (!empty($this->a_date_creation)) {
            $objetDate = DateTime::createFromFormat('d/m/Y', $this->a_date_creation);
            $this->a_date_creation = $objetDate->format('Y-m-d');
        }

        $query->andFilterWhere(['a_id' => $this->a_id]);
        $query->andFilterWhere(['like', 'a_date_creation', $this->a_date_creation])
            ->andFilterWhere(['like', 'a_nom', $this->a_nom])
            ->andFilterWhere(['like', 'a_description', $this->a_description])
            ->andFilterWhere(['like', 'a_prix', $this->a_prix])
            ->andFilterWhere(['like', 'a_quantite', $this->a_quantite]);

        return $dataProvider;
    }

}
