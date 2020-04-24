<?php
/**
 * @var array $products
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Products
                <div class="tools dropdown">
                    <a class="icon mdi mdi-plus-square" href="/products/create"></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:20%;">Group</th>
                            <th style="width:20%;">Name</th>
                            <th style="width:20%;">Price</th>
                            <th style="width:20%;">Duration</th>
                            <th style="width:10%;">Status</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="cell-detail">
                                    <?=$product['groupName']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$product['name']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$product['price']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$product['duration']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$product['isActive']?>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-hspace">
                                        <a class="btn btn-secondary" href="/products/<?=$product['id']?>">Edit <span class="icon-dropdown mdi mdi-edit"></span></a>
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
