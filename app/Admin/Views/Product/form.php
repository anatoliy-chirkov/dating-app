<?php
/**
 * ALWAYS
 * @var array $actions
 * @var array $groups
 * @var array $commands
 * @var string $formTitle
 * @var string $formAction
 * SPECIAL
 * @var array $product
 * @var array $productActionsId
 * @var array $productCommandsId
 */

if (!isset($product)) {
    $product = [];
}
if (!isset($productActionsId)) {
    $productActionsId = [];
}
if (!isset($productCommandsId)) {
    $productCommandsId = [];
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-border-color card-border-color-primary">
            <div class="card-header card-header-divider"><?=$formTitle?></div>
            <div class="card-body">
                <form method="POST" action="<?=$formAction?>">
                    <input name="id" hidden value="<?=$product['id']?>">
                    <div class="form-group">
                        <label for="inputGroup">Group</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="form-control" name="groupId" id="inputGroup">
                                <option> </option>
                                <?php foreach ($groups as $group): ?>
                                    <option
                                        value="<?=$group['id']?>"
                                        <?=$product['groupId'] === $group['id'] ? 'selected' : ''?>
                                    >
                                        <?=$group['name']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputName">Name</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <input class="form-control" id="inputName" type="text" name="name" value="<?=$product['name']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPrice">Price</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="price" id="inputPrice" value="<?=$product['price']?>">
                                <div class="input-group-append"><span class="input-group-text">roubles</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputDuration">Duration</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="duration" id="inputDuration" value="<?=$product['duration']?>">
                                <div class="input-group-append"><span class="input-group-text">hours</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAction">Actions</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="select2" multiple="" name="actionId[]" id="inputAction">
                                <option> </option>
                                <?php foreach ($actions as $action): ?>
                                    <option
                                        value="<?=$action['id']?>"
                                        <?=in_array($action['id'], $productActionsId) ? 'selected' : ''?>
                                    >
                                        <?=$action['name']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCommand">Commands</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="select2" multiple="" name="commandId[]" id="inputCommand">
                                <option> </option>
                                <?php foreach ($commands as $command): ?>
                                    <option
                                        value="<?=$command['id']?>"
                                        <?=in_array($command['id'], $productCommandsId) ? 'selected' : ''?>
                                    >
                                        <?=$command['name']?>
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
                                    id="checkFreeProduct"
                                    name="isFree"
                                    <?=$product['isFree'] ? 'checked' : ''?>
                                >
                                <label class="custom-control-label" for="checkFreeProduct">Free product</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-sm-8 col-lg-6">
                            <div class="custom-control custom-checkbox">
                                <input
                                    class="custom-control-input"
                                    type="checkbox"
                                    id="checkIsActive"
                                    name="isActive"
                                    <?=$product['isActive'] ? 'checked' : ''?>
                                >
                                <label class="custom-control-label" for="checkIsActive">Turn on after save</label>
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
