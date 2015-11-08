<form method="<?= $this->method?>" action="<?= $this->action?>" <?= (!empty($this->enctype)) ? 'enctype="'.$this->enctype.'"' : '' ;?>>
    <?php if(!empty($this->errors)):?>
        <pre>
            <?php print_r($this->errors)?>
        </pre>
    <?php endif;?>
    <?php foreach($this->fields as $field):?>
        <div class="form-line">
            <span class="inp-name"><?= $field['title']?></span>
            <?php
            switch($field['field']):
                case'input':
                    ?>
                    <input type="<?= $field['type']?>" name="<?= $field['name']?>" value="<?= (isset($_POST[$field['name']]))? $_POST[$field['name']] : $field['value'] ;?>">
                    <?php
                    break;
                case'textarea':
                    ?>
                    <textarea name="<?= $field['name']?>"><?= (isset($_POST[$field['name']]))? $_POST[$field['name']] : $field['value'] ;?></textarea>
                    <?php
                    break;
                case'file':
                    ?>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?$field['MAX_FILE_SIZE']?>">
                    <input type="file" name="<?= $field['name']?>">
                    <?php
                    break;
            endswitch;
            ?>
        </div>
    <?php endforeach;?>
    <button type="submit"><?= $this->button_text?></button>
</form>