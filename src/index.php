<?php

/*******************
**
 *
 *
 * @prame $friends support Array
**/
require_once 'functions.php';
$userClass = new Game_Class();
$info = array();
$friends_support = array();
$events_id = 1;

if(isset($_GET['et']) && !empty($_GET['et'])){
    $events_id = intval($_GET['et']);
    $info = $userClass->get_bargin_events_product_by_eventid($events_id);
    if(empty($info)){
        $info = $userClass->get_bargin_events_product_by_eventid(1);
        $friends_support = $userClass->get_friends_support_by_eventid(1);
    }else{
        //if the id is real and we can get data from database
        $friends_support = $userClass->get_friends_support_by_eventid($events_id);
    }
}else{
    // there is a new page, we open the default page for them
    $info = $userClass->get_bargin_events_product_by_eventid(1);
    $friends_support = $userClass->get_friends_support_by_eventid(1);
}



?>


<!doctype html>
<html class="no-js" lang="CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>回家过年，怎能少了澳洲的iPure羊毛围巾，新春特价~~惊喜连连</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/main.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/gsdk.css">
    <link rel="stylesheet" href="css/weui.css">
    <link rel="stylesheet" href="css/sweetalert.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

</head>
<body>
<div class="page container" ng-app="bargin" id="main">
    <div class="hd">
        <h1 class="page_title"><?= $info['p_name'] ?></h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- This is the section for imgs display-->
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="img/scarfs/<?= $info['p_color'] ?>-1.jpg" alt="...">
                    </div>
                    <div class="item">
                        <img src="img/scarfs/<?= $info['p_color'] ?>-2.jpg" alt="...">
                    </div>
                    <div class="item">
                        <img src="img/scarfs/<?= $info['p_color'] ?>-3.jpg" alt="...">
                    </div>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="hd">
        <div class="weui_cells">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>活动倒计时</p>
                </div>
                <div class="weui_cell_ft" id="count-down"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>产品原价</td>
                    <td>现价</td>
                    <td>底价</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $info['p_amount']; ?>澳币</td>
                    <td style="color: red;"><?php $last_price = end($friends_support)['current_price']; echo $last_price; ?>澳币</td>
                    <td><?= $info['p_amount']-16; ?>澳币</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="bd">
        <h4>亲爱的 <?= $info['nickname'] ?></h4>
        <p>已经有<?php echo count($friends_support); ?>位亲友帮助 <span><img src="<?= $info['headimgurl'] ?>" style="max-height: 4em;"></span>
            <span style="color: red;"><?= $info['nickname'] ?></span>砍价了。原价<?= $info['p_amount']; ?>
            现价为<span style="color: red; ">
                <?php $last_price = end($friends_support)['current_price']; echo $last_price; ?>
                </span>
            澳币(折合人民币<?= round($last_price*4.8, 2); ?>)，快快帮你的朋友补一刀吧！</p>
    </div>
    <div class="row" ng-controller="join">
        <div class="col-xs-6">
            <a href="javascript:;" ng-click="helpBargin()" ng-disable = "barginButtonDisable" class="weui_btn weui_btn_primary">
                {{barginText}}
            </a>
        </div>
        <div class="col-xs-6">
            <a ng-click="scrollTo('rules')" class="weui_btn weui_btn_default">活动规则</a>
        </div>

    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-xs-12">
            <table class="table clearfix qyt_box_table">
                <thead>
                <tr>
                    <td colspan="4" class="text-center">友团战报</td>
                </tr>
                <tr>
                    <td class="text-center">亲友</td>
                    <td>砍掉价格</td>
                    <td>价格(澳币)</td>
                    <td>价格(RMB)</td>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($friends_support as $support){
                        ?>
                        <tr>
                            <td><img src="<?= $support['headimgurl'] ?>"
                                     style="max-height: 2em;padding-right: 6px"
                                     class="img-circle"
                                ><?= $support['nickname'] ?></td>
                            <td><?= $support['amount'] ?></td>
                            <td><?= $support['current_price'] ?></td>
                            <td><?= round($support['current_price']*4.8,2) ?></td>
                        </tr>
                    <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
                <section class="more_box_title">
                    <div class="more_box_title_main">
                        <btitle>商品详情</btitle>
                    </div>
                </section>
        </div>
        <div class="col-xs-12" style="padding-top: 20px">
            <?php
            //import the temple files
            $tpl_loc = 'views/'.$info['p_details'];
            include $tpl_loc;
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sx-12">

        </div>
    </div>
    <div class="row" id="rules">
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>活动说明</btitle>
                </div>
            </section>
        </div>
    </div>
    <div class="row" style="padding-top: 20px">
        <div class="col-xs-12">
            <ol style="padding-left: 5%;">
                <li>活动时间：</li>
                <li>活动限额：一人一种颜色限购一条</li>
                <li>请在有效期内联系商家购买哦,砍价活动2月6日截止</li>
                <li>可砍到最低价购买，砍价过程中也可随时联系商家购买</li>
                <li>产品限量200件，先下单付款者先得，售完即止</li>
                <li>IPure 诚招线下经销商，详情联系客服</li>
                <li>找人帮砍可分享到朋友圈或发送给好友</li>
                <li>最终解释权属于iPure UGG Australian</li>
                <li>砍价活动持续更新，更多精彩活动尽情关注</li>
                <li>
                    客户微信号：
                    <img src="img/system/contact.jpg" style="max-width: 90%; margin: auto;">
                </li>
            </ol>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-2.2.0.min.js"><\/script>')</script>
<script src="js/plugins.js"></script>
<script src="js/main.js"></script>
<script src="js/jquery.countdown.min.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/angular-cookies.min.js"></script>
<script src="js/sweetalert.min.js"></script>

<script type="text/javascript">
    $("#count-down")
        .countdown("2016/02/06", function (event) {
            $(this).text(
                event.strftime('%D天%H时%M分%S秒')
            );
        });
</script>

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>


<script>
    'use strict';
    var app = angular.module('bargin', ['ngCookies','ngRoute']);

    app.config(['$locationProvider', function($locationProvider){
        $locationProvider.html5Mode({enabled: true, requireBase: false}).hashPrefix('!');
    }]);

    app.controller('join', function($scope, $http, $cookies, $location, $window, $anchorScroll, $route) {
        //Here to set up some deafult value for the app
        $scope.barginText = "帮助砍价";
        $scope.barginButtonDisable = "false";

        $scope.helpBargin = function(){
            console.log("Button Press");
            $scope.barginText = "载入中~";
            $scope.barginButtonDisable = "true";
            let result = $cookies.get("openid");
            if(result){
                console.log("logined");
                let req = {
                    method: 'POST',
                    url: 'ajax/bargin-help-check.php',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    data: {
                        events: <?= $events_id ?>,
                        openid: $cookies.get("openid"),
                        work_type: 'help'
                    }
                }
                // now we are getting all the result
                $http(req).then(function(response){
                    console.log("This is result: " + response.data.result);

                    if(response.data.result === 'succ'){
                        swal("Good job!", "刚刚帮你朋友 <?= $info['nickname'] ?> 砍掉了"
                            + response.data.amount + "澳币",
                            "success")
                        $window.location.reload();

                    }else if(response.data.result === 'no-existed'){
                        $cookies.remove('openid');
                        $window.location.reload();
                    }else{
                        swal({
                            title: "哦no~~",
                            text : "你已经帮你朋友砍过价了，真的很喜欢的话，发起自己的砍价吧！",
                            type : "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "发起砍价",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        }, (isConfirm)=>{
                            if(isConfirm){
                            startNewBargin($http, $location, $cookies, $window);
                            }
                        });
                    }
                    console.log(response);
                }).then(function(){
                    //Some clean up
                    $scope.barginText = "发起砍价";
                    $scope.barginButtonDisable = "false";
                }
                );

            }else{
                // now is the testing app
                let urlBase = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxae45c193de06d5a4&redirect_uri="
                    + "http%3A%2F%2F127.0.0.1%2FAusway%2Fapp%2Fipure-bargin%2Fsrc%2Flanding-guide.php"
                    + "&response_type=code&scope=snsapi_userinfo&state=<?= $events_id ?>#wechat_redirect";
                $window.location.href = urlBase;
            }
        }

        $scope.scrollTo = (id)=>{
            $location.hash(id);
            console.log($location.hash());
            $anchorScroll();
        }
    });

    function startNewBargin($http, $location, $cookies, $window){
        let req = {
            method: 'POST',
            url: 'ajax/bargin-help-check.php',
            headers: {
                'Content-Type': 'application/json'
            },
            data: {
                events: <?= $events_id ?>,
                openid: $cookies.get("openid"),
                work_type: 'create'
            }
        }

        $http(req).then(function(response){
            console.log(response.data.et);
            if(response.data.result == 'succ'){
                $location.search('et', response.data.et);
                swal({
                    title: "Good job!",
                    text : "你已经成功的创建了自己的砍价，点击右上角分享给朋友开始砍价吧~~ 确认后更新显示页面",
                    type : "success",
                    closeOnConfirm: true,
                },function(){
                    $window.location.reload();
                })
            }else{
                $location.search('et', response.data.et);
                swal({
                    title: ":)",
                    text : "您已经创建过相应产品的砍价了，要去看看么？",
                    type : "warning",
                    showCancelButton: true,
                    cancelButtonText: "返回",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "去看看",
                    closeOnConfirm: true
                }, function(isConfirm){
                    if(isConfirm){
                        $window.location.reload();
                        console.log("Lets go");
                    }
                });
            }
        });
    }

</script>
</body>
</html>
