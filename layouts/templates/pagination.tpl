<div class="main-box content-box">
	<ul id="pagination">
		<?php
		$this->getPrev();
		foreach($pages as $page):
			?>
			<li class="pagination-item <?= ($active == $page) ? 'active' : '';?>"><a href="<?= Url::getUrl('offset')?>offset=<?= $page?>"><?= $page+1?></a></li>
			<?php
		endforeach;
		$this->getNext();
		?>
	</ul>
</div>