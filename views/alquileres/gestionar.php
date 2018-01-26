<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this \yii\web\View */
/** @var $gestionarSocioForm \app\models\GestionarSocioForm */
/** @var $gestionarPeliculaForm \app\models\GestionarPeliculaForm */
/** @var $socio \app\models\Socios */
/** @var $pelicula \app\models\Peliculas */
?>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['alquileres/gestionar'],
        ]) ?>
            <?= $form->field($gestionarSocioForm, 'numero') ?>
            <div class="form-group">
                <?= Html::submitButton('Buscar socio', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end() ?>

        <?php if (isset($socio)): ?>
            <h4><?= Html::encode($socio->nombre) ?></h4>
            <h4><?= Html::encode($socio->telefono) ?></h4>

            <hr>

            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['alquileres/gestionar'],
            ]) ?>
                <?= Html::hiddenInput('numero', $gestionarPeliculaForm->numero) ?>
                <?= $form->field($gestionarPeliculaForm, 'codigo') ?>
                <div class="form-group">
                    <?= Html::submitButton('Buscar película', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end() ?>

            <?php if (isset($pelicula)): ?>
                <h4><?= Html::encode($pelicula->titulo) ?></h4>
                <h4><?= Html::encode(
                    Yii::$app->formatter->asCurrency($pelicula->precio_alq)
                ) ?></h4>

                <?php if ($pelicula->estaAlquilada): ?>
                    <h4>Película ya alquilada</h4>
                <?php else: ?>
                    <?= Html::beginForm([
                        'alquileres/alquilar',
                        'numero' => $socio->numero,
                    ]) ?>
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
        <?php if (isset($socio)): ?>
            <?php $pendientes = $socio->getPendientes()->with('pelicula') ?>
            <?php if ($pendientes->exists()): ?>
                <h3>Alquileres pendientes</h3>
                <table class="table">
                    <thead>
                        <th>Código</th>
                        <th>Título</th>
                        <th>Fecha de alquiler</th>
                        <th>Devolución</th>
                    </thead>
                    <tbody>
                        <?php foreach ($pendientes->each() as $alquiler): ?>
                            <tr>
                                <td><?= Html::encode($alquiler->pelicula->codigo) ?></td>
                                <td><?= Html::encode($alquiler->pelicula->titulo) ?></td>
                                <td><?= Html::encode(
                                    Yii::$app->formatter->asDatetime($alquiler->created_at)
                                ) ?></td>
                                <?= Html::beginForm(['alquileres/devolver', 'numero' => $socio->numero], 'post') ?>
                                    <?= Html::hiddenInput('id', $alquiler->id) ?>
                                    <td><?= Html::submitButton('Devolver', ['class' => 'btn-xs btn-danger']) ?></td>
                                <?= Html::endForm() ?>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h3>No tiene películas pendientes</h3>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>
