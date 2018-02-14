<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;

$urlPendientes = Url::to(['alquileres/pendientes']);
$js = <<<EOT
$('form.devolver-ajax').submit(function(event) {
    event.preventDefault();
    var form = $(event.target);
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
                    data: { numero: $('#gestionar-pelicula-form').yiiActiveForm('find', 'numero').value },
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
            'pelicula.enlace:html',
            'created_at:datetime',
            [
                'class' => ActionColumn::className(),
                'header' => 'Devolver',
                'template' => '{devolver}',
                'buttons' => [
                    'devolver' => function ($url, $model, $key) {
                        return Html::beginForm(['alquileres/devolver-ajax'], 'post', [
                                'class' => 'devolver-ajax',
                            ])
                            . Html::hiddenInput('id', $model->id)
                            . Html::submitButton('Devolver', ['class' => 'btn-xs btn-danger'])
                            . Html::endForm();
                    },
                ],
            ],
        ],
    ]) ?>
<?php endif ?>
