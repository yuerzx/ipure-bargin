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

if (isset($_GET['et']) && !empty($_GET['et'])) {
    $events_id = intval($_GET['et']);
    $info = $userClass->get_bargin_events_product_by_eventid($events_id);
    if (empty($info)) {
        $info = $userClass->get_bargin_events_product_by_eventid(10);
        $friends_support = $userClass->get_friends_support_by_eventid(10);
    } else {
        //if the id is real and we can get data from database
        $friends_support = $userClass->get_friends_support_by_eventid($events_id);
    }
} else {
    // there is a new page, we open the default page for them
    $info = $userClass->get_bargin_events_product_by_eventid(10);
    $friends_support = $userClass->get_friends_support_by_eventid(10);
}

$ref_rankings = $userClass->get_most_discount_ranking();

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
                    <td>原料产地</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= $info['p_amount']; ?>澳币</td>
                    <td style="color: red;"><?php $last_price = end($friends_support)['current_price'];
                        echo $last_price; ?>澳币
                    </td>
                    <td><img src="img/system/flags/australia.png" style="max-height: 1.3em;padding-right: 5px;">澳洲</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-12">
            <div class="alert alert-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5>为了防止找不到回家的路，一定记得点击右上角收藏我哦~</h5></div>
        </div>
    </div>
    <div class="bd">
        <h4>亲爱的</h4>

        <p>已经有<?php echo count($friends_support); ?>位亲友帮助 <span><img src="<?= $info['headimgurl'] ?>"
                                                                     style="max-height: 4em;"></span>
            <span style="color: red;"><?= $info['nickname'] ?></span>砍价了。
            现价为<span style="color: red; ">
                <?php $last_price = end($friends_support)['current_price'];
                if (empty($last_price)) {
                    echo $info['p_amount'];
                }else{
                    echo $last_price;
                }; ?>
                </span>
            澳币(折合人民币<?= round($last_price * 4.8, 2); ?>)，快快帮你的朋友补一刀吧！</p>
    </div>
    <div class="row" ng-controller="join">
        <div class="col-xs-6">
            <a href="javascript:;" ng-click="helpBargin()" ng-disable="barginButtonDisable"
               class="weui_btn weui_btn_primary">
                {{barginText}}
            </a>
        </div>
        <div class="col-xs-6">
            <a ng-click="newBargin()" class="weui_btn weui_btn_default">发起砍价</a>
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
                foreach ($friends_support as $support) {
                    ?>
                    <tr>
                        <td><img src="<?= $support['headimgurl'] ?>"
                                 style="max-height: 2em;padding-right: 6px"
                                 class="img-circle"
                            ><?= $support['nickname'] ?></td>
                        <td><?= $support['amount'] ?></td>
                        <td><?= $support['current_price'] ?></td>
                        <td><?= round($support['current_price'] * 4.8, 2) ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
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
            $tpl_loc = 'views/' . $info['p_details'];
            include $tpl_loc;
            ?>
        </div>
    </div>
    <div class="row" id="joinEvents">
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>参与活动</btitle>
                </div>
            </section>
        </div>
        <div class="col-xs-12">
            <table class="table join_event">
                <thead>
                    <tr>
                        <td colspan="2" class="text-center"><h5>活动参与</h5></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td style="width: 50%;"><strong>协助朋友</strong><br><img src="img/system/help.jpg" class="img-rounded img-responsive"></td>
                        <td><strong>团队协助</strong><br><img src="img/system/team.jpg" class="img-rounded img-responsive"></td>
                    </tr>
                <tr>
                  <td>帮助朋友砍价，让朋友能拿到最低价的中国红，人人为我，我为人人。朋友之间可以相互砍价哦~</td>
                    <td>分享自己的砍价，让朋友们都参与进来。 通过你的分享而发起砍价的就算积分哦~积分最高的前3名，免费送IPure的UGG和围巾一条，前10名赠送围巾一条</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row" id="CompanyIntro">
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>公司简介</btitle>
                </div>
            </section>
        </div>
        <div class="col-xs-12" style="margin-top: 20px; ">
            <p>
                澳洲IPure公司简介：<br>
                起源地：澳大利亚<br>
                品牌生产地： 澳大利亚<br>
                设计理念： 平易近人的高端羊毛制品<br>
                羊毛围巾产品：<br>
                IPURE纯羊毛(100%)制造围巾，采用澳洲当地纯羊毛为原材料，进行加工制作，<br>
                产品设计前卫，时尚，并有多种颜色可供选择，保暖功能一流。
            </p>
        </div>

    </div>
    <div class="row" id="Payment" ng-controller="payment">
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>支付说明</btitle>
                </div>
            </section>
        </div>
        <div class="col-xs-12">
            <table class="table">
                <tr>
                    <td><img src="img/system/cny.png" style="max-height: 1.3em;"> 人民币</td>
                    <td>微信支付 请添加<span ng-click = "goTo('service')" style="color: blue; text-decoration:underline;">客服&darr;</span></td>
                </tr>
                <tr>
                    <td><img src="img/system/aud.png" style="max-height: 1.3em;"> 澳币支付</td>
                    <td>Bank of Commonwealth</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>运费说明</btitle>
                </div>
            </section>
        </div>
        <div class="col-xs-12">
            <table class="table">
                <tr class="text-center">
                    <td colspan="3">全部的发货地点为澳洲墨尔本，没有国内的转发，敬请留意.预计2月15日开始陆续出货，具体会随时更新</td>
                </tr>
                <tr>
                    <td><img src="img/system/flags/australia.png" style="max-height: 1.3em;padding-right: 5px;">澳洲境内
                    </td>
                    <td>平邮： 3澳币</td>
                    <td>3-4 天到达</td>
                </tr>
                <tr>
                    <td><img src="img/system/flags/australia.png" style="max-height: 1.3em;padding-right: 5px;">澳洲境内
                    </td>
                    <td>上门提货</td>
                    <td>Boxhill VIC 3128</td>
                </tr>
                <tr>
                    <td><img src="img/system/flags/china.png" style="max-height: 1.3em;padding-right: 5px;">中国境内</td>
                    <td>9澳币/43.2人民币</td>
                    <td>2~3 周到达</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row" id="purchaseNow" ng-controller="purchaseNow" >
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>立即购买</btitle>
                </div>
            </section>
        </div>
        <div class="col-xs-12">
            <div>

                    点击购买成功后，页面将会被锁死，不能继续进行砍价。 请与确认后三天内支付。

            </div>
            <div class="weui_btn weui_btn_primary" ng-click ="goTo('#service')">立即购买</div>
        </div>
    </div>
    <div class="row" id="refRank" ng-controller="refRank">
        <div class="col-xs-12">
            <section class="more_box_title">
                <div class="more_box_title_main">
                    <btitle>推广战报</btitle>
                </div>
            </section>
        </div>
        <div class="col-xs-12" style="padding-top: 20px;" >
            <table class="table clearfix qyt_box_table">
                <thead>
                <tr>
                    <td colspan="4" class="text-center">推广战报 全部赠品免费，但是不包邮
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center">
                        总参与人数： <?= $userClass->get_total_particpate(); ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">发起人</td>
                    <td>推广人数</td>
                    <td>排名</td>
                    <td>奖品</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                foreach ($ref_rankings as $ref_ranking) {
                    ?>
                    <tr>
                        <td>
                            <img src="<?= $ref_ranking['headimgurl'] ?>"
                                 style="max-height: 2em;padding-right: 6px"
                                 class="img-circle"
                            ><a ng-click="reloadPage('<?= $ref_ranking['events_id'] ?>')">
                                <?= $ref_ranking['nickname'] ?>
                            </a>
                        </td>
                        <td><?= $ref_ranking['count'] ?></td>
                        <td><?= $count ?></td>
                        <td><?php
                            if($count == 1){
                              echo "<strong color='red'>iPhone 6S 玫瑰金</strong>";
                            }elseif($count >1 && $count <= 7){
                                echo "免费UGG一双";
                            }elseif($count > 7 && $count <= 15){
                                echo "免费围巾一条";
                            }
                            ?></td>
                    </tr>

                    <?php
                    $count ++;
                }
                ?>
                </tbody>
            </table>
            <div>
                为防止刷单，分数计算的方式为：参与人数 + 成交人数*150
                    <br>有100人点击了你的页面，参与了砍价，就算100分
                    <br>有10个人通过你的页面发起了砍价，在最后有4个人成功付款，就算600分
                    <br>分数最高者获得iPhone 6s 玫瑰金16GB一只

                <img src="img/system/iphone6s.png" class="text-center img-rounded img-responsive">
            </div>
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
        <div class="col-xs-12">
            <ol style="padding-left: 5%;">
                <li>活动时间：到2016年2月8日截止</li>
                <li>活动限额：一人一种颜色限购一条</li>
                <li>分享活动的前三名也会有大礼相送</li>
                <li>可砍到最低价购买，砍价过程中也可随时联系商家购买</li>
                <li>产品限量200件，先下单付款者先得，售完即止</li>
                <li>IPure 诚招线下经销商，详情联系客服</li>
                <li>找人帮砍可分享到朋友圈或发送给好友</li>
                <li>最终解释权属于iPure UGG Australian</li>
                <li>砍价活动持续更新，更多精彩活动尽情关注</li>
                <li id="service">
                    客户微信号：
                    <img src="img/system/contact.jpg" style="max-width: 90%; margin: auto;">
                </li>
            </ol>
        </div>
    </div>
    <div class="row" style="padding-top: 20px">

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
        .countdown("2016/02/08", function (event) {
            $(this).text(
                event.strftime('%D天%H时%M分%S秒')
            );
        });
</script>

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>


<script>
    'use strict';
    var app = angular.module('bargin', ['ngCookies', 'ngRoute']);

    app.config(['$locationProvider', function ($locationProvider) {
        $locationProvider.html5Mode({enabled: true, requireBase: false}).hashPrefix('!');
    }]);

    app.controller('join', function ($scope, $http, $cookies, $location, $window, $anchorScroll) {
        //Here to set up some deafult value for the app
        $scope.barginText = "帮助砍价";
        $scope.barginButtonDisable = "false";
        angular.element(document).ready(function () {
            loginNotice($cookies);
        });

        $scope.helpBargin = function () {
            $scope.barginText = "载入中~";
            $scope.barginButtonDisable = "true";
            var result = $cookies.get("openid");
            if (result) {
                console.log("logined");
                var req = {
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
                $http(req).then(function (response) {
                    console.log("This is result: " + response.data.result);

                    if (response.data.result === 'succ') {
                        swal({
                                title: "Good job!",
                                text: "刚刚帮你朋友 <?= $info['nickname'] ?> 砍掉了"
                                + response.data.amount + "澳币",
                                type: "success",
                                closeOnConfirm: true,
                            }, function () {
                                $window.location.reload();
                            }
                        )
                    } else if (response.data.result === 'no-existed') {
                        $cookies.remove('openid');
                        $window.location.reload();
                    } else {
                        swal({
                            title: "哦no~~",
                            text: "你已经帮你朋友砍过价了，真的很喜欢的话，发起自己的砍价吧！",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "发起砍价",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        }, function (isConfirm) {
                            if (isConfirm) {
                                startNewBargin($http, $location, $cookies, $window);
                            }
                        });
                    }
                    console.log(response);
                }).then(function () {
                        //Some clean up
                        $scope.barginText = "发起砍价";
                        $scope.barginButtonDisable = "false";
                    }
                );

            } else {
                // now is the testing app
                var urlBase = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
                        //+ "wxae45c193de06d5a4"
                    + "wx2d39a6c422ad663c"
                    + "&redirect_uri="
                        //+ "http%3A%2F%2F127.0.0.1%2FAusway%2Fapp%2Fipure-bargin%2Fsrc%2Flanding-guide.php"
                    + "https%3A%2F%2Foneu.me%2Fapp%2Fipure-bargin%2Flanding-guide.php"
                    + "&response_type=code&scope=snsapi_userinfo&state=<?= $events_id ?>#wechat_redirect";
                $window.location.href = urlBase;
            }
        }

        $scope.newBargin = function () {
            var result = $cookies.get("openid");
            if (result) {
                console.log("logined");
                // now we are getting all the result
                startNewBargin($http, $location, $cookies, $window);
            } else {
                // now is the testing app
                var urlBase = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
                        //+ "wxae45c193de06d5a4"
                    + "wx2d39a6c422ad663c"
                    + "&redirect_uri="
                        //+ "http%3A%2F%2F127.0.0.1%2FAusway%2Fapp%2Fipure-bargin%2Fsrc%2Flanding-guide.php"
                    + "https%3A%2F%2Foneu.me%2Fapp%2Fipure-bargin%2Flanding-guide.php"
                    + "&response_type=code&scope=snsapi_userinfo&state=<?= $events_id ?>#wechat_redirect";
                $window.location.href = urlBase;
            }
        }
    });

    app.controller('payment', function($scope, $location, $anchorScroll){
        $scope.goTo = function(hash){
            goTo(hash, $location, $anchorScroll);
        }
    })

    app.controller('refRank', function($scope, $window, $location){
        $scope.reloadPage = function(nu){
            $location.search('et', nu);
            setTimeout(function(){
                $window.location.reload();
            }, 700)
        }
    })

    app.controller('purchseNow', function($scope, $location, $anchorScroll){
        $scope.goTo = function(data){
            goTo(data, $location, $anchorScroll);
        }
    })

    function startNewBargin($http, $location, $cookies, $window) {
        var req1 = {
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

        $http(req1).then(function (response) {
            console.log(response.data.et);
            if (response.data.result == 'succ') {
                $location.search('et', response.data.et);
                swal({
                    title: "Good job!",
                    text: "你已经成功的创建了自己的砍价，点击右上角分享给朋友开始砍价吧~~ 确认后更新显示页面",
                    type: "success",
                    closeOnConfirm: true,
                }, function () {
                    $window.location.reload();
                })
            } else {
                $location.search('et', response.data.et);
                swal({
                    title: ":)",
                    text: "您已经创建过相应产品的砍价了，要去看看么？",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "返回",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "去看看",
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $window.location.reload();
                        console.log("Lets go");
                    }
                });
            }
        });
    }

    function loginNotice($cookies) {
        if ($cookies.get("new_login") == 1) {
            swal({
                title: "登录成功",
                text: "开始疯狂砍价吧~~ 2s后关闭",
                timer: 2000,
                showConfirmButton: false
            });
            $cookies.put("new_login", 2);
        }
    }

    function goTo(hash, $location, $anchorScroll){
        $location.hash(hash);
        $anchorScroll();
    }

</script>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-63651839-1', 'auto');
    ga('send', 'pageview');
</script>
</body>
<footer>
    <div class="row">

        <div class="col-xs-12 text-center">
            <hr>
            <div style="padding-bottom: 20px;"><img src="https://oneu.me/wp-content/uploads/2015/11/favicon.png" style="height: 25px;">技术支持: <a href="https://oneu.me">万友科技</a></div>
        </div>
    </div>
</footer>
</html>
