<?php
    if(empty($this->content['events'])):
        ?>
<div class="col-md-8">

    <div class="alert alert-danger">
        <p>Нет событий</p>
    </div>
</div>

        <?php
    else:
        foreach($this->content['events'] as $event):
            ?>
            <div class="row">
                <div class="col-md-12">
                    <h2><?= $event['event_title']?></h2>
                    <p><?= $event['event_description']?></p>
                    <p><a class="btn btn-default" href="/main/event/<?= $event['id']?>" role="button">View details &raquo;</a></p>
                </div>
            </div>
            <?php
        endforeach;
    endif;
    //TODO добавить пагинацию
?>
