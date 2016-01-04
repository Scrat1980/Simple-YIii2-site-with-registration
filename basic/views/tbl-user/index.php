<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\Application;

AppAsset::register($this);

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Главная страница сайта</h1>
        <h1>
            <?php
                if(isset(Yii::$app->user->identity->name)){
                    echo "Привет, " . Yii::$app->user->identity->name . "!";
 
                }
            ?>
        </h1>
    </div>
</div>
