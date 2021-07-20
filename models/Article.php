<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use Faker;

class Article extends ActiveRecord
{

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return 'article';
    }

    public function attributeLabels() {
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
        return $this->attributeLabels()[$attribute];
    }

    /*
     * Les attributs renseignés peuvent être affectés massivement et être soumis à validation
     * (ex : si on met [] pour search, il n'y aura que le filtre sur la date dans list.php)
     */
    public function scenarios() {
        $scenarios = parent::scenarios();

        $allAttributes = array_keys($this->attributeLabels());

        $scenarios['search'] = $allAttributes;
        $scenarios['deleteImageArticle'] = $allAttributes;
        $scenarios['generateFakeArticle'] = $allAttributes;

	return $scenarios;
    }

    // only fields in rules() are searchable
    public function rules()
    {
        return [
            [['a_date_creation', 'a_nom', 'a_description', 'a_prix', 'a_quantite', 'a_image'], 'safe'],

            [['a_date_creation'], 'date', 'format' => 'php:Y-m-d', 'except' => 'search'],
            [['a_nom'], 'string', 'length' => [0, 50]],
            [['a_description'], 'string', 'length' => [0, 250]],
            [['a_prix'], 'number', 'message' => $this->getLabel('a_prix') . ' doit être un nombre (ex : 0.00)'],
            [['a_quantite'], 'integer', 'message' => $this->getLabel('a_quantite') . ' doit être un entier sans espace'],
            ['a_image', 'file', 'extensions' => 'png, jpg, jpeg'],

            [['a_date_creation', 'a_nom', 'a_description', 'a_prix', 'a_quantite'], 'required', 'except' => 'search'],
        ];
    }

    public function beforeValidate() {
        //l'image est obligatoire selon le scénario
        if (empty($this->a_image) && !in_array($this->scenario, ['search', 'deleteImageArticle'])) {
            $this->addError('a_image', $this->getLabel('a_image') . ' est obligatoire.');
        }

        //on remet les données en format sql selon le scénario
        if (!in_array($this->scenario, ['search', 'deleteImageArticle', 'generateFakeArticle'])) {
            $this->formatSql();
        }

        return parent::beforeValidate();
    }

    /*
     * attention si on fait par ex. Article:findOne le scenario sera "default"
     * dans afterfind même si on change le scénario après Article:findOne
    */
    public function afterFind() {
        return parent::afterFind();
    }

    public function afterDelete() {
        $this->deleteImage();
        return parent::afterDelete();
    }

    public function formatSql() {
        if (!empty($this->a_date_creation)) {
            $this->a_date_creation = \components\MyHelpers::convertDateFrtoSql($this->a_date_creation);
        }
        if (!empty($this->a_prix)) {
            $this->a_prix = \components\MyHelpers::convertNumberFrtoSql($this->a_prix);
        }
        if (!empty($this->a_quantite)) {
            $this->a_quantite = \components\MyHelpers::convertNumberFrtoSql($this->a_quantite);
        }
    }

    /* Propriétés disponibles : vendor/fakerphp/faker/src/Faker/Generator.php */
    public static function generateFakeArticle() {
        $faker = Faker\Factory::create();

        $article = new Article();
        $article->scenario = 'generateFakeArticle';
        $article->a_date_creation = $faker->date;
        $article->a_nom = $faker->name;
        $article->a_description = $faker->text(250);
        $article->a_prix = $faker->randomFloat(2, 1, 10);
        $article->a_quantite = $faker->randomDigitNotNull;
        //télécharge l'image indiquée dans la destination indiquée
        $article->a_image = $faker->file(Yii::$app->basePath . '/web/uploads/defaultImageFaker/', Yii::$app->basePath . '/web/uploads/article/', false);
        $article->save();
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

        $this->formatSql();

        $query->andFilterWhere(['a_id' => $this->a_id]);
        $query->andFilterWhere(['like', 'a_date_creation', $this->a_date_creation])
            ->andFilterWhere(['like', 'a_nom', $this->a_nom])
            ->andFilterWhere(['like', 'a_description', $this->a_description])
            ->andFilterWhere(['like', 'a_prix', $this->a_prix])
            ->andFilterWhere(['like', 'a_quantite', $this->a_quantite]);

        return $dataProvider;
    }

    //supprime physiquement l'image stockée dans l'application
    public function deleteImage() {
        unlink(Yii::$app->basePath . '/web/uploads/article/' . $this->a_image);
    }

}
