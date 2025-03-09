<?php
/**
 * Plugin Name: WP Site GitHub Backup
 * Plugin URI: https://example.com/wp-site-github-backup
 * Description: Backup and restore your entire WordPress installation to/from a GitHub repository
 * Version: 2.1.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: wp-site-github-backup
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WP_SITE_GITHUB_BACKUP_VERSION', '2.1.0');
define('WP_SITE_GITHUB_BACKUP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_SITE_GITHUB_BACKUP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_SITE_GITHUB_BACKUP_GITHUB_REPO', 'pawelbcn/wp-site-github-backup');

// Create necessary files and directories
function wp_site_github_backup_activate() {
    // Create admin directory
    $admin_dir = plugin_dir_path(__FILE__) . 'admin';
    if (!is_dir($admin_dir)) {
        mkdir($admin_dir, 0755, true);
    }
    
    // Create partials directory
    $partials_dir = $admin_dir . '/partials';
    if (!is_dir($partials_dir)) {
        mkdir($partials_dir, 0755, true);
    }
    
    // Create CSS directory
    $css_dir = $admin_dir . '/css';
    if (!is_dir($css_dir)) {
        mkdir($css_dir, 0755, true);
    }
    
    // Create JS directory
    $js_dir = $admin_dir . '/js';
    if (!is_dir($js_dir)) {
        mkdir($js_dir, 0755, true);
    }
    
    // Create admin display file
    $admin_display_file = $partials_dir . '/admin-display.php';
    if (!file_exists($admin_display_file)) {
        file_put_contents($admin_display_file, wp_site_github_backup_get_admin_display_content());
    }
    
    // Create admin CSS file
    $admin_css_file = $css_dir . '/admin.css';
    if (!file_exists($admin_css_file)) {
        file_put_contents($admin_css_file, wp_site_github_backup_get_admin_css_content());
    }
    
    // Create admin JS file
    $admin_js_file = $js_dir . '/admin.js';
    if (!file_exists($admin_js_file)) {
        file_put_contents($admin_js_file, wp_site_github_backup_get_admin_js_content());
    }
    
    // Create backups directory
    $backups_dir = WP_CONTENT_DIR . '/backups';
    if (!is_dir($backups_dir)) {
        mkdir($backups_dir, 0755, true);
    }
}

// Get admin display content
function wp_site_github_backup_get_admin_display_content() {
    return '<?php
// If this file is called directly, abort.
if (!defined(\'WPINC\')) {
    die;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="notice notice-info">
        <p><?php _e(\'This plugin allows you to backup and restore your WordPress installation with a GitHub repository.\', \'wp-site-github-backup\'); ?></p>
    </div>
    
    <div class="wp-site-github-backup-settings">
        <h2><?php _e(\'GitHub Settings\', \'wp-site-github-backup\'); ?></h2>
        
        <form method="post" action="options.php">
            <?php settings_fields(\'wp_site_github_backup_settings\'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_github_username"><?php _e(\'GitHub Username\', \'wp-site-github-backup\'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_github_username" name="wp_site_github_backup_github_username" value="<?php echo esc_attr(get_option(\'wp_site_github_backup_github_username\')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_github_repo"><?php _e(\'GitHub Repository Name\', \'wp-site-github-backup\'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_github_repo" name="wp_site_github_backup_github_repo" value="<?php echo esc_attr(get_option(\'wp_site_github_backup_github_repo\')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_github_token"><?php _e(\'GitHub Personal Access Token\', \'wp-site-github-backup\'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="wp_site_github_backup_github_token" name="wp_site_github_backup_github_token" value="<?php echo esc_attr(get_option(\'wp_site_github_backup_github_token\')); ?>" class="regular-text">
                        <p class="description"><?php _e(\'Create a token with \\\'repo\\\' permissions at GitHub Settings → Developer Settings → Personal Access Tokens\', \'wp-site-github-backup\'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_excluded_dirs"><?php _e(\'Excluded Directories\', \'wp-site-github-backup\'); ?></label>
                    </th>
                    <td>
                        <textarea id="wp_site_github_backup_excluded_dirs" name="wp_site_github_backup_excluded_dirs" rows="5" class="large-text"><?php echo esc_textarea(get_option(\'wp_site_github_backup_excluded_dirs\', "wp-content/uploads\nwp-content/cache\nwp-content/upgrade\nwp-content/backup*\n.git")); ?></textarea>
                        <p class="description"><?php _e(\'Enter one directory per line, relative to WordPress root\', \'wp-site-github-backup\'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    
    <div class="wp-site-github-backup-actions">
        <h2><?php _e(\'Backup and Restore\', \'wp-site-github-backup\'); ?></h2>
        
        <p><?php _e(\'Use the buttons below to backup your WordPress installation to GitHub or restore from GitHub.\', \'wp-site-github-backup\'); ?></p>
        
        <div class="wp-site-github-backup-buttons">
            <button id="wp-site-github-backup-upload" class="button button-primary"><?php _e(\'Backup to GitHub\', \'wp-site-github-backup\'); ?></button>
            <button id="wp-site-github-backup-download" class="button"><?php _e(\'Restore from GitHub\', \'wp-site-github-backup\'); ?></button>
        </div>
        
        <div id="wp-site-github-backup-progress" style="display: none;">
            <h3><?php _e(\'Progress\', \'wp-site-github-backup\'); ?></h3>
            <div class="wp-site-github-backup-progress-bar">
                <div class="wp-site-github-backup-progress-bar-inner"></div>
            </div>
            <div class="wp-site-github-backup-progress-status"></div>
        </div>
        
        <div id="wp-site-github-backup-log" style="display: none;">
            <h3><?php _e(\'Log\', \'wp-site-github-backup\'); ?></h3>
            <div class="wp-site-github-backup-log-content"></div>
        </div>
    </div>
    
    <div class="wp-site-github-backup-update">
        <h2><?php _e(\'Plugin Updates\', \'wp-site-github-backup\'); ?></h2>
        
        <p><?php _e(\'Update this plugin directly from GitHub to get the latest features and bug fixes.\', \'wp-site-github-backup\'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field(\'wp_site_github_backup_update\', \'wp_site_github_backup_update_nonce\'); ?>
            <button type="submit" name="wp_site_github_backup_update_from_github" class="button button-secondary"><?php _e(\'Update Plugin from GitHub\', \'wp-site-github-backup\'); ?></button>
        </form>
    </div>
</div>';
}

// Get admin CSS content
function wp_site_github_backup_get_admin_css_content() {
    return '.wp-site-github-backup-settings,
.wp-site-github-backup-actions {
    margin-top: 20px;
    padding: 20px;
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.wp-site-github-backup-buttons {
    margin-bottom: 20px;
}

#wp-site-github-backup-status {
    padding: 15px;
    display: none;
}

.wp-site-github-backup-success {
    background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d;
}

.wp-site-github-backup-error {
    background-color: #f2dede;
    border-color: #ebccd1;
    color: #a94442;
}

.wp-site-github-backup-loading {
    background-color: #fcf8e3;
    border-color: #faebcc;
    color: #8a6d3b;
}';
}

// Get admin JS content
function wp_site_github_backup_get_admin_js_content() {
    return '(function($) {
    \'use strict\';
    
    $(document).ready(function() {
        // Upload to GitHub
        $(\'#wp-site-github-backup-upload\').on(\'click\', function(e) {
            e.preventDefault();
            
            if (!confirm(\'Are you sure you want to backup your WordPress installation to GitHub? This may overwrite existing files in your repository.\')) {
                return;
            }
            
            var statusDiv = $(\'#wp-site-github-backup-status\');
            statusDiv.removeClass(\'wp-site-github-backup-success wp-site-github-backup-error\')
                .addClass(\'wp-site-github-backup-loading\')
                .html(\'<p>Backing up to GitHub... This may take a while depending on the size of your WordPress installation.</p>\')
                .show();
            
            $.ajax({
                url: wpSiteGitHubBackup.ajax_url,
                type: \'POST\',
                data: {
                    action: \'wp_site_github_backup_upload\',
                    nonce: wpSiteGitHubBackup.nonce
                },
                success: function(response) {
                    statusDiv.removeClass(\'wp-site-github-backup-loading\');
                    
                    if (response.success) {
                        statusDiv.addClass(\'wp-site-github-backup-success\')
                            .html(\'<p>\' + response.data.message + \'</p>\');
                    } else {
                        statusDiv.addClass(\'wp-site-github-backup-error\')
                            .html(\'<p>\' + response.data.message + \'</p>\');
                    }
                },
                error: function() {
                    statusDiv.removeClass(\'wp-site-github-backup-loading\')
                        .addClass(\'wp-site-github-backup-error\')
                        .html(\'<p>An error occurred while communicating with the server.</p>\');
                }
            });
        });
        
        // Download from GitHub
        $(\'#wp-site-github-backup-download\').on(\'click\', function(e) {
            e.preventDefault();
            
            if (!confirm(\'Are you sure you want to restore your WordPress installation from GitHub? This will overwrite your current files. A backup will be created, but proceed with caution.\')) {
                return;
            }
            
            var statusDiv = $(\'#wp-site-github-backup-status\');
            statusDiv.removeClass(\'wp-site-github-backup-success wp-site-github-backup-error\')
                .addClass(\'wp-site-github-backup-loading\')
                .html(\'<p>Restoring from GitHub... This may take a while depending on the size of your WordPress installation.</p>\')
                .show();
            
            $.ajax({
                url: wpSiteGitHubBackup.ajax_url,
                type: \'POST\',
                data: {
                    action: \'wp_site_github_backup_download\',
                    nonce: wpSiteGitHubBackup.nonce
                },
                success: function(response) {
                    statusDiv.removeClass(\'wp-site-github-backup-loading\');
                    
                    if (response.success) {
                        statusDiv.addClass(\'wp-site-github-backup-success\')
                            .html(\'<p>\' + response.data.message + \'</p>\');
                    } else {
                        statusDiv.addClass(\'wp-site-github-backup-error\')
                            .html(\'<p>\' + response.data.message + \'</p>\');
                    }
                },
                error: function() {
                    statusDiv.removeClass(\'wp-site-github-backup-loading\')
                        .addClass(\'wp-site-github-backup-error\')
                        .html(\'<p>An error occurred while communicating with the server.</p>\');
                }
            });
        });
    });
})(jQuery);';
}

// Define the main plugin class
class WP_Site_GitHub_Backup {
    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '2.1.0';
    
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;
    
    /**
     * Initialize the plugin.
     */
    private function __construct() {
        // Hook into the admin menu
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        
        // Add settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add AJAX handlers
        add_action('wp_ajax_wp_site_github_backup_upload', array($this, 'ajax_upload_to_github'));
        add_action('wp_ajax_wp_site_github_backup_download', array($this, 'ajax_download_from_github'));
        
        // Add admin assets
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Add settings link on plugin page
        add_filter('plugin_action_links', array($this, 'add_settings_link'), 10, 2);
    }
    
    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    /**
     * Add settings link on plugin page
     */
    public function add_settings_link($links, $file) {
        $plugin_file = 'wp-site-github-backup/wp-site-github-backup.php';
        if ($file == $plugin_file) {
            $settings_link = '<a href="' . admin_url('admin.php?page=wp-site-github-backup') . '">' . __('Settings', 'wp-site-github-backup') . '</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }
    
    /**
     * Register the administration menu for this plugin.
     */
    public function add_plugin_admin_menu() {
        // Add a settings page to the WordPress admin menu
        add_menu_page(
            __('WP Site GitHub Backup', 'wp-site-github-backup'),
            __('GitHub Backup', 'wp-site-github-backup'),
            'manage_options',
            'wp-site-github-backup',
            array($this, 'display_plugin_admin_page'),
            'dashicons-backup',
            81
        );
    }
    
    /**
     * Register settings for the plugin.
     */
    public function register_settings() {
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_github_token');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_github_repo');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_github_username');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_excluded_dirs', array($this, 'sanitize_exclude_dirs'));
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_production_url');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_ftp_host');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_ftp_user');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_ftp_pass');
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_ftp_path');
    }
    
    /**
     * Sanitize the exclusion directories.
     */
    public function sanitize_exclude_dirs($input) {
        $dirs = explode("\n", $input);
        $dirs = array_map('trim', $dirs);
        $dirs = array_filter($dirs);
        return implode("\n", $dirs);
    }
    
    /**
     * Render the settings page for this plugin.
     */
    public function display_plugin_admin_page() {
        include_once('admin/partials/admin-display.php');
    }
    
    /**
     * Enqueue admin scripts and styles.
     */
    public function enqueue_admin_scripts($hook) {
        if ('toplevel_page_wp-site-github-backup' !== $hook) {
            return;
        }
        
        wp_enqueue_style('wp-site-github-backup-admin-css', plugins_url('admin/css/admin.css', __FILE__), array(), self::VERSION);
        wp_enqueue_script('wp-site-github-backup-admin-js', plugins_url('admin/js/admin.js', __FILE__), array('jquery'), self::VERSION, true);
        
        wp_localize_script('wp-site-github-backup-admin-js', 'wpSiteGitHubBackup', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_site_github_backup_nonce'),
        ));
    }
    
    /**
     * AJAX handler for uploading to GitHub.
     */
    public function ajax_upload_to_github() {
        check_ajax_referer('wp_site_github_backup_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'wp-site-github-backup')));
        }
        
        // Get GitHub settings
        $github_token = get_option('wp_site_github_backup_github_token');
        $github_repo = get_option('wp_site_github_backup_github_repo');
        $github_username = get_option('wp_site_github_backup_github_username');
        $exclude_dirs = $this->get_exclude_dirs();
        
        // Validate settings
        if (empty($github_token) || empty($github_repo) || empty($github_username)) {
            wp_send_json_error(array('message' => __('Please configure your GitHub settings.', 'wp-site-github-backup')));
        }
        
        // Create temporary directory
        $temp_dir = get_temp_dir() . 'wp-site-github-backup-' . time();
        if (!mkdir($temp_dir, 0755, true)) {
            wp_send_json_error(array('message' => __('Could not create temporary directory.', 'wp-site-github-backup')));
        }
        
        // Copy WordPress files
        $this->copy_wordpress_files(ABSPATH, $temp_dir, $exclude_dirs);
        
        // Create or update GitHub repository
        $result = $this->push_to_github($temp_dir, $github_username, $github_repo, $github_token);
        
        // Clean up
        $this->recursive_rmdir($temp_dir);
        
        if ($result['success']) {
            wp_send_json_success(array('message' => $result['message']));
        } else {
            wp_send_json_error(array('message' => $result['message']));
        }
    }
    
    /**
     * AJAX handler for downloading from GitHub.
     */
    public function ajax_download_from_github() {
        check_ajax_referer('wp_site_github_backup_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'wp-site-github-backup')));
        }
        
        // Get GitHub settings
        $github_token = get_option('wp_site_github_backup_github_token');
        $github_repo = get_option('wp_site_github_backup_github_repo');
        $github_username = get_option('wp_site_github_backup_github_username');
        
        // Validate settings
        if (empty($github_token) || empty($github_repo) || empty($github_username)) {
            wp_send_json_error(array('message' => __('Please configure your GitHub settings.', 'wp-site-github-backup')));
        }
        
        // Clone repository to temporary directory
        $temp_dir = get_temp_dir() . 'wp-site-github-backup-' . time();
        $result = $this->clone_github_repo($github_username, $github_repo, $github_token, $temp_dir);
        
        if (!$result['success']) {
            wp_send_json_error(array('message' => $result['message']));
        }
        
        // Backup current WordPress files
        $backup_dir = WP_CONTENT_DIR . '/backups/wp-site-github-backup-' . date('Y-m-d-H-i-s');
        if (!mkdir($backup_dir, 0755, true)) {
            // Clean up temp directory
            $this->recursive_rmdir($temp_dir);
            wp_send_json_error(array('message' => __('Could not create backup directory.', 'wp-site-github-backup')));
        }
        
        // Get list of files to exclude during backup
        $exclude_dirs = $this->get_exclude_dirs();
        
        // Backup current files
        $this->copy_wordpress_files(ABSPATH, $backup_dir, $exclude_dirs);
        
        // Replace current files with GitHub files
        $this->copy_directory_contents($temp_dir, ABSPATH);
        
        // Clean up
        $this->recursive_rmdir($temp_dir);
        
        wp_send_json_success(array(
            'message' => sprintf(
                __('WordPress installation successfully updated from GitHub. A backup was created in %s.', 'wp-site-github-backup'),
                $backup_dir
            )
        ));
    }
    
    /**
     * Get list of directories to exclude.
     */
    private function get_exclude_dirs() {
        $exclude_dirs_raw = get_option('wp_site_github_backup_excluded_dirs', "wp-content/uploads\nwp-content/cache\nwp-content/upgrade\nwp-content/backup*\n.git");
        $exclude_dirs = explode("\n", $exclude_dirs_raw);
        $exclude_dirs = array_map('trim', $exclude_dirs);
        return array_filter($exclude_dirs);
    }
    
    /**
     * Copy WordPress files to a directory.
     */
    private function copy_wordpress_files($source, $destination, $exclude_dirs = array()) {
        $source = rtrim($source, '/') . '/';
        $destination = rtrim($destination, '/') . '/';
        
        // Create destination directory if it doesn't exist
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        // Get list of files and directories in the source
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            // Get the path relative to the WordPress root
            $relative_path = str_replace($source, '', $item->getPathname());
            
            // Check if the path should be excluded
            $should_exclude = false;
            foreach ($exclude_dirs as $exclude_dir) {
                if (strpos($relative_path, $exclude_dir) === 0) {
                    $should_exclude = true;
                    break;
                }
            }
            
            if ($should_exclude) {
                continue;
            }
            
            $dest_path = $destination . $relative_path;
            
            if ($item->isDir()) {
                if (!is_dir($dest_path)) {
                    mkdir($dest_path, 0755, true);
                }
            } else {
                copy($item->getPathname(), $dest_path);
            }
        }
    }
    
    /**
     * Copy directory contents from source to destination.
     */
    private function copy_directory_contents($source, $destination) {
        $source = rtrim($source, '/') . '/';
        $destination = rtrim($destination, '/') . '/';
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $relative_path = str_replace($source, '', $item->getPathname());
            $dest_path = $destination . $relative_path;
            
            if ($item->isDir()) {
                if (!is_dir($dest_path)) {
                    mkdir($dest_path, 0755, true);
                }
            } else {
                copy($item->getPathname(), $dest_path);
            }
        }
    }
    
    /**
     * Push directory contents to GitHub repository.
     */
    private function push_to_github($dir, $username, $repo, $token) {
        // Check if git is installed
        exec('git --version', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Git is not installed on the server.', 'wp-site-github-backup')
            );
        }
        
        // Initialize git repository
        chdir($dir);
        exec('git init', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Failed to initialize git repository.', 'wp-site-github-backup')
            );
        }
        
        // Add all files
        exec('git add .', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Failed to add files to git repository.', 'wp-site-github-backup')
            );
        }
        
        // Configure git user
        exec('git config user.email "wordpress@example.com"', $output, $return_var);
        exec('git config user.name "WordPress GitHub Backup"', $output, $return_var);
        
        // Create date variables for the commit message and tag
        $date_ymd = date('Y-m-d');
        $date_full = date('Y-m-d-H-i-s');
        $commit_message = 'WordPress Backup - ' . $date_ymd;
        $tag_name = 'backup-' . $date_full;
        
        // Commit changes
        exec('git commit -m "' . $commit_message . '"', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Failed to commit files. This could happen if there are no changes to commit.', 'wp-site-github-backup')
            );
        }
        
        // Create a tag with the current date and time
        exec('git tag -a "' . $tag_name . '" -m "Automatic backup - ' . $date_full . '"', $output, $return_var);
        if ($return_var !== 0) {
            // Non-critical error, continue with the push
            error_log('Failed to create git tag: ' . print_r($output, true));
        }
        
        // Add remote repository
        $repo_url = 'https://' . $username . ':' . $token . '@github.com/' . $username . '/' . $repo . '.git';
        exec('git remote add origin ' . $repo_url, $output, $return_var);
        
        // Push to repository (force push to overwrite any existing content)
        exec('git push -f origin master', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Failed to push to GitHub repository. Please check your credentials and repository name.', 'wp-site-github-backup')
            );
        }
        
        // Push tags to the repository
        exec('git push -f origin --tags', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => true, // Still mark as success since the main push worked
                'message' => __('Successfully pushed WordPress files to GitHub repository, but failed to push tags.', 'wp-site-github-backup')
            );
        }
        
        return array(
            'success' => true,
            'message' => sprintf(
                __('Successfully pushed WordPress files to GitHub repository with tag: %s', 'wp-site-github-backup'),
                $tag_name
            )
        );
    }
    
    /**
     * Clone GitHub repository to a local directory.
     */
    private function clone_github_repo($username, $repo, $token, $dir) {
        // Check if git is installed
        exec('git --version', $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Git is not installed on the server.', 'wp-site-github-backup')
            );
        }
        
        // Clone repository
        $repo_url = 'https://' . $username . ':' . $token . '@github.com/' . $username . '/' . $repo . '.git';
        exec('git clone ' . $repo_url . ' ' . $dir, $output, $return_var);
        if ($return_var !== 0) {
            return array(
                'success' => false,
                'message' => __('Failed to clone GitHub repository. Please check your credentials and repository name.', 'wp-site-github-backup')
            );
        }
        
        return array(
            'success' => true,
            'message' => __('Successfully cloned GitHub repository.', 'wp-site-github-backup')
        );
    }
    
    /**
     * Recursively remove a directory.
     */
    private function recursive_rmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->recursive_rmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}

