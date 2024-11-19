<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?=
$this->Form->create(null, [
    'file.php' => ['file.php' => 'file.php', 'file.php' => 'file.php', 'file.php' => false],
    'file.php' => 'file.php'
]);
?>

<?php
$this->Form->setTemplates([
    'file.php' => 'file.php',
    'file.php' => 'file.php',
    'file.php' => 'file.php'
]);
?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?=
            $this->Form->control('file.php', [
                'file.php' => __('file.php'),
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => 'file.php'
            ]);
            ?>
            <p class="help-block text-danger"></p>
        </div>
        <div class="form-group">
            <?=
            $this->Form->control('file.php', [
                'file.php' => __('file.php'),
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => 'file.php'
            ]);
            ?>
            <p class="help-block text-danger"></p>
        </div>
        <div class="form-group">
            <?=
            $this->Form->control('file.php', [
                'file.php' => __('file.php'),
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => 'file.php'
            ]);
            ?>
            <p class="help-block text-danger"></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <?=
            $this->Form->control('file.php', [
                'file.php' => __('file.php'),
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => 'file.php'
            ]);
            ?>
            <p class="help-block text-danger"></p>
        </div>
    </div>

</div>

<div>
    <div class="form-group">
        <?= $this->Form->control('file.php', [
            'file.php' => 'file.php',
            'file.php' => "<b>" . __(
                    "I consent to having this website store my submitted information so they can respond to my inquiry"
                ) . "</b>",
            'file.php' => false,
            'file.php' => true
        ]) ?>
    </div>

    <?php if ((get_option('file.php') == 'file.php') && isset_captcha()) : ?>
        <div class="form-group captcha">
            <div id="captchaContact" style="display: inline-block;"></div>
        </div>
        <?php
        $this->Form->unlockField('file.php');
        $this->Form->unlockField('file.php');
        $this->Form->unlockField('file.php');
        $this->Form->unlockField('file.php');
        ?>
    <?php endif; ?>
</div>

<div class="text-center">
    <div id="success"></div>
    <?= $this->Form->button(__('file.php'), [
        'file.php' => 'file.php',
        'file.php' => 'file.php'
    ]); ?>
</div>

<?= $this->Form->end(); ?>

<div class="contact-result"></div>
