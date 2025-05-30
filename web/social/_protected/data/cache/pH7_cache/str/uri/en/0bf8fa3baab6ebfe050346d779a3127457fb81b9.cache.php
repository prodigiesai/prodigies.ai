<?php defined('PH7') or exit('Restricted access');
/*
Created on 2025-01-11 20:48:10
File ID: routefile
*/
/**
 * @author     Pierre-Henry Soria
 * @email      hello@ph7builder.com
 * @link       https://ph7builder.com
 * @copyright  (c) 2011-2025, Pierre-Henry Soria. All Rights Reserved.
 */
$_mData = <<<'EOF'
s:16232:"<?xml version="1.0" encoding="utf-8"?>
<routes>

    <!-- BEGIN REWRITING FOR MODULES -->


    <!-- BEGIN SYSTEM MODULES -->

    <!-- Progressive Web App (PWA) -->
    <route url="manifest\.json" path="system/modules" module="pwa" controller="main" action="manifest" />
    <route url="browserconfig\.xml" path="system/modules" module="pwa" controller="main" action="browserconfig" />

    <!-- User -->
      <route url="@([a-zA-Z0-9_-]+)" path="system/modules" module="user" controller="profile" action="index" vars="username" />
      <route url="signup" path="system/modules" module="user" controller="signup" action="step1" />
      <route url="login" path="system/modules" module="user" controller="main" action="login" />
      <route url="resend-activation" path="system/modules" module="user" controller="main" action="resendactivation" />
      <route url="user/setting/delete-account/?([a-z]+)?" path="system/modules" module="user" controller="setting" action="delete" vars="delete_status" />
      <!-- admin -->
      <route url="user/edit/?([0-9]+)?" path="system/modules" module="user" controller="setting" action="edit" vars="profile_id" />
      <route url="user/avatar/?([0-9]+)?/?([a-zA-Z0-9_-]+)?/?([^/]+)?/?([a-z]+)?" path="system/modules" module="user" controller="setting" action="avatar" vars="profile_id,username,first_name,sex" />
      <route url="user/design/?([0-9]+)?/?([a-zA-Z0-9_-]+)?/?([^/]+)?/?([a-z]+)?" path="system/modules" module="user" controller="setting" action="design" vars="profile_id,username,first_name,sex" />

      <!-- search -->
      <route url="meet-people" path="system/modules" module="user" controller="browse" action="index" />
      <route url="search-people" path="system/modules" module="user" controller="search" action="index" />
      <route url="quick-search-people" path="system/modules" module="user" controller="search" action="quick" />
      <route url="advanced-search-people" path="system/modules" module="user" controller="search" action="advanced" />

      <!-- friend -->
      <route url="friends/?([a-zA-Z0-9_-]+)?" path="system/modules" module="friend" controller="main" action="index" vars="username" />
      <route url="mutual-friends/?([a-zA-Z0-9_-]+)?" path="system/modules" module="friend" controller="main" action="mutual" vars="username" />
      <route url="friends/search/([a-zA-Z0-9_-]+)/?(mutual)?" path="system/modules" module="friend" controller="main" action="search" vars="username,action" />
      <!-- visitor -->
      <route url="user/visitors/?([a-zA-Z0-9_-]+)?" path="system/modules" module="user" controller="visitor" action="index" vars="username" />
      <route url="user/visitors/search/?([a-zA-Z0-9_-]+)" path="system/modules" module="user" controller="visitor" action="search" vars="username" />
      <!-- birthday -->
      <route url="user/birthday-people/?([a-z]{3,6})?" path="system/modules" module="birthday" controller="user" action="index" vars="gender" />

      <route url="see-you-soon" path="system/modules" module="user" controller="main" action="soon" />

    <!-- Cool Profile Page -->
      <route url="new-profile/([0-9]+)?" path="system/modules" module="cool-profile-page" controller="main" action="index" vars="profile_id" />

    <!-- Map (Country page) -->
      <route url="dating/([a-zA-Z0-9_-]+)/?([^/]+)?" path="system/modules" module="map" controller="country" action="index" vars="country,city" />

    <!-- Picture -->
      <route url="photo-gallery" path="system/modules" module="picture" controller="main" action="index" />

      <route url="user/add-album" path="system/modules" module="picture" controller="main" action="addalbum" />
      <route url="user/add-photo/?([0-9]+)?" path="system/modules" module="picture" controller="main" action="addphoto" vars="album_id" />
      <route url="user/edit-album/([0-9]+)" path="system/modules" module="picture" controller="main" action="editalbum" vars="album_id" />
      <route url="user/edit-photo/([0-9]+)/(.+)/([0-9]+)" path="system/modules" module="picture" controller="main" action="editphoto" vars="album_id,picture_title,picture_id" />
      <route url="user/albums/?([a-zA-Z0-9_-]+)?" path="system/modules" module="picture" controller="main" action="albums" vars="username" />
      <route url="user/album/([a-zA-Z0-9_-]+)/([^/]+)/([0-9]+)" path="system/modules" module="picture" controller="main" action="album" vars="username,album_title,album_id" />
      <route url="user/photo/([a-zA-Z0-9_-]+)/([0-9]+)/(.+)/([0-9]+)" path="system/modules" module="picture" controller="main" action="photo" vars="username,album_id,picture_title,picture_id" />

    <!-- Video -->
      <route url="video" path="system/modules" module="video" controller="main" action="index" />

      <route url="video/add-album" path="system/modules" module="video" controller="main" action="addalbum" />
      <route url="video/add-video/?([0-9]+)?" path="system/modules" module="video" controller="main" action="addvideo" vars="album_id" />
      <route url="video/edit-album/([0-9]+)" path="system/modules" module="video" controller="main" action="editalbum" vars="album_id" />
      <route url="video/edit-video/([0-9]+)/(.+)/([0-9]+)" path="system/modules" module="video" controller="main" action="editvideo" vars="album_id,video_title,video_id" />
      <route url="video/albums/?([a-zA-Z0-9_-]+)?" path="system/modules" module="video" controller="main" action="albums" vars="username" />
      <route url="video/album/([a-zA-Z0-9_-]+)/([^/]+)/([0-9]+)" path="system/modules" module="video" controller="main" action="album" vars="username,album_title,album_id" />
      <route url="video/watch/([a-zA-Z0-9_-]+)/([0-9]+)/(.+)/([0-9]+)" path="system/modules" module="video" controller="main" action="video" vars="username,album_id,video_title,video_id" />

    <!-- Hot Or Not -->
      <route url="hot-or-not" path="system/modules" module="hotornot" controller="main" action="rating" />

    <!-- Mail -->
      <route url="mail/compose/?([a-zA-Z0-9_-]+)?/?([^/]+)?" path="system/modules" module="mail" controller="main" action="compose" vars="recipient,title" />
      <route url="mail/outbox/?([0-9]+)?" path="system/modules" module="mail" controller="main" action="outbox" vars="id" />
      <route url="mail/trash/?([0-9]+)?" path="system/modules" module="mail" controller="main" action="trash" vars="id" />
      <route url="mail/?([0-9]+)?" path="system/modules" module="mail" controller="main" action="inbox" vars="id" />

    <!-- Blog -->
      <route url="blog" path="system/modules" module="blog" controller="main" action="index" />
      <route url="blog/category/([^/]+)/?([a-zA-Z]+)?/?([a-zA-Z]+)?" path="system/modules" module="blog" controller="main" action="category" vars="name,order,sort" />
      <route url="b/([a-zA-Z0-9_-]+)" path="system/modules" module="blog" controller="main" action="read" vars="post" />
      <!--admin-->
      <route url="blog/admin/edit/([0-9]+)" path="system/modules" module="blog" controller="admin" action="edit" vars="id" />

    <!-- Note -->
      <route url="note" path="system/modules" module="note" controller="main" action="index" />
      <route url="note/category/([^/]+)/?([a-zA-Z]+)?/?([a-zA-Z]+)?" path="system/modules" module="note" controller="main" action="category" vars="name,order,sort" />
      <route url="note/author/([a-zA-Z0-9_-]+)/?([a-zA-Z]+)?/?([a-zA-Z]+)?" path="system/modules" module="note" controller="main" action="author" vars="author,order,sort" />
      <route url="n/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)" path="system/modules" module="note" controller="main" action="read" vars="username,post" />
      <route url="note/edit/([0-9]+)" path="system/modules" module="note" controller="main" action="edit" vars="id" />

    <!-- Contact -->
      <route url="contact" path="system/modules" module="contact" controller="contact" action="index" />

    <!-- Page -->
      <route url="free-online-dating" path="system/modules" module="page" controller="main" action="index" />
      <route url="help-us" path="system/modules" module="page" controller="main" action="helpus" />
      <route url="share-site" path="system/modules" module="page" controller="main" action="sharesite" />
      <route url="help/about" path="system/modules" module="page" controller="main" action="about" />
      <route url="help/faq" path="system/modules" module="page" controller="main" action="faq" />
      <route url="legal/tos" path="system/modules" module="page" controller="main" action="terms" />
      <route url="legal/affiliate/tos" path="system/modules" module="page" controller="main" action="affiliateterms" />
      <route url="legal/privacy" path="system/modules" module="page" controller="main" action="privacy" />
      <route url="legal/legal-notice" path="system/modules" module="page" controller="main" action="legalnotice" />
      <route url="partners" path="system/modules" module="page" controller="main" action="partner" />
      <route url="links" path="system/modules" module="page" controller="main" action="link" />
      <route url="jobs" path="system/modules" module="page" controller="main" action="job" />

    <!-- Error -->
      <route url="error/([0-9]+)" path="system/modules" module="error" controller="http" action="index" vars="code" />

    <!-- Site Map -->
      <route url="site-map" path="system/modules" module="xml" controller="sitemap" action="index" />
      <route url="site-map-xml/?([a-z-]+)?" path="system/modules" module="xml" controller="sitemap" action="xmlrouter" vars="action" />

    <!-- XML -->
      <route url="rss" path="system/modules" module="xml" controller="rss" action="index" />
      <route url="rss/([a-z-]+)/?([0-9]+)?" path="system/modules" module="xml" controller="rss" action="xmlrouter" vars="action,param" />
      <route url="layout\.xsl" path="system/modules" module="xml" controller="main" action="xsllayout" />

    <!-- Forum -->
      <route url="forum" path="system/modules" module="forum" controller="forum" action="index" />
      <route url="forum/topics/([^/]+)/([0-9]+)" path="system/modules" module="forum" controller="forum" action="topic" vars="forum_name,forum_id" />
      <route url="forum/post/([^/]+)/([0-9]+)/([^/]+)/([0-9]+)" path="system/modules" module="forum" controller="forum" action="post" vars="forum_name,forum_id,topic_name,topic_id" />
      <route url="forum/add-topic/([^/]+)/([0-9]+)" path="system/modules" module="forum" controller="forum" action="addtopic" vars="forum_name,forum_id" />
      <route url="forum/reply/([^/]+)/([0-9]+)/([^/]+)/([0-9]+)" path="system/modules" module="forum" controller="forum" action="reply" vars="forum_name,forum_id,topic_name,topic_id" />
      <route url="forum/edit-topic/([^/]+)/([0-9]+)/([^/]+)/([0-9]+)" path="system/modules" module="forum" controller="forum" action="edittopic" vars="forum_name,forum_id,topic_name,topic_id" />
      <route url="forum/edit-msg/([^/]+)/([0-9]+)/([^/]+)/([0-9]+)/([0-9]+)" path="system/modules" module="forum" controller="forum" action="editmessage" vars="forum_name,forum_id,topic_name,topic_id,message_id" />
      <route url="user/forum/([a-zA-Z0-9_-]+)" path="system/modules" module="forum" controller="forum" action="showpostbyprofile" vars="username" />
      <!-- admin -->
      <route url="forum/admin/add-category" path="system/modules" module="forum" controller="admin" action="addcategory" />
      <route url="forum/admin/add-forum/?([0-9]+)?" path="system/modules" module="forum" controller="admin" action="addforum" vars="category_id" />
      <route url="forum/admin/edit-category/([0-9]+)" path="system/modules" module="forum" controller="admin" action="editcategory" vars="category_id" />
      <route url="forum/admin/edit-forum/([0-9]+)" path="system/modules" module="forum" controller="admin" action="editforum" vars="forum_id" />

    <!-- Comment -->
      <route url="comment/add/([a-zA-Z]+)/([0-9]+)" path="system/modules" module="comment" controller="comment" action="add" vars="table,recipient" />
      <route url="comment/edit/([a-zA-Z]+)/([0-9]+)/([0-9]+)/([0-9]+)" path="system/modules" module="comment" controller="comment" action="edit" vars="table,recipient,sender,id" />
      <route url="comment/read/([a-zA-Z]+)/([0-9]+)" path="system/modules" module="comment" controller="comment" action="read" vars="table,id" />
      <route url="comment/post/([a-zA-Z]+)/([0-9]+)" path="system/modules" module="comment" controller="comment" action="post" vars="table,id" />

    <!-- Chat -->
      <route url="free-chat-room" path="system/modules" module="chat" controller="home" action="index" />

    <!-- Search -->
      <route url="search/([a-zA-Z0-9_-]+)" path="system/modules" module="search" controller="main" action="search" vars="table" />
      <route url="result/([a-zA-Z0-9_-]+)" path="system/modules" module="search" controller="main" action="result" vars="table" />

    <!-- Love Calculator -->
      <route url="love-calculator/([a-zA-Z0-9_-]+)" path="system/modules" module="love-calculator" controller="main" action="index" vars="second_username" />

    <!-- Admin -->
      <route url="admin123/public-file/edit/(.+)" path="system/modules" module="admin123" controller="file" action="publicedit" vars="file" />
      <route url="admin123/protected-file/edit/(.+)" path="system/modules" module="admin123" controller="file" action="protectededit" vars="file" />
      <route url="admin123/setting/ads/?([a-z]+)?" path="system/modules" module="admin123" controller="setting" action="ads" vars="ads_type" />
      <route url="admin123/setting/addads/?([a-z]+)?" path="system/modules" module="admin123" controller="setting" action="addads" vars="ads_type" />
      <route url="admin123/setting/meta-main/?([a-z][a-z]_[A-Z][A-Z])?" path="system/modules" module="admin123" controller="setting" action="metamain" vars="meta_lang" />
      <route url="admin123/edit/?([0-9]+)?" path="system/modules" module="admin123" controller="account" action="edit" vars="profile_id" />

    <!-- Affiliate -->
      <route url="affiliate" path="system/modules" module="affiliate" controller="home" action="index" />
      <route url="ai/([a-zA-Z0-9_-]+)/?(.+)?" path="system/modules" module="affiliate" controller="router" action="refer" vars="aff,action" />
      <route url="affiliate/account/delete-account/?([a-z]+)?" path="system/modules" module="affiliate" controller="account" action="delete" vars="delete_status" />
      <route url="affiliate/signup" path="system/modules" module="affiliate" controller="signup" action="step1" />
      <route url="affiliate/login" path="system/modules" module="affiliate" controller="home" action="login" />
      <route url="affiliate/resend-activation" path="system/modules" module="affiliate" controller="home" action="resendactivation" />
      <!-- admin -->
      <route url="affiliate/edit/?([0-9]+)?" path="system/modules" module="affiliate" controller="account" action="edit" vars="profile_id" />

    <!-- Lost Password -->
      <route url="forgot/([a-zA-Z0-9_-]+)" path="system/modules" module="lost-password" controller="main" action="forgot" vars="mod" />
      <route url="reset-password/([a-zA-Z0-9_-]+)/(.+)/(.+)" path="system/modules" module="lost-password" controller="main" action="reset" vars="mod,mail,hash" />

    <!-- Two-Factor Auth -->
      <route url="verification-code/([a-zA-Z0-9_-]+)" path="system/modules" module="two-factor-auth" controller="main" action="verificationcode" vars="mod" />

    <!-- Payment -->
      <route url="payment/edit/([0-9]+)" path="system/modules" module="payment" controller="admin" action="editmembership" vars="group_id" />

    <!-- Field -->
      <route url="field/list/([a-zA-Z]+)" path="system/modules" module="field" controller="field" action="all" vars="mod" />
      <route url="field/add/([a-zA-Z]+)" path="system/modules" module="field" controller="field" action="add" vars="mod" />
      <route url="field/edit/([a-zA-Z]+)/([a-zA-Z0-9_-]+)" path="system/modules" module="field" controller="field" action="edit" vars="mod,name" />

    <!-- END SYSTEM MODULES -->


    <!-- BEGIN MODULES -->

    <!-- END MODULES -->

    <!-- END REWRITING FOR MODULES -->

</routes>
";
EOF;