// Register activation hook
register_activation_hook(__FILE__, 'wp_site_github_backup_activate');

// Initialize the plugin
add_action('plugins_loaded', array('WP_Site_GitHub_Backup', 'get_instance'));

// Add update from GitHub functionality
function wp_site_github_backup_check_for_updates() {
    // Check if we need to update from GitHub
    if (isset($_POST['wp_site_github_backup_update_from_github']) && current_user_can('manage_options')) {
        // Verify nonce
        if (!isset($_POST['wp_site_github_backup_update_nonce']) || 
            !wp_verify_nonce($_POST['wp_site_github_backup_update_nonce'], 'wp_site_github_backup_update')) {
            wp_die('Security check failed');
        }
        
        // Get the latest release from GitHub
        $response = wp_remote_get('https://api.github.com/repos/' . WP_SITE_GITHUB_BACKUP_GITHUB_REPO . '/releases/latest');
        
        if (is_wp_error($response)) {
            add_action('admin_notices', 'wp_site_github_backup_update_error_notice');
            return;
        }
        
        $body = wp_remote_retrieve_body($response);
        $release = json_decode($body);
        
        if (!$release || !isset($release->zipball_url)) {
            add_action('admin_notices', 'wp_site_github_backup_update_error_notice');
            return;
        }
        
        // Download the latest release
        $download_response = wp_remote_get($release->zipball_url, array(
            'timeout' => 300,
            'stream' => true,
            'filename' => WP_CONTENT_DIR . '/upgrade/wp-site-github-backup.zip'
        ));
        
        if (is_wp_error($download_response)) {
            add_action('admin_notices', 'wp_site_github_backup_update_error_notice');
            return;
        }
        
        // Extract the downloaded file
        global $wp_filesystem;
        if (!$wp_filesystem) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }
        
        $unzip_result = unzip_file(
            WP_CONTENT_DIR . '/upgrade/wp-site-github-backup.zip',
            WP_CONTENT_DIR . '/upgrade/wp-site-github-backup-temp'
        );
        
        if (is_wp_error($unzip_result)) {
            add_action('admin_notices', 'wp_site_github_backup_update_error_notice');
            return;
        }
        
        // Find the extracted directory (it has a random name)
        $extracted_dirs = glob(WP_CONTENT_DIR . '/upgrade/wp-site-github-backup-temp/*', GLOB_ONLYDIR);
        if (empty($extracted_dirs)) {
            add_action('admin_notices', 'wp_site_github_backup_update_error_notice');
            return;
        }
        
        $extracted_dir = $extracted_dirs[0];
        
        // Copy files to plugin directory
        $copy_result = copy_dir(
            $extracted_dir,
            WP_SITE_GITHUB_BACKUP_PLUGIN_DIR
        );
        
        if (is_wp_error($copy_result)) {
            add_action('admin_notices', 'wp_site_github_backup_update_error_notice');
            return;
        }
        
        // Clean up
        $wp_filesystem->delete(WP_CONTENT_DIR . '/upgrade/wp-site-github-backup.zip');
        $wp_filesystem->delete(WP_CONTENT_DIR . '/upgrade/wp-site-github-backup-temp', true);
        
        // Success notice
        add_action('admin_notices', 'wp_site_github_backup_update_success_notice');
    }
}
add_action('admin_init', 'wp_site_github_backup_check_for_updates');

