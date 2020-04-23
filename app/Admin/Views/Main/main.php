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
<script type="text/javascript">
    $(document).ready(function(){
        App.dashboard();
    });
</script>
