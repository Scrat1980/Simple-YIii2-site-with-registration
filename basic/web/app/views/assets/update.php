<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assets */

$this->title = 'Update Assets: ' . ' ' . $model->id_asset;
$this->params['breadcrumbs'][] = ['label' => 'Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_asset, 'url' => ['view', 'id' => $model->id_asset]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="assets-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