// Error notice for update
function wp_site_github_backup_update_error_notice() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Error updating plugin from GitHub. Please try again or update manually.', 'wp-site-github-backup'); ?></p>
    </div>
    <?php
}

// Success notice for update
function wp_site_github_backup_update_success_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Plugin successfully updated from GitHub!', 'wp-site-github-backup'); ?></p>
    </div>
    <?php
}

/**
 * Get the latest version from GitHub
 */
function wp_site_github_backup_get_github_version() {
    // Check if we have a cached version
    $github_version = get_transient('wp_site_github_backup_github_version');
    
    // If we need to refresh or don't have a cached version
    if (isset($_GET['refresh_versions']) || false === $github_version) {
        // Get the latest release from GitHub
        $response = wp_remote_get('https://api.github.com/repos/' . WP_SITE_GITHUB_BACKUP_GITHUB_REPO . '/releases/latest');
        
        if (is_wp_error($response)) {
            return 'Unknown';
        }
        
        $body = wp_remote_retrieve_body($response);
        $release = json_decode($body);
        
        if (!$release || !isset($release->tag_name)) {
            return 'Unknown';
        }
        
        // Clean up version number (remove 'v' prefix if present)
        $github_version = ltrim($release->tag_name, 'v');
        
        // Cache the version for 1 hour
        set_transient('wp_site_github_backup_github_version', $github_version, HOUR_IN_SECONDS);
    }
    
    return $github_version;
}

