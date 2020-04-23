<?php
/**
 * @var array $users
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card card-table">
            <div class="card-header">Users</div>
            <div class="card-body">
                <div class="table-responsive noSwipe">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th style="width:40%;">User</th>
                            <th style="width:32%;">Activity</th>
                            <th style="width:10%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="user-avatar cell-detail user-info">
                                <img src="<?='http://' . str_replace('admin.', '', $_SERVER['HTTP_HOST']) . $user['clientPath']?>" alt="Avatar">
                                <span><?=$user['name']?>, <?=$user['age']?></span>
                                <span class="cell-detail-description"><?=$user['city']?></span>
                            </td>
                            <td class="cell-detail">
                                <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($user['sex'], $user['isConnected'], $user['lastConnected'])?>
                            </td>
                            <td class="text-right">
                                <div class="btn-group btn-hspace">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">More <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" target="_blank" href="<?='http://' . str_replace('admin.', '', $_SERVER['HTTP_HOST']) . '/user/' . $user['id']?>">Open profile</a>
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
