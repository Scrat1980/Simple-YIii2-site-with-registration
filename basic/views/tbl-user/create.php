<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblUser */

$this->title = 'Регистрация';
// $this->params['breadcrumbs'][] = ['label' => 'Tbl Users', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        if($error = Yii::$app->session->getFlash('error'))
            echo '<div class="alert alert-danger">' . $error . '</div>';
    ?>

    <?= $this->render('_form', [
        'model' => $model,
        'formType' => 'create'
    ]) ?>

 </div>