/**
 * Get the version from the production site
 */
function wp_site_github_backup_get_production_version() {
    // Check if we have a cached version
    $production_version = get_transient('wp_site_github_backup_production_version');
    
    // If we need to refresh or don't have a cached version
    if (isset($_GET['refresh_versions']) || false === $production_version) {
        $production_url = get_option('wp_site_github_backup_production_url');
        
        if (empty($production_url)) {
            return 'Not configured';
        }
        
        // Add trailing slash if not present
        $production_url = trailingslashit($production_url);
        
        // Get the version from the production site
        $response = wp_remote_get($production_url . 'wp-content/plugins/wp-site-github-backup/version.php');
        
        if (is_wp_error($response)) {
            return 'Unknown';
        }
        
        $body = wp_remote_retrieve_body($response);
        
        // Parse the version from the response
        if (preg_match('/Version:\s*([0-9.]+)/', $body, $matches)) {
            $production_version = $matches[1];
        } else {
            $production_version = 'Unknown';
        }
        
        // Cache the version for 1 hour
        set_transient('wp_site_github_backup_production_version', $production_version, HOUR_IN_SECONDS);
    }
    
    return $production_version;
}

/**
 * Handle updating GitHub from local
 */
function wp_site_github_backup_handle_update_github() {
    if (isset($_POST['wp_site_github_backup_update_github']) && current_user_can('manage_options')) {
        // Verify nonce
        if (!isset($_POST['wp_site_github_backup_update_github_nonce']) || 
            !wp_verify_nonce($_POST['wp_site_github_backup_update_github_nonce'], 'wp_site_github_backup_update_github')) {
            wp_die('Security check failed');
        }
        
        // Get GitHub settings
        $username = get_option('wp_site_github_backup_github_username');
        $repo = get_option('wp_site_github_backup_github_repo');
        $token = get_option('wp_site_github_backup_github_token');
        
        if (empty($username) || empty($repo) || empty($token)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('GitHub settings are not configured. Please configure them first.', 'wp-site-github-backup') . '</p></div>';
            });
            return;
        }
        
        // Create a temporary directory
        $temp_dir = WP_CONTENT_DIR . '/upgrade/wp-site-github-backup-temp';
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir, 0755, true);
        }
        
        // Clone the repository
        $result = wp_site_github_backup_clone_github_repo($username, $repo, $token, $temp_dir);
        
        if (is_wp_error($result)) {
            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Error cloning GitHub repository: ', 'wp-site-github-backup') . esc_html($result->get_error_message()) . '</p></div>';
            });
            return;
        }
        
        // Copy plugin files to the repository
        $plugin_dir = WP_SITE_GITHUB_BACKUP_PLUGIN_DIR;
        $exclude_dirs = array('.git', '.github');
        
        $result = wp_site_github_backup_copy_files($plugin_dir, $temp_dir, $exclude_dirs);
        
        if (is_wp_error($result)) {
            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Error copying files: ', 'wp-site-github-backup') . esc_html($result->get_error_message()) . '</p></div>';
            });
            return;
        }
        
        // Commit and push changes
        $result = wp_site_github_backup_commit_and_push($temp_dir, 'Update to version ' . WP_SITE_GITHUB_BACKUP_VERSION);
        
        if (is_wp_error($result)) {
            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Error pushing to GitHub: ', 'wp-site-github-backup') . esc_html($result->get_error_message()) . '</p></div>';
            });
            return;
        }
        
        // Create a new tag
        $result = wp_site_github_backup_create_tag($temp_dir, WP_SITE_GITHUB_BACKUP_VERSION, 'Version ' . WP_SITE_GITHUB_BACKUP_VERSION . ' release');
        
        if (is_wp_error($result)) {
            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Error creating tag: ', 'wp-site-github-backup') . esc_html($result->get_error_message()) . '</p></div>';
            });
            return;
        }
        
        // Clean up
        wp_site_github_backup_recursive_rmdir($temp_dir);
        
        // Clear the cached GitHub version
        delete_transient('wp_site_github_backup_github_version');
        
        // Success notice
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('GitHub repository updated successfully!', 'wp-site-github-backup') . '</p></div>';
        });
    }
}
add_action('admin_init', 'wp_site_github_backup_handle_update_github');

