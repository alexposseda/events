<?php
    if($this->creator == $_SESSION['user_id'] or Auth::getAccessLevel() == 'admin'):
        ?>
        <div class="control-box">
            <a href="/event/edit/<?= $this->id?>" class="button">Редактировать встречу</a>
            <a href="/event/delete/<?= $this->id?>" class="button">Удалить встречу</a>
        </div>
        <?php
    endif;
?>
<div class="small-event-box">
    <div class="box-header">
        <p class="event-title"><?= $this->event_title?></p>
        <div class="event-info">
            <p class="event-creator">Создатель: <?= $this->creator_login?></p>
        </div>
    </div>
    <div class="box-body">
        <div class="inline-box">
            <div class="box-cover-full">
                <img src="/<?= $this->event_full_cover?>" alt="">
            </div>
            <div class="participants">
                <span class="participant">Участники <?= $this->event_participants?></span>
                <?php if($this->getParticipants()):?>
                <ol>
                    <?php foreach($this->getParticipants() as $user_login):?>
                        <li><?= $user_login?></li>
                    <?php endforeach;?>
                </ol>
                <?php endif;?>
            </div>
        </div>
        <p class="box-data inline-box">
            <?= $this->event_data?>
        </p>
    </div>
    <div class="box-footer">
        <a href="#" class="button more">Принять участие</a>
        <div class="event-info">
            <p class="event-date">Время проведения: <?= $this->date_event?></p>
            <p class="event-place">Место проведения: <?= $this->event_place?></p>
        </div>
    </div>
</div>