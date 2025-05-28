<?php 
namespace PH7;
defined('PH7') or exit('Restricted access');
/*
Created on 2025-01-11 20:48:10
Compiled file from: /home2/vivhjgte/public_html/social/templates/themes/base/tpl/headings.inc.tpl
Template Engine: PH7Tpl version 1.6.0 by Pierre-Henry Soria
*/
/**
 * @author     Pierre-Henry Soria
 * @email      hello@ph7builder.com
 * @link       https://ph7builder.com
 * @copyright  (c) 2011-2025, Pierre-Henry Soria. All Rights Reserved.
 */
?><div class="center" id="headings"> <?php foreach(['h1' => 'h1_title', 'h2' => 'h2_title', 'h3' => 'h3_title', 'h4' => 'h4_title'] as $heading => $headingVar) { ?> <?php if(!empty($$headingVar)) { ?> <<?php echo $heading; ?>><?php echo $$headingVar ;?></<?php echo $heading; ?>> <?php } ?> <?php } ?></div>