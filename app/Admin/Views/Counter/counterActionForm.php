<?php
/**
 * ALWAYS
 * @var array $actions
 * @var string $formTitle
 * @var string $formAction
 * SPECIAL
 * @var array $counterAction
 */

if (!isset($counterAction)) {
    $counterAction = [];
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-border-color card-border-color-primary">
            <div class="card-header card-header-divider"><?=$formTitle?></div>
            <div class="card-body">
                <form method="POST" action="<?=$formAction?>">

                    <div class="form-group">
                        <label for="inputType">Type</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="form-control" name="type" id="inputType">
                                <option> </option>
                                <?php foreach (['increase', 'reduce'] as $type): ?>
                                    <option
                                        <?=$counterAction['type'] === $type ? 'selected' : ''?>
                                    >
                                        <?=$type?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAction">Action</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <select class="form-control" name="actionId" id="inputAction">
                                <option> </option>
                                <?php foreach ($actions as $action): ?>
                                    <option
                                        value="<?=$action['id']?>"
                                        <?=$counterAction['actionId'] === $action['id'] ? 'selected' : ''?>
                                    >
                                        <?=$action['name']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputMultiplier">Multiplier</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <input class="form-control" id="inputMultiplier" type="text" name="multiplier" value="<?=$counterAction['multiplier']?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputCounterLimit">Counter Limit</label>
                        <div class="col-12 col-sm-8 col-lg-6 row">
                            <input class="form-control" id="inputCounterLimit" type="text" name="counterLimit" value="<?=$counterAction['counterLimit']?>">
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
