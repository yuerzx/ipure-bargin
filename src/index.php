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
    <title>iPure Scarf New Year Promote</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/main.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="css/gsdk.css">
    <link rel="stylesheet" href="css/weui.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
<div class="page container">
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
                    <p>Count Down</p>
                </div>
                <div class="weui_cell_ft" id="count-down"></div>
            </div>
        </div>
    </div>
    <div class="bd">
        <h4>Dear <?= $info['nickname'] ?></h4>
        <p>已经有3位亲友帮助 <span><img src="<?= $info['headimgurl'] ?>" style="max-height: 4em;"></span>
            <span style="color: red;"><?= $info['nickname'] ?></span>砍价了。
            当前价格为<?php $last_price = end($friends_support)['current_price']; echo $last_price; ?>
            澳币(折合人民币<?= $last_price*4.8 ?>)，快快帮你的朋友补一刀吧！</p>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <a href="javascript:;" class="weui_btn weui_btn_primary">补上一刀</a>
        </div>
        <div class="col-xs-6">
            <a href="javascript:;" class="weui_btn weui_btn_default">活动规则</a>
        </div>

    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-xs-12">
            <table class="table clearfix qyt_box_table">
                <thead>
                <tr>
                    <td colspan="3" class="text-center">给力亲友团</td>
                </tr>
                <tr>
                    <td>亲友</td>
                    <td>砍掉价格</td>
                    <td>最终价格</td>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($friends_support as $support){
                        ?>
                        <tr>
                            <td><img src="<?= $support['headimgurl'] ?>"
                                     style="max-height: 2.5em;padding-right: 6px"
                                     class="img-circle"
                                ><?= $support['nickname'] ?></td>
                            <td><?= $support['amount'] ?></td>
                            <td><?= $support['current_price'] ?></td>
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
    </div>
    <div class="row">
        <div class="col-sx-12">

        </div>
    </div>
    <div class="row">
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
            <ol>
                <li>活动时间：</li>
                <li>活动限额：一人一种颜色限购一条</li>
                <li>发起砍价限时48小时，请在有效期内联系商家购买哦</li>
                <li>可砍到最低价购买，砍价过程中也可随时联系商家购买</li>
                <li>产品限量200件，先下单付款者先得，售完即止</li>
                <li>找人帮砍可分享到朋友圈或发送给好友</li>
                <li>砍价活动持续更新，更多精彩活动尽情关注</li>
                <li>
                    客户微信号：
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

<script type="text/javascript">
    $("#count-down")
            .countdown("2016/01/31", function (event) {
                $(this).text(
                        event.strftime('%D Days %H:%M:%S')
                );
            });
</script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
                function () {
                    (b[l].q = b[l].q || []).push(arguments)
                });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = 'https://www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-XXXXX-X', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>
