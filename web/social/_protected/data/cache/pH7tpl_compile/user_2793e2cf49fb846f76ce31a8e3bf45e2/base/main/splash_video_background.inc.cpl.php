<?php 
namespace PH7;
defined('PH7') or exit('Restricted access');
/*
Created on 2025-01-11 20:48:10
Compiled file from: /home2/vivhjgte/public_html/social/_protected/app/system/modules/user/views/base/tpl/main/splash_video_background.inc.tpl
Template Engine: PH7Tpl version 1.6.0 by Pierre-Henry Soria
*/
/**
 * @author     Pierre-Henry Soria
 * @email      hello@ph7builder.com
 * @link       https://ph7builder.com
 * @copyright  (c) 2011-2025, Pierre-Henry Soria. All Rights Reserved.
 */
?><?php $total_videos = count(glob(PH7_PATH_TPL . PH7_TPL_NAME . '/file/splash/*_vid.jpg')) ; $video_prefix = mt_rand(1, $total_videos) ; if(!$this->browser->isMobile()) { ?> <style scoped="scoped">video#bgvid{background: url(<?php echo PH7_URL_TPL . PH7_TPL_NAME . PH7_SH?>file/splash/<?php echo $video_prefix; ?>_vid.jpg) no-repeat center}</style> <video autoplay loop muted poster="<?php echo PH7_URL_TPL . PH7_TPL_NAME . PH7_SH?>file/splash/<?php echo $video_prefix; ?>_vid.jpg" id="bgvid"> <source src="<?php echo PH7_URL_TPL . PH7_TPL_NAME . PH7_SH?>file/splash/<?php echo $video_prefix; ?>_vid.webm" type="video/webm" /> <source src="<?php echo PH7_URL_TPL . PH7_TPL_NAME . PH7_SH?>file/splash/<?php echo $video_prefix; ?>_vid.mp4" type="video/mp4" /> </video><?php } else { ?> <style scoped="scoped"> body { background: url('<?php echo PH7_URL_TPL . PH7_TPL_NAME . PH7_SH?>file/splash/<?php echo $video_prefix; ?>_vid.jpg') repeat-y center; background-size: cover; top: 50%; left: 50%; } </style><?php } ?>