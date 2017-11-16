<?php

namespace modules\purifier\models;

use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;
use modules\purifier\Module;
use yii\helpers\VarDumper;

/**
 * Class PurifierForm
 * @package modules\purifier\models
 */
class PurifierForm extends Model
{
    public $before_table;           // Начальная таблица Модель
    public $after_table;            // Конечная таблица Модель
    public $before_column;          // Колонка в начальной таблице
    public $after_column;           // Колонка в конечной таблице
    public $forbidden_elements;         // Запрещёные тэги
    public $forbidden_attributes;   // Запрещёные аттрибуты у тэгов

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['before_table', 'before_column'], 'required'],
            [['before_table', 'after_table', 'before_column', 'after_column', 'forbidden_attributes', 'forbidden_elements'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'before_table' => Module::t('module', 'Before Table'),
            'after_table' => Module::t('module', 'After Table'),
            'before_column' => Module::t('module', 'Before Column'),
            'after_column' => Module::t('module', 'After Column'),
            'forbidden_elements' => Module::t('module', 'Forbidden HTML Elements'),
            'forbidden_attributes' => Module::t('module', 'Forbidden attributes for HTML Tags'),
        ];
    }

    /**
     * Начальная таблица Модель
     * @param int $limit
     * @return mixed
     */
    public function getBeforeData($limit=1)
    {
        /** @var  $model object */
        $model = $this->before_table;
        $column = $this->getBeforeColumn();
        return $model::find()->select([$column])->limit($limit)->all();
    }

    /**
     * Конечная таблица Модель
     * @param int $limit
     * @return mixed
     */
    public function getAfterData($limit=1)
    {
        /** @var  $model object */
        $model = $this->after_table;
        $column = $this->getAfterColumn();
        return $model::find()->select([$column])->limit($limit)->all();
    }

    /**
     * Начальная колонка в начальной таблице
     * @return mixed
     */
    public function getBeforeColumn()
    {
        return $this->before_column;
    }

    /**
     * Конечная колонка в конечной таблице
     * @return mixed
     */
    public function getAfterColumn()
    {
        return $this->after_column;
    }

    /**
     * @return array
     */
    public function getDataArray()
    {
        $column = $this->getBeforeColumn();
        $data = $this->getBeforeData();
        $array = [];
        foreach ($data as $item) {
            $array[] = ArrayHelper::getValue($item, $column);
        }
        return $array;
    }

    /**
     * @return array
     */
    public function getDataPuriferArray()
    {
        $column = $this->getBeforeColumn();
        $data = $this->getBeforeData();
        $array = [];
        foreach ($data as $item) {
            $array[] = $this->processPurifer(ArrayHelper::getValue($item, $column));
        }
        return $array;
    }

    /**
     * @return array
     */
    public function getForbiddenElementsArray()
    {
        $result = [];
        if($this->forbidden_elements) {
            $string = $this->forbidden_elements;
            $string = trim($string);
            $string = preg_replace('/\s/', '', $string);
            $result = explode(',', $string);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getForbiddenAttributesArray()
    {
        $result = [];
        if($this->forbidden_attributes) {
            $string = $this->forbidden_attributes;
            $string = trim($string);
            $string = preg_replace('/\s/', '', $string);
            $result = explode(',', $string);
        }
        return $result;
    }

    /**
     * @param $data
     * @param array $options
     * @see http://htmlpurifier.org/live/configdoc/plain.html
     * @return string
     */
    public function processPurifer($data, $options = [])
    {
        $options = [
            'HTML.ForbiddenAttributes' => $this->getForbiddenAttributesArray(),
            'HTML.ForbiddenElements' => $this->getForbiddenElementsArray(),
        ];
        $purifier = new HtmlPurifier();
        return $purifier->process($data, $options);
    }
}
