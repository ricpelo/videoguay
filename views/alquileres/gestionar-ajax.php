<?php
use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

$this->title = 'Gestión de alquileres'
    . (isset($socio) ? (' del socio ' . $socio->nombre) : '');
$this->params['breadcrumbs'][] = [
    'label' => 'Gestionar alquileres',
    'url' => ['alquileres/gestionar']
];
if (isset($socio)) {
    $this->params['breadcrumbs'][] = $socio->nombre;
}
$urlPeliculasAlquilada = Url::to(['peliculas/alquilada']);
$js = <<<EOT
function isEmpty(el) {
    return !$.trim(el.html());
}
function botonAlquilar() {
    if (!isEmpty($('#socio')) && !isEmpty($('#pelicula'))) {
        $.ajax({
            url: '$urlPeliculasAlquilada',
            type: 'GET',
            data: {
                codigo: $('#gestionar-pelicula-form').yiiActiveForm('find', 'codigo').value
            },
            success: function (data) {
                if (data) {
                    $('#btn-alquilar').hide();
                } else {
                    $('#btn-alquilar').show();
                }
            }
        });
    } else {
        $('#btn-alquilar').hide();
    }
}
EOT;
$this->registerJs($js, View::POS_HEAD);
$urlSociosDatosAjax = Url::to(['socios/datos-ajax']);
$urlPeliculasDatosAjax = Url::to(['peliculas/datos-ajax']);
$urlAlquileresPendientes = Url::to(['alquileres/pendientes']);
$js = <<<EOT
var form = $('#gestionar-pelicula-form');
form.on('afterValidateAttribute', function (event, attribute, messages) {
    switch (attribute.name) {
        case 'numero':
            if (messages.length === 0) {
                $.ajax({
                    url: '$urlSociosDatosAjax',
                    type: 'GET',
                    data: {
                        numero: form.yiiActiveForm('find', 'numero').value
                    },
                    success: function (data) {
                        $('#socio').html(data);
                        botonAlquilar();
                    }
                });
                $.ajax({
                    url: '$urlAlquileresPendientes',
                    type: 'GET',
                    data: {
                        numero: form.yiiActiveForm('find', 'numero').value
                    },
                    success: function (data) {
                        $('#pendientes').html(data);
                    }
                });
            } else {
                $('#socio').empty();
                $('#pendientes').empty();
                botonAlquilar();
            }
            break;
        case 'codigo':
            if (messages.length === 0) {
                $.ajax({
                    url: '$urlPeliculasDatosAjax',
                    type: 'GET',
                    data: {
                        codigo: form.yiiActiveForm('find', 'codigo').value
                    },
                    success: function (data) {
                        $('#pelicula').html(data);
                        botonAlquilar();
                    }
                });
            } else {
                $('#pelicula').empty();
                botonAlquilar();
            }
            break;
    }
});
$('#alquilar-ajax').on('beforeSubmit', function () {
    $.ajax({
        url: $('#alquilar-ajax').attr('action'),
        type: 'POST',
        data: {
            numero: form.yiiActiveForm('find', 'numero').value,
            codigo: form.yiiActiveForm('find', 'codigo').value
        },
        success: function (data) {
            if (data) {
                $.ajax({
                    url: '$urlAlquileresPendientes',
                    type: 'GET',
                    data: {
                        numero: form.yiiActiveForm('find', 'numero').value
                    },
                    success: function (data) {
                        $('#pendientes').html(data);
                        $('#codigo').val('');
                        $('#pelicula').empty();
                        botonAlquilar();
                    }
                });
            }
        }
    });
    return false;
});
EOT;
$this->registerJs($js);
?>
<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'gestionar-pelicula-form',
            'method' => 'get',
            'action' => ['alquileres/gestionar-ajax'],
        ]) ?>
            <?= $form->field(
                $gestionarPeliculaForm,
                'numero',
                ['enableAjaxValidation' => true]
            ) ?>
            <div id="socio">
            </div>
            <?= $form->field(
                $gestionarPeliculaForm,
                'codigo',
                ['enableAjaxValidation' => true]
            ) ?>
            <div id="pelicula">
            </div>
        <?php ActiveForm::end() ?>
        <?php $form = ActiveForm::begin([
            'id' => 'alquilar-ajax',
            'action' => ['alquileres/alquilar-ajax'],
        ]) ?>
            <div id="btn-alquilar" class="form-group" style="display: none;">
                <?= Html::submitButton('Alquilar', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end() ?>
    </div>
    <div class="col-md-6">
        <div id="pendientes">
        </div>
    </div>
</div>
