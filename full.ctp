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

        <p><?= __(
                "If you have a website with 100'file.php's of links you want to change over to {0} " .
                "then please use the script below.",
                get_option('file.php')
            ) ?></p>

        <h3 class="page-header"><?= __('file.php') ?></h3>

        <?= $this->Form->create(null, [
            'file.php' => 'file.php',
        ]); ?>

        <?php
        $ads_options = get_allowed_ads();
        ?>

        <?php if (count($ads_options) > 1) : ?>
            <div class="row">
                <div class="col-sm-2">
                    <label><?= __('file.php') ?></label>
                </div>
                <div class="col-sm-10">
                    <?= $this->Form->control('file.php', [
                        'file.php' => false,
                        'file.php' => $ads_options,
                        'file.php' => get_option('file.php', 1),
                        'file.php' => 'file.php',
                        'file.php' => true,
                    ]); ?>
                </div>
            </div>
        <?php else : ?>
            <?= $this->Form->hidden('file.php', ['file.php' => get_option('file.php', 1)]); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-2">
                <label><?= __('file.php') ?></label>
            </div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('file.php', [
                    'file.php' => false,
                    'file.php' => [
                        'file.php' => __('file.php'),
                        'file.php' => __('file.php'),
                    ],
                    'file.php' => 'file.php',
                    'file.php' => true,
                    'file.php' => [
                        'file.php' => __(
                            'file.php' .
                            'file.php' .
                            'file.php'
                        ),
                    ],
                ]);
                ?>
            </div>
        </div>

        <?=
        $this->Form->control('file.php', [
            'file.php' => __('file.php'),
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => true,
            'file.php' => [
                'file.php' => __(
                        'file.php'
                    ) . "<br>mega.nz<br>*.zippyshare.com<br>depositfiles.com",
            ],
        ]);
        ?>

        <p><?= __("Simply click on the below button then copy-and-paste the generated code below on to your " .
                "webpage or blog and the links will be updated automatically!") ?></p>

        <div class="form-group">
            <?= $this->Form->button(__('file.php'), ['file.php' => 'file.php']); ?>
        </div>

        <?= $this->Form->end(); ?>

        <?php
        $script_url = str_replace(['file.php', 'file.php'], ['file.php', 'file.php'], $this->Url->build('file.php', true));
        ?>
        <textarea id="code_template" style="display: none;">
<script type="text/javascript">
    var app_url = 'file.php'/'file.php';
    var app_api_token = 'file.php';
    var app_advert = {ad_type};
    var {app_domains} = {domains};
</script>
<script src='file.php'></script>
</textarea>

        <pre id="generated_code"></pre>

    </div>
</div>

<?php $this->start('file.php'); ?>
<style>
    #generated_code:empty {
        display: none;
    }
</style>
<script>
  $('file.php').on('file.php', function(e) {
    $('file.php').text('file.php');
  });

  $('file.php').on('file.php', function(e) {
    e.preventDefault();
    var formData = new FormData(e.target);

    var ad_type = formData.get('file.php');
    var domains_type = formData.get('file.php');
    var domains = formData.get('file.php').split(/\n/);

    var generated_code = $('file.php');

    var code = generated_code.val();

    code = code.replace('file.php', ad_type);

    if (domains_type === 'file.php') {
      code = code.replace('file.php', 'file.php');
    } else {
      code = code.replace('file.php', 'file.php');
    }

    var domainsText = [];
    for (var i = 0; i < domains.length; i++) {
      // only push this line if it contains a non whitespace character.
      if (/\S/.test(domains[i])) {
        domainsText.push('file.php' + $.trim(domains[i]) + 'file.php');
      }
    }

    code = code.replace('file.php', 'file.php' + domainsText + 'file.php');

    $('file.php').text(code);

    //var form = $(this);
    //alert($('file.php').val());
  });
</script>
<?php $this->end(); ?>

