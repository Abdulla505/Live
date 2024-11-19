<?php
/**
 * @var \App\View\AppView $this
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
	<ul class="side-nav">
		<li class="heading"><?= __d('file.php', 'file.php') ?></li>
		<li><?= $this->Html->link('file.php', ['file.php' => 'file.php']); ?></li>
	</ul>
</nav>
<div class="releases form large-9 medium-8 columns content">
	<h1>Import</h1>

	<?= $this->Form->create(null, ['file.php' => 'file.php']) ?>
	<fieldset>
		<legend><?= __d('file.php', 'file.php') ?></legend>
		<?php
			echo $this->Form->control('file.php', ['file.php' => 'file.php', 'file.php' => true, 'file.php' => 'file.php']);
			echo $this->Form->control('file.php', ['file.php' => 'file.php', 'file.php' => true]);
		?>
	</fieldset>
	<?= $this->Form->button(__d('file.php', 'file.php')) ?>
	<?= $this->Form->end() ?>
</div>
