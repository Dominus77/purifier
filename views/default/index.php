<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use modules\purifier\Module;

/* @var $this yii\web\View */

$this->title = Module::t('module', 'Purifier Converter');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purifier-default-index">

    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'before_table')->textInput([
                'maxlength' => true,
                'placeholder' => 'app\models\Post',
            ]) ?>

            <?= $form->field($model, 'before_column')->textInput([
                'maxlength' => true,
                'placeholder' => 'content',
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'after_table')->textInput([
                'maxlength' => true,
                'placeholder' => 'app\models\Post',
            ]) ?>

            <?= $form->field($model, 'after_column')->textInput([
                'maxlength' => true,
                'placeholder' => 'content_after',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
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
                    'class' => 'btn btn-primary'
                ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        /** @var  $before_data array */
        if ($before_data) {
            foreach ($before_data as $item) {
                echo $this->render('_list', ['item' => $item]);
            }
        }
        ?>
    </div>
    <div class="col-md-6">
        <?php
        /** @var  $after_data array */
        if ($after_data) {
            foreach ($after_data as $item) {
                echo $this->render('_list', ['item' => $item]);
            }
        }
        ?>
    </div>
</div>
