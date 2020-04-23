<?php
/**
 * @var array $pushers
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Pushers
                <div class="tools dropdown">
                    <a class="icon mdi mdi-plus-square" href="/products/create-pusher"></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:20%;">Name</th>
                            <th style="width:20%;">CommandId</th>
                            <th style="width:20%;">Price</th>
                            <th style="width:20%;">isActive</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pushers as $pusher): ?>
                            <tr>
                                <td class="cell-detail">
                                    <?=$pusher['name']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$pusher['pusherCommandId']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$pusher['price']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$pusher['isActive']?>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-hspace">
                                        <div class="btn-group btn-hspace">
                                            <a class="btn btn-secondary" href="/products/pusher/<?=$pusher['id']?>">Edit <span class="icon-dropdown mdi mdi-edit"></span></a>
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
