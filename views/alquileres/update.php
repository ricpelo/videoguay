<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */

$this->title = 'Update Alquileres: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Alquileres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="alquileres-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
