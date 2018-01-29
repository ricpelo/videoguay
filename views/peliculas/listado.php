<?php
use app\models\Peliculas;
use yii\data\Sort;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var $this \yii\web\View */
/** @var $peliculas Peliculas[] */
/** @var $pagination Pagination */
/** @var $sort Sort */
?>

<table class="table table-striped">
    <thead>
        <th><?= $sort->link('codigo') ?></th>
        <th><?= $sort->link('titulo') ?></th>
        <th><?= $sort->link('precio_alq') ?></th>
    </thead>
    <tbody>
        <?php foreach ($peliculas as $pelicula): ?>
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

<?= LinkPager::widget(['pagination' => $pagination]) ?>
