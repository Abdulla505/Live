<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php $user = $this->request->getSession()->read('file.php'); ?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language('file.php') ?>">
<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($this->fetch('file.php')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($this->fetch('file.php')); ?>">

    <?= $this->Assets->favicon() ?>

    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"
          rel="stylesheet">

    <?php
    if ((bool)get_option('file.php', false)) {
        echo $this->Assets->css('file.php' . APP_VERSION);
    } else {
        echo $this->Assets->css('file.php' . APP_VERSION);
        echo $this->Assets->css('file.php' . APP_VERSION);
        echo $this->Assets->css('file.php' . APP_VERSION);
        echo $this->Assets->css('file.php' . APP_VERSION);
        echo $this->Assets->css('file.php' . APP_VERSION);
    }

    echo $this->fetch('file.php');
    echo $this->fetch('file.php');
    echo $this->fetch('file.php');
    ?>

    <?= get_option('file.php'); ?>

    <?= $this->fetch('file.php') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn'file.php'admin_adminlte_theme_skin'file.php'skin-blue'file.php'/'file.php'/(\B.|\s+)/'file.php''file.php'site_name'file.php'site_name'file.php'Toggle navigation'file.php'role'file.php'admin'file.php'controller'file.php'Users'file.php'action'file.php'dashboard'file.php'prefix'file.php'member'file.php'Member Area'file.php'email'file.php'first_name'file.php'email'file.php'Member since'file.php'created'file.php'controller'file.php'Users'file.php'action'file.php'profile'file.php'prefix'file.php'member'file.php'Profile'file.php'controller'file.php'Users'file.php'action'file.php'logout'file.php'prefix'file.php'auth'file.php'Log out'file.php'role'file.php'admin'file.php'<?= $this->Url->build([
                        'file.php' => 'file.php',
                        'file.php' => 'file.php',
                        'file.php' => 'file.php',
                    ]); ?>'file.php'Complete Upgrade Process'file.php'controller'file.php'Users'file.php'action'file.php'dashboard'file.php'Statistics'file.php'Manage Links'file.php'controller'file.php'Links'file.php'action'file.php'index'file.php'All Links'file.php'controller'file.php'Links'file.php'action'file.php'hidden'file.php'Hidden Links'file.php'controller'file.php'Links'file.php'action'file.php'inactive'file.php'Inactive Links'file.php'earning_mode'file.php'campaign'file.php'campaign'file.php'Campaigns'file.php'controller'file.php'Campaigns'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Campaigns'file.php'action'file.php'createInterstitial'file.php'Create Interstitial Campaign'file.php'controller'file.php'Campaigns'file.php'action'file.php'createBanner'file.php'Create Banner Campaign'file.php'controller'file.php'Campaigns'file.php'action'file.php'createPopup'file.php'Create Popup Campaign'file.php'Prices'file.php'controller'file.php'Options'file.php'action'file.php'interstitial'file.php'prefix'file.php'admin'file.php'Interstitial'file.php'controller'file.php'Options'file.php'action'file.php'banner'file.php'prefix'file.php'admin'file.php'Banner'file.php'controller'file.php'Options'file.php'action'file.php'popup'file.php'prefix'file.php'admin'file.php'Popup'file.php'earning_mode'file.php'campaign'file.php'simple'file.php'Payout Rates'file.php'controller'file.php'Options'file.php'action'file.php'payoutInterstitial'file.php'prefix'file.php'admin'file.php'Interstitial'file.php'controller'file.php'Options'file.php'action'file.php'payoutBanner'file.php'prefix'file.php'admin'file.php'Banner'file.php'controller'file.php'Options'file.php'action'file.php'payoutPopup'file.php'prefix'file.php'admin'file.php'Popup'file.php'Withdraws'file.php'controller'file.php'Withdraws'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Withdraws'file.php'action'file.php'export'file.php'Export'file.php'Users'file.php'controller'file.php'Users'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Users'file.php'action'file.php'add'file.php'Add'file.php'controller'file.php'Users'file.php'action'file.php'referrals'file.php'Referrals'file.php'controller'file.php'Users'file.php'action'file.php'export'file.php'Export'file.php'controller'file.php'Reports'file.php'action'file.php'campaigns'file.php'Reports'file.php'Plans'file.php'controller'file.php'Plans'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Plans'file.php'action'file.php'add'file.php'Add'file.php'controller'file.php'Invoices'file.php'action'file.php'index'file.php'Invoices'file.php'Blog'file.php'controller'file.php'Posts'file.php'action'file.php'index'file.php'Posts List'file.php'controller'file.php'Posts'file.php'action'file.php'add'file.php'Add Post'file.php'Pages'file.php'controller'file.php'Pages'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Pages'file.php'action'file.php'add'file.php'Add'file.php'Testimonials'file.php'controller'file.php'Testimonials'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Testimonials'file.php'action'file.php'add'file.php'Add'file.php'Announcements'file.php'controller'file.php'Announcements'file.php'action'file.php'index'file.php'List'file.php'controller'file.php'Announcements'file.php'action'file.php'add'file.php'Add'file.php'controller'file.php'Options'file.php'action'file.php'menu'file.php'Menu Manger'file.php'Advanced'file.php'controller'file.php'Advanced'file.php'action'file.php'statistics'file.php'Statistics Table'file.php'controller'file.php'Options'file.php'action'file.php'system'file.php'System Info'file.php'Settings'file.php'controller'file.php'Options'file.php'action'file.php'index'file.php'Settings'file.php'controller'file.php'Options'file.php'action'file.php'ads'file.php'Ads'file.php'controller'file.php'Options'file.php'action'file.php'withdraw'file.php'Withdraw'file.php'controller'file.php'Options'file.php'action'file.php'email'file.php'Email'file.php'controller'file.php'Options'file.php'action'file.php'socialLogin'file.php'Social Login'file.php'controller'file.php'Options'file.php'action'file.php'payment'file.php'Payment Methods'file.php'content_title'file.php'Dashboard'file.php'content_title'file.php'content'file.php'Version'file.php'Copyright &copy;'file.php'site_name'file.php's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>


</div>

<?= $this->element('file.php'); ?>

<script data-cfasync="false" src="<?= $this->Assets->url('file.php' . APP_VERSION) ?>"></script>

<?php
if ((bool)get_option('file.php', false)) {
    echo $this->Assets->script('file.php' . APP_VERSION);
} else {
    echo $this->Assets->script('file.php' . APP_VERSION);
    echo $this->Assets->script('file.php' . APP_VERSION);
    echo $this->Assets->script('file.php' . APP_VERSION);
    echo $this->Assets->script('file.php' . APP_VERSION);
    echo $this->Assets->script('file.php' . APP_VERSION);
    echo $this->Assets->script('file.php' . APP_VERSION);
}
?>

<?= $this->fetch('file.php') ?>
</body>
</html>
