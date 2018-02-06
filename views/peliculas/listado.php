<?php
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\DataColumn;
use yii\grid\ActionColumn;
use yii\grid\SerialColumn;

use yii\helpers\Url;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dataProvider ActiveDataProvider */
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => SerialColumn::className()],
        'codigo:text:Número',
        'titulo',
        [
            'attribute' => 'todo',
            'value' => function ($model, $key, $index, $column) {
                return $model->codigo . ' ' . $model->titulo . ' '
                    . Yii::$app->formatter->asCurrency($model->precio_alq);
            },
            'format' => 'text',
        ],
        [
            'class' => ActionColumn::className(),
            'header' => 'Acciones',
            'template' => '{delete} {update} {devolver}',
            'buttons' => [
                'devolver' => function ($url, $model, $key) {
                    if ($model->estaAlquilada) {
                        Yii::$app->session->set('rutaVuelta', Url::to());
                        return Html::beginForm([
                            'alquileres/devolver',
                            'numero' => $model->pendiente->socio->numero,
                        ], 'post', ['style' => 'display: inline'])
                        . Html::hiddenInput('id', $model->pendiente->id)
                        . Html::submitButton('Devolver', ['class' => 'btn btn-xs btn-success'])
                        . Html::endForm();
                    }
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        'Borrar',
                        [
                            'peliculas/delete',
                            'id' => $model->id
                        ],
                        [
                            'data-method' => 'post',
                            'class' => 'btn btn-xs btn-danger'
                        ]
                    );
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(
                        'Cambiar',
                        [
                            'peliculas/update',
                            'id' => $model->id
                        ],
                        ['class' => 'btn btn-xs btn-info']
                    );
                },
            ]
        ],
    ],
]) ?>
