<?php
/*
Plugin Name: oEmbed HTML5 Audio
Plugin URI: http://www.honza.info/category/wordpress/
Description: This plugin converts URLs to audio files (MP3, OGG, WAV) into HTML5 audio with Flash-based player backup
Version: 1.2
Author: Honza Skypala
Author URI: http://www.honza.info
License: WTFPL license applies
*/

class Embed_HTML5_Audio {
  const version = "1.2";

  public function __construct() {
    register_activation_hook(__FILE__, array($this, 'activate'));
    add_action('admin_notices', array($this, 'plugin_deprecated'));
  }

  public static function activate() {
    die('WordPress 3.6 and newer contains native support for <a href="http://codex.wordpress.org/Audio_Shortcode" target="_blank">audio embedding</a>. For this reason, plugin <em>oEmbed HTML5 Audio</em> is considered deprecated and it is not supported anymore. You can delete the plugin.');
  }

  public static function plugin_deprecated() {
    ?><div class="error">
        <p><?php printf('WordPress 3.6 and newer contains native support for <a href="http://codex.wordpress.org/Audio_Shortcode" target="_blank">audio embedding</a>. For this reason, plugin <em>oEmbed HTML5 Audio</em> is considered deprecated and it is not supported anymore. Please <a href="%1$s">deactivate</a> the plugin and delete it.', admin_url('plugins.php')); ?></p>
    </div><?php
  }
}

$wp_Embed_HTML5_Audio = new Embed_HTML5_Audio();
?>