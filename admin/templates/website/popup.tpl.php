<?php
	$this->extend('layout');
?>

<?php $this->set('title', $websiteTitlePrefix . " " . $page->title) ?>

<?php if (!empty($websiteTitlePrefix)): ?>
<?php $this->set('websiteTitle', $websiteTitlePrefix) ?>
<?php endif; ?>

<?php if (!empty($page->title)): ?>
<?php $this->set('pageTitle', $page->title) ?>
<?php endif; ?>

<?php if (!empty($page->description)): ?>
<?php $this->set('meta-description', $page->description) ?>
<?php endif; ?>

<?php if (!empty($page->keywords)): ?>
<?php $this->set('meta-keywords', $page->keywords) ?>
<?php endif; ?>

<?php if ($page->noIndex) : ?>
<?php $robots[] = 'noindex' ?>
<?php endif; ?>

<?php if ($page->noFollow) : ?>
<?php $robots[] = 'nofollow' ?>
<?php endif; ?>

<?php if (!empty($robots)): ?>
<?php $this->set('meta-robots', implode(',', $robots)) ?>
<?php endif; ?>

<?php if ($googleAnalyticsEnabled == 'true'): ?>
<?php $this->set('googleAnalytics', $this->render('_google_analytics', array('account' => $googleAnalyticsAccount))) ?>
<?php endif;?>

<?php echo $pageData ?>

<?php if ($this->has('content')): ?>
<?php $this->output('content') ?>
<?php endif; ?>