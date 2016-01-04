<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model app\models\TblUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'w0',
        'enableAjaxValidation' => true]
    ); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php 
        if($formType == 'create')
            echo $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint('3 character minimum');
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?php 
        if($formType == 'update')
            echo $form->field($model, 'role_id')->DropDownList(['2' => 'User', '1' => 'Admin']); ?>

    <?php 
        if($formType == 'create')
            echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]); 
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
