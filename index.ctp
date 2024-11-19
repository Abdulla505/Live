<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('file.php', __('file.php'));
$this->assign('file.php', 'file.php');
$this->assign('file.php', __('file.php'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create(null, [
            'file.php' => ['file.php' => 'file.php', 'file.php' => 'file.php']
        ]); ?>

        <?=
        $this->Form->control('file.php', [
            'file.php' => __('file.php'),
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => get_option('file.php', 'file.php'),
            'file.php' => 'file.php'
        ]);
        ?>

        <span class="help-block"><?= __('file.php',
                'file.php') ?></span>

        <?=
        $this->Form->control('file.php', [
            'file.php' => __('file.php'),
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => get_option('file.php', 'file.php'),
            'file.php' => 'file.php'
        ]);
        ?>

        <span class="help-block"><?= __('file.php',
                'file.php') ?></span>

        <?= $this->Form->button(__('file.php'), ['file.php' => 'file.php']); ?>
        <?= $this->Form->end(); ?>

    </div><!-- /.box-body -->
</div>
