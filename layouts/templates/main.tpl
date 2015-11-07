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
                <div class="row">
                    <form class="form-inline filter">
                        <div class="form-group col-md-4">
                            <select class="form-control" id="event-count" name="events_count">
                                <option selected disabled value="10">Количество записей на страницу</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="event-date">Дата встречи</label>
                            <input type="date" class="form-control" name="events_date" id="event-date">
                        </div>
                        <div class="form-group col-md-3">
                            <button type="submit" class="btn btn-default pull-right">Применить</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
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
                        <img src="/<?= $event['event_ava']?>" alt="" class="media-object pull-left media-object-small">
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
