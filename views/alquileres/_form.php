<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alquileres-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'socio_id')->textInput() ?>

    <?= $form->field($model, 'pelicula_id')->textInput() ?>

    <?= $form->field($model, 'createdAtForm')->textInput() ?>

    <?= $form->field($model, 'devolucion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
