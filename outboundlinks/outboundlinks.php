<?php
/**
 * @package OutboundLinks
 */
/*
  Plugin Name: OutboundLinks
  Plugin URI: http://clariontechnologies.co.in
  Description: OutboundLinks
  Version: 3.0.0
  Author: Yogesh Pawar
  Author URI: http://clariontechnologies.co.in
  License: GPLv2 or later
  Text Domain: OutboundLinks
 */

//Plugin Constant
defined('ABSPATH') or die('Restricted direct access!');
$plugin = plugin_basename(__FILE__);

//Main Plugin files
if (!class_exists('OutBound_Links')) {
    require('classes/class.outbound.links.php');
}

?>