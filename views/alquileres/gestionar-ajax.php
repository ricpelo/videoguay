<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/** @var $this \yii\web\View */
/** @var $gestionarSocioForm \app\models\GestionarSocioForm */
/** @var $gestionarPeliculaForm \app\models\GestionarPeliculaForm */
/** @var $socio \app\models\Socios */
/** @var $pelicula \app\models\Peliculas */

$this->title = 'Gestión de alquileres'
    . (isset($socio) ? (' del socio ' . $socio->nombre) : '');
$this->params['breadcrumbs'][] = [
    'label' => 'Gestionar alquileres',
    'url' => ['alquileres/gestionar']
];
if (isset($socio)) {
    $this->params['breadcrumbs'][] = $socio->nombre;
}
$urlSocio = Url::to(['socios/ajax']);
$urlPelicula = Url::to(['peliculas/ajax']);
$urlAlquilada = Url::to(['peliculas/alquilada']);
$urlPendientes = Url::to(['alquileres/pendientes']);
$js = <<<EOT
function isEmpty(el) {
    return !$.trim(el.html());
}
function botonAlquilar() {
    if (!isEmpty($('#socio')) && !isEmpty($('#pelicula'))) {
        $.ajax({
            url: '$urlAlquilada',
            type: 'GET',
            data: { codigo: $('#gestionar-pelicula-form').yiiActiveForm('find', 'codigo').value },
            success: function (data) {
                if (!data) {
                    $('#alquilada').hide();
                    $('#btn-alquilar').show();
                } else {
                    $('#alquilada').show();
                    $('#btn-alquilar').hide();
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
    } else {
        $('#btn-alquilar').hide();
    }
}
EOT;
$this->registerJs($js, yii\web\View::POS_HEAD);
$js = <<<EOT
var form=$('#gestionar-pelicula-form');
form.on('afterValidateAttribute', function (event, attribute, messages, deferreds) {
    switch (attribute.name) {
        case 'numero':
            if (messages.length === 0) {
                $.ajax({
                    url: '$urlSocio',
                    type: 'GET',
                    data: { numero: form.yiiActiveForm('find', 'numero').value },
                    success: function (data) {
                        $('#socio').html(data);
                        botonAlquilar();
                    },
                    error: function(jqXHR, errMsg) {
                        alert(errMsg);
                    }
                });
                $.ajax({
                    url: '$urlPendientes',
                    type: 'GET',
                    data: { numero: form.yiiActiveForm('find', 'numero').value },
                    success: function (data) {
                        $('#pendientes').html(data);
                    },
                    error: function(jqXHR, errMsg) {
                        alert(errMsg);
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
                    url: '$urlPelicula',
                    type: 'GET',
                    data: { codigo: form.yiiActiveForm('find', 'codigo').value },
                    success: function (data) {
                        $('#pelicula').html(data);
                        botonAlquilar();
                    },
                    error: function(jqXHR, errMsg) {
                        alert(errMsg);
                    }
                });
            } else {
                $('#pelicula').empty();
                botonAlquilar();
            }
            break;
    }
});
$('#alquilar-ajax').on('beforeSubmit', function() {
    var data = form.serialize();
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
                    url: '$urlPendientes',
                    type: 'GET',
                    data: { numero: form.yiiActiveForm('find', 'numero').value },
                    success: function (data) {
                        $('#pendientes').html(data);
                    },
                    error: function(jqXHR, errMsg) {
                        alert(errMsg);
                    }
                });
            }
            $('#codigo').val('');
            $('#pelicula').empty();
            botonAlquilar();
        },
        error: function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
     return false; // prevent default submit
});
EOT;
$this->registerJs($js);
?>

<div class="row">
    <div id="prueba"></div>
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'gestionar-pelicula-form',
            'method' => 'get',
            'action' => ['alquileres/gestionar-ajax'],
        ]) ?>
            <?= $form->field($gestionarPeliculaForm, 'numero', ['enableAjaxValidation' => true]) ?>
            <div id="socio">
            </div>
            <?= $form->field($gestionarPeliculaForm, 'codigo', ['enableAjaxValidation' => true]) ?>
            <div id="pelicula">
            </div>
            <h4 id="alquilada" style="display: none;">Película ya alquilada</h4>
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
