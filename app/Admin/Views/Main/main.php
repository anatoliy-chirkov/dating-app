<?php
/**
 * @var int $usersCount
 */
?>
<div class="row">
    <div class="col-12 col-lg-6 col-xl-3">
        <div class="widget widget-tile">
            <div class="chart sparkline" id="spark1"><canvas width="85" height="35" style="display: inline-block; width: 85px; height: 35px; vertical-align: top;"></canvas></div>
            <div class="data-info">
                <div class="desc">Users</div>
                <div class="value"><span class="indicator indicator-equal mdi mdi-chevron-right"></span><span class="number" data-toggle="counter" data-end="<?=$usersCount?>"><?=$usersCount?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/lib/jquery-flot/jquery.flot.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-flot/jquery.flot.pie.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-flot/jquery.flot.time.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-flot/jquery.flot.resize.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-flot/plugins/jquery.flot.orderBars.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-flot/plugins/curvedLines.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-flot/plugins/jquery.flot.tooltip.js" type="text/javascript"></script>
<script src="/assets/lib/jquery.sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/assets/lib/countup/countUp.min.js" type="text/javascript"></script>
<script src="/assets/lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="/assets/lib/jqvmap/jquery.vmap.min.js" type="text/javascript"></script>
<script src="/assets/lib/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="/assets/js/app-dashboard.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        App.dashboard();
    });
</script>
