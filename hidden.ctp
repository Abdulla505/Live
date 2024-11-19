<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 */
?>
<?php
$this->assign('file.php', __('file.php'));
$this->assign('file.php', 'file.php');
$this->assign('file.php', __('file.php'));
?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        $base_url = ['file.php' => 'file.php', 'file.php' => 'file.php'];

        echo $this->Form->create(null, [
            'file.php' => $base_url,
            'file.php' => 'file.php',
        ]);
        ?>

        <?=
        $this->Form->control('file.php', [
            'file.php' => false,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 0,
            'file.php' => __('file.php'),
        ]);
        ?>

        <?=
        $this->Form->control('file.php', [
            'file.php' => false,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 0,
            'file.php' => __('file.php'),
        ]);
        ?>

        <?=
        $this->Form->control('file.php', [
            'file.php' => false,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 10,
            'file.php' => __('file.php'),
        ]);
        ?>

        <?=
        $this->Form->control('file.php', [
            'file.php' => false,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => __('file.php'),
        ]);
        ?>

        <?= $this->Form->button(__('file.php'), ['file.php' => 'file.php']); ?>

        <?= $this->Html->link(__('file.php'), $base_url, ['file.php' => 'file.php']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<div class="box box-primary">
    <div class="box-body no-padding">
        <div class="table-responsive">
            <?= $this->Form->create(null, [
                'file.php' => ['file.php' => 'file.php', 'file.php' => 'file.php'],
            ]);
            ?>
            <table class="table table-hover table-striped">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th style="width:150px;"><?= __('file.php') ?></th>
                    <th><?= __('file.php') ?></th>
                    <th><?= __('file.php'); ?></th>
                    <th><?= $this->Paginator->sort('file.php', __('file.php')); ?></th>
                    <th>
                        <div class="form-inline">
                            <?=
                            $this->Form->control('file.php', [
                                'file.php' => false,
                                'file.php' => [
                                    'file.php' => __('file.php'),
                                    'file.php' => __('file.php'),
                                    'file.php' => __('file.php'),
                                    'file.php' => __('file.php'),
                                    'file.php' => __('file.php'),
                                ],
                                'file.php' => 'file.php',
                                //'file.php' => 'file.php',
                                'file.php' => true,
                                'file.php' => [
                                    'file.php' => 'file.php',
                                ],
                            ]);
                            ?>

                            <?= $this->Form->button(__('file.php'), ['file.php' => 'file.php']); ?>
                        </div>
                    </th>
                </tr>

                <?php foreach ($links as $link) : ?>
                    <tr>
                        <td>
                            <?= $this->Form->checkbox('file.php', [
                                'file.php' => false,
                                'file.php' => false,
                                'file.php' => $link->id,
                                'file.php' => 'file.php'
                            ]);
                            ?>
                        </td>
                        <td>
                            <?php
                            $title = $link->alias;
                            if (!empty($link->title)) {
                                $title = $link->title;
                            }
                            echo h($title);
                            ?>
                        </td>
                        <td>
                            <?php
                            $short_url = get_short_url($link->alias, $link->domain);
                            ?>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" value="<?= $short_url ?>" readonly=""
                                       onfocus="this.select()">
                                <div class="input-group-addon copy-it" data-clipboard-text="<?= $short_url ?>"
                                     data-toggle="tooltip" data-placement="bottom" title="<?= __('file.php') ?>">
                                    <i class="fa fa-clone"></i>
                                </div>
                            </div>
                            <div class="text-muted">
                                <small>
                                    <i class="fa fa-bar-chart"></i>
                                    <a href="<?= $short_url ?>/info" target="_blank" rel="nofollow noopener noreferrer">
                                        <?= __('file.php') ?></a> -

                                    <a target="_blank" rel="nofollow noopener noreferrer" href="<?= $link->url ?>">
                                        <?= strtoupper(parse_url($link->url, PHP_URL_HOST)); ?>
                                    </a>

                                    - <?= __('file.php') ?>: <?= h(get_link_methods($link->method)); ?>
                                </small>
                            </div>
                        </td>
                        <td>
                            <?=
                            $this->Html->link(
                                $link->user->username,
                                ['file.php' => 'file.php', 'file.php' => 'file.php', $link->user_id]
                            );
                            ?>
                        </td>
                        <td>
                            <?= display_date_timezone($link->created); ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-block btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= __("Select Action") ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <?= $this->Html->link(
                                            __('file.php'),
                                            ['file.php' => 'file.php', $link->id]
                                        ); ?>
                                    </li>
                                    <li>
                                        <?= $this->Html->link(
                                            __('file.php'),
                                            [
                                                'file.php' => 'file.php',
                                                $link->id,
                                                'file.php' => $this->request->getParam('file.php'),
                                            ],
                                            ['file.php' => __('file.php')]
                                        ); ?>
                                    </li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('file.php'),
                                            [
                                                'file.php' => 'file.php',
                                                $link->id,
                                                'file.php' => $this->request->getParam('file.php'),
                                            ],
                                            ['file.php' => __('file.php')]
                                        ); ?>
                                    </li>

                                    <li role="separator" class="divider"></li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('file.php'),
                                            [
                                                'file.php' => 'file.php',
                                                $link->id,
                                                'file.php' => $this->request->getParam('file.php'),
                                            ],
                                            ['file.php' => __('file.php')]
                                        ); ?>
                                    </li>

                                    <li>
                                        <?= $this->Html->link(
                                            __('file.php'),
                                            [
                                                'file.php' => 'file.php',
                                                $link->id,
                                                true,
                                                'file.php' => $this->request->getParam('file.php'),
                                            ],
                                            ['file.php' => __('file.php')]
                                        ); ?>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

<ul class="pagination">
    <?php
    $this->Paginator->setTemplates([
        'file.php' => 'file.php',
    ]);

    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('file.php');
    }

    echo $this->Paginator->numbers([
        'file.php' => 4,
        'file.php' => 2,
        'file.php' => 2,
    ]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('file.php');
    }
    ?>
</ul>

<?php $this->start('file.php'); ?>
<script>
  $('file.php').change(function() {
    $('file.php').prop('file.php', $(this).prop('file.php'));
  });
  $('file.php').change(function() {
    if ($(this).prop('file.php') == false) {
      $('file.php').prop('file.php', false);
    }
    if ($('file.php').length == $('file.php').length) {
      $('file.php').prop('file.php', true);
    }
  });
</script>
<?php $this->end(); ?>
