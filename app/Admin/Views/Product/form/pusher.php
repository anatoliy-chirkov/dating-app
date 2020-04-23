<?php
/**
 * ALWAYS
 * @var array $pusherCommands
 * @var string $title
 * @var string $action
 * SPECIAL
 * @var array $counter
 */

if (!isset($counter)) {
    $counter = [];
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-border-color card-border-color-primary">
            <div class="card-header card-header-divider"><?=$title?></div>
            <div class="card-body">
                <form method="POST" action="<?=$action?>">
                    <input name="id" hidden value="<?=$counter['id']?>">

                    <div class="form-group">
                        <label for="inputName">Name</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <input class="form-control" id="inputName" type="text" name="name" value="<?=$counter['name']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAccess">Command</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="form-control" name="accessId" id="inputAccess">
                                <?php foreach ($pusherCommands as $pusherCommand): ?>
                                    <option
                                        value="<?=$pusherCommand['id']?>"
                                        <?=$counter['accessId'] === $pusherCommand['id'] ? 'selected' : ''?>
                                    >
                                        <?=$counter['name']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPrice">Price</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="price" id="inputPrice" value="<?=$counter['price']?>">
                                <div class="input-group-append"><span class="input-group-text">roubles</span></div>
                            </div>
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
                                    <?=$counter['isActive'] ? 'checked' : ''?>
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
