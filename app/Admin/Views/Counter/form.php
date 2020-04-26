<?php
/**
 * ALWAYS
 * @var array $actions
 * @var string $title
 * @var string $action
 * SPECIAL
 * @var array $counter
 * @var array $counterActionsId
 */

if (!isset($counter)) {
    $counter = [];
}
?>
<link rel="stylesheet" type="text/css" href="/assets/lib/summernote/summernote-bs4.css"/>

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
                        <label for="inputName">About</label>
                        <textarea id="about" name="about" style="display: none"><?=$counter['about']?></textarea>
                        <div class="col-12 row">
                            <div id="summernote"><?=$counter['about']?></div>
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
<script src="/assets/lib/summernote/summernote-bs4.min.js" type="text/javascript"></script>
<script src="/assets/lib/summernote/summernote-ext-beagle.js" type="text/javascript"></script>
<script src="/assets/js/app-form-wysiwyg.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        App.formElements();
        const $about = $('#about');

        $('#summernote').summernote({
            height: 300,
            callbacks: {
                onChange: function(contents, $editable) {
                    $about.html(contents);
                }
            }
        });
    });
</script>