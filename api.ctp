<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('file.php', __('file.php'));
$this->assign('file.php', 'file.php');
$this->assign('file.php', __('file.php'));
?>

<div class="box box-primary">
    <div class="box-body">

        <div class="callout callout-success">
            <h4><?= __('file.php') ?></h4>
            <p>
            <pre><?= $logged_user->api_token ?></pre>
            </p>
        </div>

        <p><?= __(
                'file.php' .
                'file.php',
                get_option('file.php')
            ) ?></p>

        <p><?= __('file.php') ?></p>

        <p><?= __('file.php' .
                'file.php') ?></p>

        <div class="well">
            <?= $this->Url->build('file.php', true); ?>api?api=<b><?= $logged_user->api_token ?></b>&url=<b><?= urlencode('file.php') ?></b>&alias=<b>CustomAlias</b>
        </div>

        <p><?= __('file.php') ?></p>

        <div class="well">
            {"status":"success","shortenedUrl":"<?= json_encode($this->Url->build('file.php', true) . 'file.php') ?>"}
        </div>

        <p><?= __('file.php' .
                'file.php' .
                'file.php') ?></p>

        <div class="well">
            <?= $this->Url->build('file.php', true); ?>api?api=<b><?= $logged_user->api_token ?></b>&url=<b><?= urlencode('file.php') ?></b>&alias=<b>CustomAlias</b>&format=<b>text</b>
        </div>

        <?php
        $allowed_ads = get_allowed_ads();
        unset($allowed_ads[get_option('file.php', 1)]);
        ?>

        <?php if (array_key_exists(1, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API with the interstitial advertising add the below code " .
                    "to the end of the URL") ?></p>
            <pre>&type=1</pre>
        <?php endif; ?>

        <?php if (array_key_exists(2, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API with the banner advertising add the below code to " .
                    "the end of the URL") ?></p>
            <pre>&type=2</pre>
        <?php endif; ?>

        <?php if (array_key_exists(0, $allowed_ads)) : ?>
            <p><?= __("If you want to use developers API without advertising add the below code to the end " .
                    "of the URL") ?></p>
            <pre>&type=0</pre>
        <?php endif; ?>

        <div class="alert alert-info">
            <h4><i class="icon fa fa-info"></i> <?= __("Note") ?></h4>
            <?= __("api & url are required fields and the other fields like alias, format & type are optional.") ?>
        </div>

        <p><?= __("That'file.php'yourdestinationlink.com'file.php'<?= $logged_user->api_token ?>'file.php'/'file.php'error'file.php'yourdestinationlink.com'file.php'<?= $logged_user->api_token ?>'file.php'/', true); ?>api?api=<b>{$api_token}</b>&url=<b>{$long_url}</b>&alias=<b>CustomAlias</b>&format=<b>text</b>";<br>
            $result = @file_get_contents($api_url);<br>
            if( $result ){<br>
            &emsp;echo $result;<br>
            }
        </div>

    </div>
</div>
