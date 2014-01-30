<!doctype html>
<!--[if IE 8]><html class="ie8" dir="ltr" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" dir="ltr" lang="en"><![endif]-->
<!--[if gt IE 9]><!--> <html dir="ltr" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Storm Creative" />
        <title><?php echo SITE_NAME; ?> | <?php echo $title; ?></title>
        <meta name="keywords" content="<?php echo $meta_keywords; ?>">
        <meta name="description" content="<?php echo $meta_desc; ?>">
        <meta name="robots" content="index, follow">
        <meta name="revisit-after" content="7 days"/>
        <script src="<?php echo DIRECTORY; ?>assets/scripts/utils/modernizr.min.js"></script>
        <?php if (MEDIA_QUERIES): ?>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <!--[if lte IE 8]>
            <link rel="stylesheet" href="<?php echo DIRECTORY; ?>assets/styles/nomq.css">
            <![endif]-->
        <?php else: ?>
            <?php foreach ( $stylesheets as $style ): ?>
                <!--[if lte IE 8]>
                <link rel="stylesheet" href="<?php echo $style; ?>">
                <![endif]-->
            <?php endforeach; ?>
        <?php endif; ?>
        <?php foreach ( $stylesheets as $style ): ?>
            <!--[if gt IE 8]>-->
            <link rel="stylesheet" href="<?php echo $style; ?>">
            <!--<![endif]-->
        <?php endforeach; ?>
        <?php include('assets/includes/ga.php'); ?>
    </head>
    <body>
        <div class="wrapper">
            <?php require "assets/includes/ie-notification.php"; flush(); ?>
            <?php include "assets/includes/navigation.php"; ?>
            <div class="container content grid">
