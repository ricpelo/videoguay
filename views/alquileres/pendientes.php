<?php

use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;

$urlPendientes = Url::to(['alquileres/pendientes']);
$js = <<<EOT
$('.grid-view form').on('submit', function (event) {
    event.preventDefault();
    var form = $(event.target); // $(this)
    var data = form.serialize();
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            if (data) {
                $.ajax({
                    url: '$urlPendientes',
                    type: 'GET',
                    data: {
                        numero: $('#gestionar-pelicula-form').yiiActiveForm('find', 'numero').value
                    },
                    success: function (data) {
                        $('#pendientes').html(data);
                        botonAlquilar();
                    }
                });
            }
        }
    });
});
EOT;
$this->registerJs($js);
?>
<?php if (!$pendientes->exists()): ?>
    <h3>No tiene alquileres pendientes</h3>
<?php else: ?>
    <h3>Alquileres pendientes</h3>
    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $pendientes,
            'pagination' => false,
            'sort' => false,
        ]),
        'columns' => [
            'pelicula.codigo',
            'pelicula.titulo',
            'created_at:datetime',
            [
                'class' => ActionColumn::className(),
                'template' => '{devolver}',
                'header' => 'Devolver',
                'buttons' => [
                    'devolver' => function ($url, $model, $key) {
                        return Html::beginForm(
                            ['alquileres/devolver-ajax'],
                            'post'
                        )
                        . Html::hiddenInput('id', $model->id)
                        . Html::submitButton(
                            'Devolver',
                            ['class' => 'btn-xs btn-danger']
                        )
                        . Html::endForm();
                    },
                ],
            ],
        ],
    ]) ?>
<?php endif ?>
