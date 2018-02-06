<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AlquileresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alquileres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alquileres-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Alquileres', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'socio_id',
            'pelicula.titulo',
            [
                'attribute' => 'created_at',
                'filter' => DateTimePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'options' => [
                        'value' => $searchModel->created_at === null ? '' : Yii::$app->formatter->asDatetime($searchModel->created_at),
                    ],
                    'readonly' => true,
                    'pluginOptions' => [
                        'weekStart' => 1,
                        'format' => 'dd-mm-yyyy hh:ii:ss',
                    ],
               ]) // . Html::error($searchModel, 'created_at')
            ],
            //'created_at:datetime',
            'devolucion:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
