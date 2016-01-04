<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblUser */

$this->title = 'Update Tbl User: ' . ' ' . $model->name;
// $this->params['breadcrumbs'][] = ['label' => 'Tbl Users', 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="tbl-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'formType' => 'update',
    ]) ?>

</div>
