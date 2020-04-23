<?php
/**
 * ALWAYS
 * @var array $permissions
 * @var array $accesses
 * @var array $groups
 * @var string $title
 * @var string $action
 * SPECIAL
 * @var array $advantage
 * @var array $advantagePermissionsId
 */

if (!isset($advantage)) {
    $advantage = [];
}
if (!isset($advantagePermissionsId)) {
    $advantagePermissionsId = [];
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-border-color card-border-color-primary">
            <div class="card-header card-header-divider"><?=$title?></div>
            <div class="card-body">
                <form method="POST" action="<?=$action?>">
                    <input name="id" hidden value="<?=$advantage['id']?>">
                    <div class="form-group">
                        <label for="inputGroup">Group</label>
                        <p><small>Choose or add new</small></p>
                        <div class="row">
                            <div class="col-6 col-sm-4 col-lg-3">
                                <select class="form-control" name="groupId" id="inputGroup">
                                    <option> </option>
                                    <?php foreach ($groups as $group): ?>
                                        <option
                                            value="<?=$group['id']?>"
                                            <?=$advantage['groupId'] === $group['id'] ? 'selected' : ''?>
                                        >
                                            <?=$group['name']?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 col-sm-4 col-lg-3">
                                <input class="form-control" id="inputGroup" type="text" name="groupName">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputName">Name</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <input class="form-control" id="inputName" type="text" name="name" value="<?=$advantage['name']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPrice">Price</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="price" id="inputPrice" value="<?=$advantage['price']?>">
                                <div class="input-group-append"><span class="input-group-text">roubles</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputDuration">Duration</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="duration" id="inputDuration" value="<?=$advantage['duration']?>">
                                <div class="input-group-append"><span class="input-group-text">hours</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPermission">Permissions</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="select2" multiple="" name="permissionId[]" id="inputPermission">
                                <option> </option>
                                <?php foreach ($permissions as $permission): ?>
                                    <option
                                        value="<?=$permission['id']?>"
                                        <?=in_array($permission['id'], $advantagePermissionsId) ? 'selected' : ''?>
                                    >
                                        <?=$permission['name']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAccess">Access</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="form-control" name="accessId" id="inputAccess">
                                <?php foreach ($accesses as $access): ?>
                                    <option
                                        value="<?=$access['id']?>"
                                        <?=$advantage['accessId'] === $access['id'] ? 'selected' : ''?>
                                    >
                                        <?=$access['name']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="custom-control custom-checkbox">
                                <input
                                    class="custom-control-input"
                                    type="checkbox"
                                    id="check3"
                                    name="isActive"
                                    <?=$advantage['isActive'] ? 'checked' : ''?>
                                >
                                <label class="custom-control-label" for="check3">Turn on after save</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-sm-8 col-lg-6">
                            <button class="btn btn-space btn-lg btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/assets/lib/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="/assets/lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="/assets/lib/bootstrap-slider/bootstrap-slider.min.js" type="text/javascript"></script>
<script src="/assets/lib/bs-custom-file-input/bs-custom-file-input.js" type="text/javascript"></script>
<script src="/assets/js/app-form-elements.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        App.formElements();
    });
</script>