/**
 * Handle updating production from local
 */
function wp_site_github_backup_handle_update_production() {
    if (isset($_POST['wp_site_github_backup_update_production']) && current_user_can('manage_options')) {
        // Verify nonce
        if (!isset($_POST['wp_site_github_backup_update_production_nonce']) || 
            !wp_verify_nonce($_POST['wp_site_github_backup_update_production_nonce'], 'wp_site_github_backup_update_production')) {
            wp_die('Security check failed');
        }
        
        // Get FTP settings
        $ftp_host = get_option('wp_site_github_backup_ftp_host');
        $ftp_user = get_option('wp_site_github_backup_ftp_user');
        $ftp_pass = get_option('wp_site_github_backup_ftp_pass');
        $ftp_path = get_option('wp_site_github_backup_ftp_path');
        
        if (empty($ftp_host) || empty($ftp_user) || empty($ftp_pass) || empty($ftp_path)) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('FTP settings are not configured. Please configure them first.', 'wp-site-github-backup') . '</p></div>';
            });
            return;
        }
        
        // Create a version.php file
        $version_file = WP_SITE_GITHUB_BACKUP_PLUGIN_DIR . 'version.php';
        $version_content = "<?php\n// Version information for WP Site GitHub Backup\necho 'Version: " . WP_SITE_GITHUB_BACKUP_VERSION . "';\n";
        file_put_contents($version_file, $version_content);
        
        // Upload files to production
        $result = wp_site_github_backup_upload_to_production($ftp_host, $ftp_user, $ftp_pass, $ftp_path);
        
        if (is_wp_error($result)) {
            add_action('admin_notices', function() use ($result) {
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Error uploading to production: ', 'wp-site-github-backup') . esc_html($result->get_error_message()) . '</p></div>';
            });
            return;
        }
        
        // Clear the cached production version
        delete_transient('wp_site_github_backup_production_version');
        
        // Success notice
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Production site updated successfully!', 'wp-site-github-backup') . '</p></div>';
        });
    }
}
add_action('admin_init', 'wp_site_github_backup_handle_update_production');

