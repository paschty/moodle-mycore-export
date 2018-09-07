<?php

/**
 * MyCoRe Export Portfolio
 *
 * @author Sebastian Hofmann<sebastian.hofmann@gbv.de>
 */
require_once($CFG->libdir . '/portfolio/plugin.php');

class portfolio_plugin_blogexport extends portfolio_plugin_push_base
{


    private $mycoreid = null;

    private const PORTFOLIO_MYCORE_EXPORT = "portfolio_mycore_export";


    /**
     * Class to inherit from for 'push' type plugins
     *
     * Eg: those that send the file via a HTTP post or whatever
     *
     */
    public function supported_formats()
    {
        return array(PORTFOLIO_FORMAT_FILE,
            PORTFOLIO_FORMAT_PLAINHTML,
            PORTFOLIO_FORMAT_RICHHTML);
    }

    /**
     * How long does this reasonably expect to take..
     * Should we offer the user the option to wait..
     * This is deliberately nonstatic so it can take filesize into account
     *
     * @param string $callertime - what the caller thinks
     *                             the portfolio plugin instance
     *                             is given the final say
     *                             because it might be (for example) download.
     * @return string
     */
    public function expected_time($callertime)
    {
        return $callertime;
    }

    /**
     * Called after the caller has finished having control
     * of its prepare_package function.
     * This function should read all the files from the portfolio
     * working file area and zip them and send them or whatever it wants.
     * get_tempfiles to get the list of files.
     * @see get_tempfiles
     *
     */
    public function prepare_package()
    {
        return true;
    }

    /**
     * Whether this plugin supports multiple exports in the same session
     * most plugins should handle this, but some that require a redirect for authentication
     * and then don't support dynamically constructed urls to return to (eg box.net)
     * need to override this to return false.
     * This means that moodle will prevent multiple exports of this *type* of plugin
     * occurring in the same session.
     *
     * @return bool
     */
    public static function allows_multiple_exports()
    {
        return true;
    }


    /**
     * This is the function that is responsible for sending
     * the package to the remote system,
     * or whatever request is necessary to initiate the transfer.
     *
     * @return bool success
     * @throws portfolio_plugin_exception if formatclass is PORTFOLIO_FORMAT_RICHHTML or PORTFOLIO_FORMAT_PLAINHTML
     */
    public function send_package()
    {
        global $CFG;
        $files = $this->exporter->get_tempfiles();
        $fileCount = count($files);


        switch ($this->exporter->get('formatclass')) {
            case PORTFOLIO_FORMAT_RICHHTML:
                throw new portfolio_plugin_exception('err_not_supported_rich_html', self::PORTFOLIO_MYCORE_EXPORT);
                break;
            case PORTFOLIO_FORMAT_PLAINHTML:
                throw new portfolio_plugin_exception('err_not_supported_plain_html', self::PORTFOLIO_MYCORE_EXPORT);
                break;
            default:
                // Files


        }
        // TODO: Implement send_package() method.

        return true;
    }

    /**
     * The url for the user to continue to their portfolio
     * during the lifecycle of the request
     */
    public function get_interactive_continue_url()
    {
        // TODO: Implement get_interactive_continue_url() method.
    }

    public function export_config_form(&$mform)
    {
        $mform->addElement('text', 'plugin_auth_user', get_string('auth_user', self::PORTFOLIO_MYCORE_EXPORT));
        $mform->addElement('password', 'plugin_auth_password', get_string('auth_password', self::PORTFOLIO_MYCORE_EXPORT));

        $mform->addElement('text', 'plugin_mods_title', get_string('mods_title', self::PORTFOLIO_MYCORE_EXPORT));
        $mform->addElement('text', 'plugin_mods_sub_title', get_string('mods_sub_title', self::PORTFOLIO_MYCORE_EXPORT));
        $mform->addElement('text', 'plugin_mods_abstract', get_string('mods_abstract', self::PORTFOLIO_MYCORE_EXPORT));

    }

    public function export_config_validation(array $data)
    {
        $errors = array();

        if (empty($data["plugin_auth_user"])) {
            $errors["plugin_auth_user"] = get_string('invalid_plugin_auth_user_empty', self::PORTFOLIO_MYCORE_EXPORT);
        }

        if (empty($data["plugin_auth_password"])) {
            $errors["plugin_auth_password"] = get_string('invalid_plugin_auth_password_empty', self::PORTFOLIO_MYCORE_EXPORT);
        }

        if (!empty("plugin_auth_user") && !empty($data["plugin_auth_password"])) {
            // do curl check the login!
        }

        if (empty($data["plugin_mods_title"])) {
            $errors["plugin_mods_title"] = get_string('invalid_plugin_mods_title_empty', self::PORTFOLIO_MYCORE_EXPORT);
        }

        return $errors;
    }

    public static function admin_config_form(&$mform)
    {
        $mform->addElement('text', 'plugin_admin_repository_url', get_string('repo_url', self::PORTFOLIO_MYCORE_EXPORT));
    }

    public static function admin_config_validation($data)
    {
        $errors = array();

        if (empty($data["plugin_admin_repository_url"])) {
            $errors["plugin_admin_repository_url"] = get_string('plugin_admin_repository_url_empty', self::PORTFOLIO_MYCORE_EXPORT);
        } else {
            // do curl check the repo!
        }

        return $errors;
    }

}

?>
