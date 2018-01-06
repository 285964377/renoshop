<?php

?>
<div class="topnav">
    <div class="topnav_bd w1210 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>

                <li>您好，欢迎来到京西

                    <?php
                      if(Yii::$app->user->isGuest){
                      //如果没有登录则没有获取到其中的选哟的值 则提示 尚未登录状态
                      //echo "尚未登录";
                       echo   yii\helpers\Html::a('点击登录',['member/login']);
                       echo   \yii\helpers\Html::a('免费注册',['member/add']);
                     }else{
                      //登录之后则输出username 放在导航头
                      echo \Yii::$app->user->identity->username;
                      echo yii\helpers\Html::a('注销',['member/logout']);
                      }
                    ?>


                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>

