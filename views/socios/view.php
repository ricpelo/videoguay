<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Socios */
/* @var $peliculas app\models\Peliculas[] */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Socios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socios-view">

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
            'numero',
            'nombre',
            'direccion',
            'telefono',
        ],
    ]) ?>

    <h3>Últimas peliculas alquiladas</h3>

    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $peliculas,
            'pagination' => false,
            'sort' => false,
        ]),
    ]) ?>

    <table class="table">
        <thead>
            <th>Código</th>
            <th>Título</th>
        </thead>
        <?php foreach ($peliculas->all() as $pelicula): ?>
            <tr>
                <td><?= Html::encode($pelicula->codigo) ?></td>
                <td><?= Html::encode($pelicula->titulo) ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
