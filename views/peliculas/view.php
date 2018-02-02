<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Peliculas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Peliculas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peliculas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'codigo',
            'titulo',
            'precio_alq:currency',
        ],
    ]) ?>

    <h3>Últimos alquileres de esta película</h3>

    <table class="table">
        <thead>
            <th>Número</th>
            <th>Nombre</th>
            <th>Fecha de alquiler</th>
        </thead>
        <tbody>
            <?php foreach ($alquileres as $alquiler): ?>
                <tr>
                    <td><?= Html::encode($alquiler->socio->numero) ?></td>
                    <td><?= Html::encode($alquiler->socio->nombre) ?></td>
                    <td><?= Html::encode($alquiler->created_at) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>
