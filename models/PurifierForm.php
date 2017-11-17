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
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function processColumnUpdate()
    {
        $after_column = $this->getAfterColumn();
        $column = $this->getBeforeColumn();
        /** @var  $model object */
        $model = $this->model_namespace;
        $models = $model::find()->select(['id', $column])->all();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($models as $item) {
                /** @var  $one object */
                $one = $model::findOne($item->id);
                $one->$after_column = $this->processPurifier($item->$column);
                $one->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
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
            $result = explode(',', $this->removingSpaces($this->forbidden_elements));
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
            $result = explode(',', $this->removingSpaces($this->forbidden_attributes));
        }
        return $result;
    }

    /**
     * Удаляем пробелы в строке
     * @param $string
     * @return mixed|string
     */
    public function removingSpaces($string = '')
    {
        $string = trim($string);
        $string = preg_replace('/\s/', '', $string);
        return $string;
    }
}
