<?php
use yii\helpers\Html;
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

$js = <<<EOT
var f = $('#alquilar-form');
f.on('beforeSubmit', function() {
    alert('hola');
    return false;
    var data = f.serialize();
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            alert(data);
            // Implement successful
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
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'id' => 'gestionar-socio-form',
            'method' => 'get',
            'action' => ['alquileres/gestionar'],
        ]) ?>
            <?= $form->field($gestionarSocioForm, 'numero', ['enableAjaxValidation' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('Buscar socio', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end() ?>

        <?php if (isset($socio)): ?>
            <h4><?= $socio->enlace ?></h4>
            <h4><?= Html::encode($socio->telefono) ?></h4>

            <hr>

            <?php $form = ActiveForm::begin([
                'id' => 'gestionar-pelicula-form',
                'method' => 'get',
                'action' => ['alquileres/gestionar'],
            ]) ?>
                <?= Html::hiddenInput('numero', $gestionarPeliculaForm->numero) ?>
                <?= $form->field($gestionarPeliculaForm, 'codigo', ['enableAjaxValidation' => true]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Buscar película', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end() ?>

            <?php if (isset($pelicula)): ?>
                <h4><?= $pelicula->enlace ?></h4>
                <h4><?= Html::encode(
                    Yii::$app->formatter->asCurrency($pelicula->precio_alq)
                ) ?></h4>

                <?php if ($pelicula->estaAlquilada): ?>
                    <h4>Película ya alquilada por <?= $pelicula->pendiente->socio->enlace ?></h4>
                <?php else: ?>
                    <?= Html::beginForm([
                        'alquileres/alquilar-ajax',
                        'numero' => $socio->numero,
                    ], 'POST', ['id' => 'alquilar-form']) ?>
                        <?= Html::hiddenInput('socio_id', $socio->id) ?>
                        <?= Html::hiddenInput('pelicula_id', $pelicula->id) ?>
                        <div class="form-group">
                            <?= Html::submitButton('Alquilar', [
                                'class' => 'btn btn-success'
                            ]) ?>
                        </div>
                    <?= Html::endForm() ?>
                <?php endif ?>
            <?php endif ?>
        <?php endif ?>
    </div>
    <div class="col-md-6">
        <div id="pendientes">
        </div>
    </div>
</div>
