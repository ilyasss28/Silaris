<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= get_option('site_description'); ?>">
    <meta name="keywords" content="<?= get_option('keywords'); ?>">
    <meta name="author" content="<?= get_option('author'); ?>">

    <title> <?= isset($title) ? $title : site_name() ?></title>

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Favicons
    ================================================== -->
    <link rel="icon" href="img/favicon/favicon-32x32.png" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/favicon/favicon-144x144.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/favicon/favicon-72x72.png">
    <link rel="apple-touch-icon-precomposed" href="img/favicon/favicon-54x54.png">
    
    <!-- CSS
    ================================================== -->
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/bootstrap.min.css">
    <!-- Template styles-->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/style.css">
    <!-- Responsive styles-->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/responsive.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/font-awesome.min.css">
    <!-- Animation -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/animate.css">
    <!-- Prettyphoto -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/prettyPhoto.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/owl.carousel.css">
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/owl.theme.css">
    <!-- Flexslider -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/flexslider.css">
    <!-- Flexslider -->
    <link rel="stylesheet" href="<?= theme_asset(); ?>css/cd-hero.css">
    <!-- Style Swicther -->
    <link id="style-switch" href="<?= theme_asset(); ?>css/presets/preset2.css" media="screen" rel="stylesheet" type="text/css">
</head>
<body>