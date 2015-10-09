<?php
/*
Plugin Name: Fancy Gallery Lite
Plugin URI: http://dennishoppe.de/en/wordpress-plugins/fancy-gallery
Description: Fancy Gallery enables you to create and manage galleries and converts your galleries in post and pages to valid HTML5 blocks and associates linked images with a nice and responsive lightbox.
Version: 1.5.32
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/

If (Version_Compare(PHP_VERSION, '5.3.0', '<')):

  # Add PHP Version warning to the dashboard
  Add_Action('admin_notices', 'Fancy_Gallery_PHP53_Version_Warning');
  function Fancy_Gallery_PHP53_Version_Warning(){ ?>
    <div class="error">
      <p><?php PrintF('<strong>%1$s:</strong> You need at least <strong>PHP 5.3</strong> or higher to use %1$s. You are using PHP %2$s. Please ask your hoster for an upgrade.', 'Fancy Gallery', PHP_VERSION) ?></p>
    </div><?php
  }

Else:

  $plugin_folder = DirName(__FILE__);

  # Load core classes
  Include $plugin_folder . '/classes/class.core.php';
  Include $plugin_folder . '/classes/class.gallery-post-type.php';
  Include $plugin_folder . '/classes/class.lightbox.php';
  Include $plugin_folder . '/classes/class.i18n.php';
  Include $plugin_folder . '/classes/class.mocking-bird.php';
  Include $plugin_folder . '/classes/class.options.php';
  Include $plugin_folder . '/classes/class.wpml.php';

  # Load widgets
  Include $plugin_folder . '/widgets/widget.random-images.php';
  Include $plugin_folder . '/widgets/widget.taxonomies.php';
  Include $plugin_folder . '/widgets/widget.taxonomy-cloud.php';

  # Inititalize Plugin: Would cause a synthax error in PHP < 5.3
  Eval('New WordPress\Plugin\Fancy_Gallery\Core(__FILE__);');

EndIf;