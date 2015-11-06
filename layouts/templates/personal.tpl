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
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach($this->content['events'] as $event):
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $event['event_title']?></h3>
                        <span class="label label-default">Дата провеления: <?= $event['date_event']?></span>
                        <span class="label label-default">Место провеления: <?= $event['event_place']?></span>
                    </div>
                    <div class="panel-body">
                        <img src="/storage/events/<?= $event['id']?>/<?= $event['event_ava']?>" alt="" class="media-object pull-left media-object-small">
                        <p><?= $event['event_description']?></p>
                    </div>
                    <div class="panel-footer">
                        <a href="#" class="btn btn-info"><?= $event['creator']?></a>
                        <a class="btn btn-default pull-right" href="/main/event/<?= $event['id']?>" role="button">View details &raquo;</a>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php
            endforeach;
            ?>
        </div>
    </div>
    <?php
endif;
//TODO добавить пагинацию
?>