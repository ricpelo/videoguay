<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SociosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Socios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socios-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Socios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'numero',
            'nombre',
            'direccion',
            'telefono',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
