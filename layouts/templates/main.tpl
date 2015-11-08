<div class="filter">
    <form id="event_count">
        <div class="inline-box">
            <div class="form-inline">
                <span class="inp-name">Записей на страницу</span>
                <select name="limit">
                    <option value="5" <?= ($this->limit == 5) ? 'disabled selected' : '' ;?>>5</option>
                    <option value="10" <?= ($this->limit == 10) ? 'disabled selected' : '' ;?>>10</option>
                    <option value="15" <?= ($this->limit == 15) ? 'disabled selected' : '' ;?>>15</option>
                </select>
            </div>
            <div class="form-inline">
                <span class="inp-name">Дата проведения</span>
                <input type="date" name="date" value="<?= (isset($_GET['date'])) ? $this->date : ''?>">
            </div>
        </div>
        <button type="submit">Применить</button>
    </form>
</div>
<?php
    if(!empty($this->content['events'])) {
        foreach ($this->content['events'] as $event) {
            $event->showEvent();
        }
    }else{
        echo 'nothing found';
    }

    $this->pagination->showPagination();
?>