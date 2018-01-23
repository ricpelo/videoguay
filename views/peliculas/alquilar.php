<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Alquilar una película';
$this->params['breadcrumbs'][] = ['label' => 'Peliculas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = \yii\helpers\Url::to(['peliculas/listado']);

$js=<<<EOF
$('#alquilar-form').on('afterValidateAttribute', function (event, attribute, messages) {
    if (attribute.name == 'numero') {
        if (messages.length === 0) {
            var numero = $('#alquilarform-numero').val();
            $.get('$url', { numero: numero })
                .done(function (data) {
                    $('#contenedor').html(data);
                });
        } else {
            $('#contenedor').empty();
        }
    }
});
EOF;

$this->registerJs($js);
?>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'alquilar-form',
        ]) ?>
            <?= $form->field($alquilarForm, 'numero', ['enableAjaxValidation' => true]) ?>
            <?= $form->field($alquilarForm, 'codigo', ['enableAjaxValidation' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Alquilar', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end() ?>
    </div>
    <div id="contenedor" class="col-md-6">
    </div>
</div>
