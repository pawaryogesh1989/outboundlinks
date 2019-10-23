<?php

//Main Plugin Class
class OutBound_Links
{

    static $instance;

    //Constructor of the Class
    function __construct()
    {

        self::$instance = $this;

        add_action('wp_enqueue_scripts', array($this, 'outBoundScripts'));
        add_filter('the_content', array($this, 'addQueryVarsFilter'));

        register_activation_hook('outboundlinks/classes/class.outbound.links.php', array($this, 'outBoundHookActivate'));
        register_deactivation_hook('outboundlinks/classes/class.outbound.links.php', array($this, 'outBoundHookDeactivate'));
    }

    /**
     * Function to include scripts necessary for the plugin.
     */
    public function outBoundScripts()
    {
        wp_enqueue_script('outbound-script', plugins_url('assets/js/outbound.js', __DIR__), array('jquery'), '2.0.0', true);
    }

    /**
     * Function to add the Outbound Rel tag and Target __blank
     * @global type $post
     * @param type $content
     * @return type
     */
    public function addQueryVarsFilter($content)
    {
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

    /**
     * Plugin Activation Hook
     * @return type
     */
    function outBoundHookActivate()
    {

        if (!current_user_can('activate_plugins')) {
            return;
        }

        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("activate-plugin_{$plugin}");
    }

    /**
     * Plugin Deactivation Hook
     * @return type
     */
    function outBoundHookDeactivate()
    {

        if (!current_user_can('activate_plugins'))
            return;
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("deactivate-plugin_{$plugin}");
    }
}

new OutBound_Links();

?>