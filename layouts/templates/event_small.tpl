<div class="small-event-box">
    <div class="box-header">
        <button class="button info" id="event_<?= $this->id?>" role="show_event_info">быстрый просмотр</button>
        <p class="event-title"><?= $this->event_title?></p>
        <div class="event-info">
            <p class="event-creator">Создатель: <?= $this->creator_login?></p>
        </div>
    </div>
    <div class="box-body">
        <div class="inline-box">
            <div class="box-cover">
                <img src="/<?= $this->event_cover?>" alt="">
            </div>
            <div class="participants">
                <span class="participant">Участники <?= $this->event_participants?></span>
            </div>
        </div>
        <p class="box-data inline-box">
           <?= $this->event_description?>
        </p>
    </div>
    <div class="box-footer">
        <a href="/main/event/<?= $this->id?>" class="button more">Подробнее</a>
        <div class="event-info">
            <p class="event-date">Время проведения: <?= $this->date_event?></p>
            <p class="event-place">Место проведения: <?= $this->event_place?></p>
        </div>
    </div>
</div>