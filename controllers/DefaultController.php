<?php

namespace modules\purifier\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'converting' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Форма и предпросмотр
     * @return string
     */
    public function actionIndex()
    {
        $model = new PurifierForm();
        if ($model->load(Yii::$app->request->post())) {
            $items = $model->getPreviewData(3);
            return $this->render('index', [
                'model' => $model,
                'items' => $items,
            ]);
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Обработка данных
     */
    public function actionConverting()
    {
        $model = new PurifierForm();
        if ($model->load(Yii::$app->request->post())) {
            $result = $model->getColumnUpdate();
            if ($result === true) {
                Yii::$app->session->setFlash('success', Module::t('module', 'The data was successfully processed.'));
            } else if (is_array($result)) {
                $string = '';
                foreach ($result as $item) {
                    $string .= $item . '<br>';
                }
                Yii::$app->session->setFlash('danger', $string);
            } else {
                Yii::$app->session->setFlash('danger', Module::t('module', 'Error in data processing!'));
            }
        }
        $this->redirect('index');
    }
}
