<div id="event_data">
    <span id="close">закрыть</span>
    <p>Место проведения: <?= $event['event_place']?></p>
    <p>Описание:</p>
    <p><?= $event['event_data']?></p>
    <?php if(!empty($event['all_participants'])):?>
        <p>Участники:</p>
        <ol>
            <?php foreach ($event['all_participants'] as $participant):?>
            <li><?= $participant?></li>
            <?php endforeach;?>
        </ol>
    <?php endif;?>
</div>