/**
 * Helper function to clone a GitHub repository
 */
function wp_site_github_backup_clone_github_repo($username, $repo, $token, $dir) {
    // Clean up directory if it exists
    if (is_dir($dir)) {
        wp_site_github_backup_recursive_rmdir($dir);
    }
    
    // Create directory
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Clone repository
    $repo_url = "https://{$token}@github.com/{$username}/{$repo}.git";
    $command = "git clone {$repo_url} {$dir} 2>&1";
    
    exec($command, $output, $return_var);
    
    if ($return_var !== 0) {
        return new WP_Error('git_clone_failed', implode("\n", $output));
    }
    
    return true;
}

/**
 * Helper function to copy files
 */
function wp_site_github_backup_copy_files($source, $destination, $exclude_dirs = array()) {
    if (!is_dir($source)) {
        return new WP_Error('source_not_dir', 'Source is not a directory');
    }
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $dir = opendir($source);
    
    while (($file = readdir($dir)) !== false) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        
        // Skip excluded directories
        if (in_array($file, $exclude_dirs)) {
            continue;
        }
        
        $src_file = $source . '/' . $file;
        $dst_file = $destination . '/' . $file;
        
        if (is_dir($src_file)) {
            wp_site_github_backup_copy_files($src_file, $dst_file, $exclude_dirs);
        } else {
            copy($src_file, $dst_file);
        }
    }
    
    closedir($dir);
    
    return true;
}

