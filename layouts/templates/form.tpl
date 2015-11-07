<form class="form-horizontal" role="form" action="<?= $this->action?>" method="<?= $this->method?>" <?= (!empty($this->enctype))? 'enctype="'.$this->enctype.'"': ''?>>
    <?php if(isset($this->errors)):?>
        <?php
        foreach($this->errors as $error_field):
            foreach($error_field as $field=>$error):
                ?>
                <p class="alert alert-danger"><?= 'Field: '.$field.' have error: '.implode(' and ', $error)?></p>
                <?php
            endforeach;
        endforeach;
        ?>
    <?php endif;?>
    <?php foreach($this->fields as $field):?>
    <div class="form-group <?= $this->getInpClass($field['name'])?>">
        <label for="<?= $field['name']?>" class="col-md-3 control-label"><?= $field['title']?></label>
        <div class="col-md-9">
            <?php
                switch($field['field']):
                    case'input':
                        ?>
                        <input type="<?= $field['type']?>" name="<?= $field['name']?>" class="form-control" id="<?= $field['name']?>" placeholder="<?= $field['placeholder']?>" <?= (isset($_POST))? 'value="'.$_POST[$field['name']].'"' : ''?>>
                        <?php
                        break;
                    case'textarea':
                        ?>
                        <textarea class="form-control" name="<?= $field['name']?>"><?= (isset($_POST) and !empty($_POST)) ? $_POST[$field['name']] : ''?></textarea>
                        <?php
                        break;
                    case'file':
                        ?>
                        <input type="file" name="<?= $field['name']?>" class="form-control" id="<?= $field['name']?>" placeholder="<?= $field['placeholder']?>" <?= (isset($_POST))? 'value="'.$_POST[$field['name']].'"' : ''?>>
                        <?php
                        break;
                endswitch;
                        ?>
        </div>
    </div>
    <?php endforeach;?>
    <div class="form-group">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn btn-danger col-md-12"><?= $this->button_text?></button>
        </div>
    </div>
</form>