<?php 
namespace PH7;
defined('PH7') or exit('Restricted access');
/*
Created on 2025-01-11 20:48:10
Compiled file from: /home2/vivhjgte/public_html/social/_protected/app/system/modules/user/views/base/tpl/main/index.guest_splash.inc.tpl
Template Engine: PH7Tpl version 1.6.0 by Pierre-Henry Soria
*/
/**
 * @author     Pierre-Henry Soria
 * @email      hello@ph7builder.com
 * @link       https://ph7builder.com
 * @copyright  (c) 2011-2025, Pierre-Henry Soria. All Rights Reserved.
 */
?><?php if($is_bg_video) { ?> <?php $this->display($this->getCurrentController() . PH7_DS . 'splash_video_background.inc.tpl', $this->registry->path_module_views . PH7_TPL_MOD_NAME . PH7_DS); } ?><div class="col-md-8 login_block animated fadeInDown"> <?php LoginSplashForm::display() ;?></div><?php if(!$is_mobile) { ?> <div class="pull-left col-lg-7 col-md-8 col-sm-7 col-xs-11 animated fadeInLeft"> <?php $this->display($this->getCurrentController() . PH7_DS . 'user_promo_block.inc.tpl', $this->registry->path_module_views . PH7_TPL_MOD_NAME . PH7_DS); ?> </div><?php } ?><div class="pull-left col-lg-4 col-md-4 col-sm-5 col-xs-11 animated fadeInRight"> <h1 class="red3 italic underline"><?php echo $headline; ?></h1> <div class="login_button hidden center"> <a href="<?php $design->url('user','main','login') ;?>" class="btn btn-primary btn-lg"> <strong><?php echo t('Login'); ?></strong> </a> </div> <?php JoinForm::step1() ;?> <?php if($is_mobile) { ?> <div class="s_tMarg"></div> <?php $this->display($this->getCurrentController() . PH7_DS . 'user_promo_block.inc.tpl', $this->registry->path_module_views . PH7_TPL_MOD_NAME . PH7_DS); ?> <?php } ?></div>