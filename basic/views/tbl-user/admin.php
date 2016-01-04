<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зарегистрированные пользователи:';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-user-index">

    <h1><?php echo Html::encode($this->title); ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
        if($error = Yii::$app->session->getFlash('error'))
            echo '<div class="alert alert-danger">' . $error . '</div>';
    ?>

    <p>
        <?php /*echo Html::a('Create Tbl User', ['create'], ['class' => 'btn btn-success']);*/ ?>
    </p>

    <?= GridView::widget([
        // 'afterRow' => function(){echo 'ok';},
        // 'caption' => 'caption',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            // 'password',
            'name',
            'phone',
            'activation_status',
            // 'email:email',
            // 'role.title',
            // 'authKey',

            [
                'attribute'=>'role_id',
                'value' => 'role.title',
                'filter'=> [
                    1 =>"Администратор",
                    2 =>"Пользователь"
                ],
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
