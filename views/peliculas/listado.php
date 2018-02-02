<?php
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\DetailView;

/** @var $this \yii\web\View */
/** @var $dataProvider ActiveDataProvider */
?>

<table class="table table-striped">
    <thead>
        <th><?= $dataProvider->sort->link('codigo') ?></th>
        <th><?= $dataProvider->sort->link('titulo') ?></th>
        <th><?= $dataProvider->sort->link('precio_alq') ?></th>
    </thead>
    <tbody>
        <?php foreach ($dataProvider->getModels() as $pelicula): ?>
            <tr>
                <td><?= Html::encode($pelicula->codigo) ?></td>
                <td><?= Html::encode($pelicula->titulo) ?></td>
                <td><?= Html::encode(
                    Yii::$app->formatter->asCurrency($pelicula->precio_alq)
                ) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?= LinkPager::widget(['pagination' => $dataProvider->pagination]) ?>

<?php foreach ($dataProvider->getModels() as $pelicula): ?>
    <?= DetailView::widget([
        'model' => $pelicula,
        'attributes' => [
            'codigo',
            'titulo',
            [
                'label' => 'Precio de alquiler',
                'value' => $pelicula->precio_alq,
                'format' => 'currency',
                'contentOptions' => [
                    'class' => ($pelicula->precio_alq < 0) ? 'text-danger' : '',
                    'style' => 'font-weight: bold',
                ],
            ],
        ],
    ]) ?>
<?php endforeach ?>
