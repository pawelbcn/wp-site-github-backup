<?php
/**
 * Plugin Name: WP Site GitHub Backup
 * Plugin URI: https://example.com/wp-site-github-backup
 * Description: Backup and restore your entire WordPress installation to/from a GitHub repository
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: wp-site-github-backup
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

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
                        <p class="description">
                            <?php _e(\'You need to create a Personal Access Token with \\\'repo\\\' permissions. <a href="https://github.com/settings/tokens" target="_blank">Create one here</a>.\', \'wp-site-github-backup\'); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_exclude_dirs"><?php _e(\'Excluded Directories (one per line)\', \'wp-site-github-backup\'); ?></label>
                    </th>
                    <td>
                        <textarea id="wp_site_github_backup_exclude_dirs" name="wp_site_github_backup_exclude_dirs" rows="5" cols="50"><?php echo esc_textarea(get_option(\'wp_site_github_backup_exclude_dirs\', ".git\n.github\nwp-content/uploads\nwp-content/backups")); ?></textarea>
                        <p class="description">
                            <?php _e(\'These directories will be excluded from the backup process.\', \'wp-site-github-backup\'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__(\'Save Settings\', \'wp-site-github-backup\')); ?>
        </form>
    </div>
    
    <div class="wp-site-github-backup-actions">
        <h2><?php _e(\'Backup & Restore Actions\', \'wp-site-github-backup\'); ?></h2>
        
        <div class="wp-site-github-backup-buttons">
            <button id="wp-site-github-backup-upload" class="button button-primary"><?php _e(\'Backup to GitHub\', \'wp-site-github-backup\'); ?></button>
            <button id="wp-site-github-backup-download" class="button button-secondary"><?php _e(\'Restore from GitHub\', \'wp-site-github-backup\'); ?></button>
        </div>
        
        <div id="wp-site-github-backup-status"></div>
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
    const VERSION = '1.0.0';
    
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
        register_setting('wp_site_github_backup_settings', 'wp_site_github_backup_exclude_dirs', array($this, 'sanitize_exclude_dirs'));
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
        $exclude_dirs_raw = get_option('wp_site_github_backup_exclude_dirs', ".git\n.github\nwp-content/uploads\nwp-content/backups");
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
