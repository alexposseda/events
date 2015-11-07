<?php
    $event = $this->content['event_data'];
?>
<!-- TO DO nav panel -->
<?php if(Url::getUrlSegment(1) == 'event'):?>
    <ol class="breadcrumb">
        <li><a href="/">Встречи</a></li>
        <li class="active"><?= $event['event_title']?></li>
    </ol>
<?php endif?>
<!-- -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= $event['event_title']?></h3>
        <span class="label label-default">Дата провеления: <?= $event['date_event']?></span>
        <span class="label label-default">Место провеления: <?= $event['event_place']?></span>
    </div>
    <div class="panel-body">
        <img src="/<?= $event['event_big_img']?>" alt="" class="media-object pull-left media-object-big">
        <p><?= $event['event_data']?></p>
        <div class="clearfix"></div>
        <span class="label label-default">Участников: <?= $event['event_participants']?></span>
    </div>
    <div class="panel-footer">
        <a href="#" class="btn btn-info"><?= $event['creator']?></a>
        <?php if(Auth::checkAuth()):?>
        <a class="btn btn-success pull-right" href="#" role="button">Принять участие</a>
        <?php else:?>
            <a class="btn btn-success pull-right disabled" href="#" role="button">Принять участие</a>
        <?php endif;?>
        <div class="clearfix"></div>
    </div>
</div>