/**
 * Helper function to commit and push changes
 */
function wp_site_github_backup_commit_and_push($dir, $message) {
    // Add all files
    $command = "cd {$dir} && git add . 2>&1";
    exec($command, $output, $return_var);
    
    if ($return_var !== 0) {
        return new WP_Error('git_add_failed', implode("\n", $output));
    }
    
    // Commit changes
    $command = "cd {$dir} && git commit -m \"{$message}\" 2>&1";
    exec($command, $output, $return_var);
    
    // It's okay if there's nothing to commit
    if ($return_var !== 0 && !strpos(implode("\n", $output), 'nothing to commit')) {
        return new WP_Error('git_commit_failed', implode("\n", $output));
    }
    
    // Push changes
    $command = "cd {$dir} && git push origin main 2>&1";
    exec($command, $output, $return_var);
    
    if ($return_var !== 0) {
        return new WP_Error('git_push_failed', implode("\n", $output));
    }
    
    return true;
}

/**
 * Helper function to create a tag
 */
function wp_site_github_backup_create_tag($dir, $tag, $message) {
    // Create tag
    $command = "cd {$dir} && git tag -a {$tag} -m \"{$message}\" 2>&1";
    exec($command, $output, $return_var);
    
    if ($return_var !== 0) {
        return new WP_Error('git_tag_failed', implode("\n", $output));
    }
    
    // Push tag
    $command = "cd {$dir} && git push origin {$tag} 2>&1";
    exec($command, $output, $return_var);
    
    if ($return_var !== 0) {
        return new WP_Error('git_push_tag_failed', implode("\n", $output));
    }
    
    return true;
}

