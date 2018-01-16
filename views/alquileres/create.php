<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */

$this->title = 'Create Alquileres';
$this->params['breadcrumbs'][] = ['label' => 'Alquileres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alquileres-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
