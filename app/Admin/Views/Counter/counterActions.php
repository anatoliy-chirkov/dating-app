<?php
/**
 * @var array $counterActions
 * @var int $counterId
 * @var string $counterName
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Counter Actions for "<?=$counterName?>"
                <div class="tools dropdown">
                    <a class="icon mdi mdi-plus-square" href="/counters/<?=$counterId?>/actions/create"></a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:20%;">Type</th>
                            <th style="width:20%;">Action</th>
                            <th style="width:20%;">Multiplier</th>
                            <th style="width:20%;">Counter Limit</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($counterActions as $counterAction): ?>
                            <tr>
                                <td class="cell-detail">
                                    <?=$counterAction['type']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$counterAction['actionName']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$counterAction['multiplier']?>
                                </td>
                                <td class="cell-detail">
                                    <?=$counterAction['counterLimit']?>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-hspace">
                                        <div class="btn-group btn-hspace">
                                            <a class="btn btn-secondary" href="/counters/<?=$counterId?>/actions/<?=$counterAction['id']?>">Edit <span class="icon-dropdown mdi mdi-edit"></span></a>
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