/**
 * Helper function to upload files to production
 */
function wp_site_github_backup_upload_to_production($host, $user, $pass, $path) {
    // Create a temporary script
    $temp_script = WP_CONTENT_DIR . '/upgrade/upload-to-production.sh';
    $script_content = "#!/bin/bash\n\n";
    $script_content .= "# FTP Configuration\n";
    $script_content .= "FTP_HOST=\"{$host}\"\n";
    $script_content .= "FTP_USER=\"{$user}\"\n";
    $script_content .= "FTP_PASS=\"{$pass}\"\n";
    $script_content .= "REMOTE_PATH=\"{$path}\"\n";
    $script_content .= "LOCAL_PATH=\"" . WP_SITE_GITHUB_BACKUP_PLUGIN_DIR . "\"\n\n";
    $script_content .= "# Upload files\n";
    $script_content .= "curl -T \"{$LOCAL_PATH}/wp-site-github-backup.php\" -u \"{$FTP_USER}:{$FTP_PASS}\" \"ftp://{$FTP_HOST}{$REMOTE_PATH}/wp-site-github-backup.php\"\n";
    $script_content .= "curl -T \"{$LOCAL_PATH}/README.md\" -u \"{$FTP_USER}:{$FTP_PASS}\" \"ftp://{$FTP_HOST}{$REMOTE_PATH}/README.md\"\n";
    $script_content .= "curl -T \"{$LOCAL_PATH}/version.php\" -u \"{$FTP_USER}:{$FTP_PASS}\" \"ftp://{$FTP_HOST}{$REMOTE_PATH}/version.php\"\n";
    
    file_put_contents($temp_script, $script_content);
    chmod($temp_script, 0755);
    
    // Execute the script
    $command = "{$temp_script} 2>&1";
    exec($command, $output, $return_var);
    
    // Clean up
    unlink($temp_script);
    
    if ($return_var !== 0) {
        return new WP_Error('upload_failed', implode("\n", $output));
    }
    
    return true;
}
