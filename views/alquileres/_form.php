<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alquileres-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'socio_id')->textInput() ?>

    <?= $form->field($model, 'pelicula_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATETIME,
    ]); ?>

    <?= $form->field($model, 'devolucion')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATETIME,
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
