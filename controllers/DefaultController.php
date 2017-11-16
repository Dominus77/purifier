<?php

namespace modules\purifier\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use modules\purifier\models\PurifierForm;
use modules\purifier\Module;
use yii\helpers\VarDumper;

/**
 * Class DefaultController
 * @package modules\purifier\controllers
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new PurifierForm();
        $before_data = [];
        $after_data = [];
        if ($model->load(Yii::$app->request->post())) {
            $before_data = $model->getDataArray();
            $after_data = $model->getDataPurifierArray();
        }
        return $this->render('index', [
            'model' => $model,
            'before_data' => $before_data,
            'after_data' => $after_data,
        ]);
    }
}
