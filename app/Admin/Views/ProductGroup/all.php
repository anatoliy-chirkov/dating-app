<?php
/**
 * @var array $groups
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Product Groups
                <div class="tools dropdown">
                    <a class="icon mdi mdi-plus-square" href="/product-groups/create"></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:20%;">Name</th>
                            <th style="width:10%;">Status</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($groups as $group): ?>
                            <tr>
                                <td class="cell-detail">
                                    <?=$group['name']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$group['isActive']?>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-hspace">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Open <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="/product-groups/<?=$group['id']?>">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="/products/<?=$group['id']?>">See products</a>
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
