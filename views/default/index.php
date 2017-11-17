<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;
use modules\purifier\Module;

/* @var $this yii\web\View */
/* @var $model modules\purifier\models\PurifierForm */

$this->title = Module::t('module', 'Purifier Converter');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="purifier-default-index">

        <h1><?= $this->title ?></h1>

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'model_namespace')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'app\models\Post',
                ]) ?>

                <?= $form->field($model, 'before_column')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'content',
                ]) ?>
                <?= $form->field($model, 'after_column')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'content_after',
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'forbidden_elements')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'p, div, span, ...',
                ]) ?>
                <?= $form->field($model, 'forbidden_attributes')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'style, class, ...',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton(Module::t('module', 'Preview'), [
                        'class' => 'btn btn-primary',
                        'formaction' => Url::to(['/purifier/default/index']),
                    ]) ?>
                    <?= Html::submitButton(Module::t('module', 'Converting'), [
                        'class' => 'btn btn-success',
                        'formaction' => Url::to(['/purifier/default/converting']),
                    ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php if (isset($items)) : ?>
    <div class="row">
        <div class="col-md-6">
            <h3>Исходный текст</h3>
            <?php foreach ($items as $item) {
                echo $this->render('_list', ['item' => $item]);
            } ?>
        </div>
        <div class="col-md-6">
            <h3>Обработанный текст</h3>
            <?php foreach ($items as $item) {
                echo $this->render('_list', ['item' => $model->processPurifier($item)]);
            } ?>
        </div>
    </div>
<?php endif; ?>