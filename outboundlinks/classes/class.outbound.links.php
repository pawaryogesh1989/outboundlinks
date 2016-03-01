<?php

//Main Plugin Class

class OutBound_Links {

    static $instance;

    //Constructor of the Class
    public function __construct() {

        self::$instance = $this;

        add_action('wp_enqueue_scripts', array(
            $this,
            'OutBound_Links_Scripts'
        ));
        add_filter('the_content', array(
            $this,
            'add_query_vars_filter'
        ));

        register_activation_hook('outboundlinks/classes/class.outbound.links.php', array(
            $this,
            'hook_activate'
        ));
        register_deactivation_hook('outboundlinks/classes/class.outbound.links.php', array(
            $this,
            'hook_deactivate'
        ));
    }

    /* Function to include scripts necessary for the plugin.
     * Scripts are saved in the JS folder of the plugin.
     */

    public function OutBound_Links_Scripts() {
        wp_enqueue_script('outbound-script', AUTH_PLUGINS_PATH . '/outboundlinks/js/outbound.js', array(), '1.0.0', true);
    }

    /* Function to add the Outbound Rel tag and Target __blank
     * 
     */

    public function add_query_vars_filter($content) {
        global $post;

        if (!is_singular()) {
            return $content;
        } else {
            if ($post->post_type == "page" || $post->post_type == "post") {
                $buffer = '';
                $offset = 0;

                while (($start = strpos($content, '<a ', $offset)) !== false) {

                    $buffer .= substr($content, $offset, $start - $offset);
                    $end = strpos($content, '>', $start + 1);
                    $tag = substr($content, $start, $end - $start + 1);

                    if (strpos($tag, site_url()) == false) {

                        $tag = preg_replace('#rel=[\'"].*?[\'"]#', '', $tag);
                        $tag = str_replace('<a', '<a rel="outbound"', $tag);

                        $tag = preg_replace('#target=[\'"].*?[\'"]#', '', $tag);
                        $tag = str_replace('<a', '<a target="_blank"', $tag);
                    }

                    $buffer .= $tag;
                    $offset = $end + 1;
                }
                $buffer .= substr($content, $offset);
                return $buffer;
            }
        }
        return $content;
    }

    /* Plugin Acivation Hook
     * 
     */

    function hook_activate() {

        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("activate-plugin_{$plugin}");
    }

    /* Plugin Deactivation Hook
     * 
     */

    function hook_deactivate() {

        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");
    }

}

?>