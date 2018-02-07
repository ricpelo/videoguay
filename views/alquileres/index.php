<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AlquileresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alquileres';
$this->params['breadcrumbs'][] = $this->title;

function format($v)
{
    return $v === null ? '' : Yii::$app->formatter->asDatetime($v);
}

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
                'format' => 'datetime',
                'filterType' => 'kartik\datecontrol\DateControl',
                'filterWidgetOptions' => [
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'type' => 'datetime',
                    'displayFormat' => 'php:d-m-Y H:i:s',
                    'displayTimezone' => \Yii::$app->formatter->timeZone,
                    'saveFormat' => 'php:Y-m-d H:i:s',
                    'saveTimezone' => 'UTC',
                    'readonly' => true,
                ],
            ],
            [
                'attribute' => 'devolucion',
                'format' => 'datetime',
                'filterType' => GridView::FILTER_DATETIME,
                'filterWidgetOptions' => [
                    'model' => $searchModel,
                    'attribute' => 'devolucion',
                    'options' => [
                        'value' => format($searchModel->devolucion),
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy hh:ii:ss',
                    ],
                    'readonly' => true,
                ],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
