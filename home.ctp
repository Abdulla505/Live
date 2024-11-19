<?php
$this->assign('file.php', (get_option('file.php')) ?: get_option('file.php'));
$this->assign('file.php', get_option('file.php'));
$this->assign('file.php', get_option('file.php'));
?>

<!-- Header -->
<header class="shorten">
    <div class="section-inner">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in wow zoomIn" data-wow-delay="0.3s"><?= __('file.php') ?></div>
                <div class="intro-heading wow pulse" data-wow-delay="2.0s"><?= __('file.php') ?></div>
                <div class="row wow rotateInUpLeft" data-wow-delay="0.3s">
                    <div class="col-sm-8 col-sm-offset-2">
                        <?php if (get_option('file.php') == 'file.php') : ?>
                            <?= $this->element('file.php'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="steps">
    <div class="container text-center">
        <div class="row wow fadeInUp">
            <div class="col-sm-4">
                <div class="step step1">
                    <div class="step-img"><i class="ms-sprite ms-sprite-step1"></i></div>
                    <h4 class="step-heading"><?= __('file.php') ?></h4>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="step step2">
                    <div class="step-img"><i class="ms-sprite ms-sprite-step2"></i></div>
                    <h4 class="step-heading"><?= __('file.php') ?></h4>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="step step3">
                    <div class="step-img"><i class="ms-sprite ms-sprite-step3"></i></div>
                    <h4 class="step-heading"><?= __('file.php') ?></h4>
                </div>
            </div>
        </div>

    </div>
</section>

<div class="separator">
    <div class="container"></div>
</div>

<section class="features">
    <div class="container text-center">
        <div class="section-title wow bounceIn">
            <h3 class="section-subheading"><?= __('file.php') ?></h3>
            <h2 class="section-heading"><?= __('file.php') ?></h2>
        </div>

        <div style="display: flex; flex-wrap: wrap;">
            <div class="col-sm-4 wow fadeInUp">
                <div class="feature">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f1"></i></div>
                    <h4 class="feature-heading"><?= __('file.php', h(get_option('file.php'))) ?></h4>
                    <div
                        class="feature-content"><?= __('file.php',
                            h(get_option('file.php'))) ?></div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f2"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __("How can you start making money with {0}? It'file.php's just that easy!",
                            h(get_option('file.php'))) ?></div>
                </div>
            </div>

            <?php if ((bool)get_option('file.php', 1)) : ?>
                <div class="col-sm-4 wow fadeInUp">
                    <div class="feature">
                        <div class="feature-img"><i class="ms-sprite ms-sprite-f3"></i></div>
                        <h4 class="feature-heading"><?= __('file.php',
                                h(get_option('file.php'))) ?></h4>
                        <div
                            class="feature-content"><?= __('file.php',
                                [h(get_option('file.php')), h(get_option('file.php'))]) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f4"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __('file.php') ?></div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f5"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __('file.php') ?></div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f6"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __('file.php',
                            display_price_currency(get_option('file.php'))) ?></div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature last">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f7"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __('file.php') ?></div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature last">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f8"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __('file.php') ?></div>
                </div>
            </div>

            <div class="col-sm-4 wow fadeInUp">
                <div class="feature last">
                    <div class="feature-img"><i class="ms-sprite ms-sprite-f9"></i></div>
                    <h4 class="feature-heading"><?= __('file.php') ?></h4>
                    <div
                        class="feature-content"><?= __('file.php') ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?=
$this->cell('file.php', [], [
    'file.php' => [
        'file.php' => 'file.php',
        'file.php' => 'file.php' . locale_get_default(),
    ],
])
?>

<?php if ((bool)get_option('file.php', 1)) : ?>
    <section class="stats">
        <div class="container">
            <div class="section-title text-center wow bounceIn">
                <h3 class="section-subheading"><?= __("Numbers speak for themselves") ?></h3>
                <h2 class="section-heading"><?= __('file.php') ?></h2>
            </div>
            <div class="row">
                <div class="col-sm-4 text-center">
                    <div class="stat wow flipInY">
                        <div class="stat-img">
                            <i class="ms-sprite ms-sprite-total-clicks"></i>
                        </div>
                        <div class="stat-num">
                            <?= $totalClicks ?>
                        </div>
                        <div class="stat-text">
                            <?= __("Total Clicks") ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <div class="stat wow flipInY">
                        <div class="stat-img">
                            <i class="ms-sprite ms-sprite-total-links"></i>
                        </div>
                        <div class="stat-num">
                            <?= $totalLinks ?>
                        </div>
                        <div class="stat-text">
                            <?= __("Total Links") ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <div class="stat wow flipInY">
                        <div class="stat-img">
                            <i class="ms-sprite ms-sprite-total-users"></i>
                        </div>
                        <div class="stat-num">
                            <?= $totalUsers ?>
                        </div>
                        <div class="stat-text">
                            <?= __("Registered users") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<div class="separator">
    <div class="container"></div>
</div>

<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <div class="section-title text-center wow bounceIn">
            <h3 class="section-subheading"><?= __("Contact Us") ?></h3>
            <h2 class="section-heading"><?= __("Get in touch!") ?></h2>
        </div>

        <?= $this->element('file.php'); ?>

    </div>
</section>
