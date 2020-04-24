<?php
/**
 * @var array $counters
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Counter
                <div class="tools dropdown">
                    <a class="icon mdi mdi-plus-square" href="/counters/create"></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:20%;">Name</th>
                            <th style="width:20%;">Status</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($counters as $counter): ?>
                            <tr>
                                <td class="cell-detail">
                                    <?=$counter['name']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$counter['isActive']?>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-hspace">
                                        <div class="btn-group btn-hspace">
                                            <a class="btn btn-secondary" href="/counters/<?=$counter['id']?>">Edit <span class="icon-dropdown mdi mdi-edit"></span></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
