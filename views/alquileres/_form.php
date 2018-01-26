<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alquileres-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'socio_id')->textInput() ?>

    <?= $form->field($model, 'pelicula_id')->textInput() ?>

    <?= $form->field($model, 'created_at')
        ->widget(DateTimePicker::classname(), [
            'options' => [
                'placeholder' => 'Introduzca instante...',
                'value' => Yii::$app->formatter->asDatetime($model->created_at, 'php:d-m-Y H:i:s'),
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd-mm-yyyy hh:ii:ss',
            ]
        ]) ?>

    <?= $form->field($model, 'devolucion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
