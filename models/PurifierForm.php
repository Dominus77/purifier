<?php

namespace modules\purifier\models;

use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;
use modules\purifier\Module;

/**
 * Class PurifierForm
 * @package modules\purifier\models
 */
class PurifierForm extends Model
{
    public $model_namespace;        // Модель Namespace
    public $before_column;          // Исходная колонка
    public $after_column;           // Конечная колонка
    public $forbidden_elements;     // Запрещёные тэги
    public $forbidden_attributes;   // Запрещёные аттрибуты у тэгов

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_namespace', 'before_column', 'after_column'], 'required'],
            [['model_namespace', 'before_column', 'after_column', 'forbidden_attributes', 'forbidden_elements'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_namespace' => Module::t('module', 'Model'),
            'before_column' => Module::t('module', 'Before Column'),
            'after_column' => Module::t('module', 'After Column'),
            'forbidden_elements' => Module::t('module', 'Forbidden HTML Elements'),
            'forbidden_attributes' => Module::t('module', 'Forbidden attributes for HTML Tags'),
        ];
    }

    /**
     * Исходная колонка
     * @return mixed
     */
    protected function getBeforeColumn()
    {
        return $this->before_column;
    }

    /**
     * Конечная колонка
     * @return mixed
     */
    protected function getAfterColumn()
    {
        return $this->after_column;
    }

    /**
     * Данные предварительного просмотра
     * @param int $limit
     * @return array
     */
    public function getPreviewData($limit = 1)
    {
        /** @var  $model object */
        $model = $this->model_namespace;
        $column = $this->getBeforeColumn();
        $query = $model::find()->select([$column])->limit($limit)->all();
        $array = [];
        foreach ($query as $item) {
            $array[] = ArrayHelper::getValue($item, $column);
        }
        return $array;
    }

    /**
     * Обновление данных в колонке
     * @return bool
     */
    public function getColumnUpdate()
    {
        $error = [];
        $after_column = $this->getAfterColumn();
        $column = $this->getBeforeColumn();
        /** @var  $model object */
        $model = $this->model_namespace;
        $models = $model::find()->select(['id', $column, $after_column])->all();
        foreach ($models as $item) {
            /** @var  $one object */
            $one = $model::findOne($item->id);
            $one->$after_column = $this->processPurifier($item->$column);
            if (!$one->save())
                $error[] = Module::t('module', 'Error while saving item with id:{:Id}', [':Id' => $one->id]);
        }
        if (!empty($error))
            return $error;
        return true;
    }

    /**
     * Обработка текста HTML Purifier
     * @param $data
     * @param array $options
     * @see http://htmlpurifier.org/live/configdoc/plain.html
     * @return string
     */
    public function processPurifier($data, $options = [])
    {
        $options = ArrayHelper::merge([
            'HTML.ForbiddenAttributes' => $this->getForbiddenAttributesArray(),
            'HTML.ForbiddenElements' => $this->getForbiddenElementsArray(),
        ], $options);
        $purifier = new HtmlPurifier();
        return $purifier->process($data, $options);
    }

    /**
     * Запрещённые тэги
     * @see http://htmlpurifier.org/live/configdoc/plain.html#HTML.ForbiddenElements
     * @return array
     */
    protected function getForbiddenElementsArray()
    {
        $result = [];
        if ($this->forbidden_elements) {
            $string = $this->forbidden_elements;
            $string = trim($string);
            $string = preg_replace('/\s/', '', $string);
            $result = explode(',', $string);
        }
        return $result;
    }

    /**
     * Запрещённые атрибуты тэгов
     * @see http://htmlpurifier.org/live/configdoc/plain.html#HTML.ForbiddenAttributes
     * @return array
     */
    protected function getForbiddenAttributesArray()
    {
        $result = [];
        if ($this->forbidden_attributes) {
            $string = $this->forbidden_attributes;
            $string = trim($string);
            $string = preg_replace('/\s/', '', $string);
            $result = explode(',', $string);
        }
        return $result;
    }
}
