<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Get version information
$local_version = WP_SITE_GITHUB_BACKUP_VERSION;
$github_version = wp_site_github_backup_get_github_version();
$production_version = wp_site_github_backup_get_production_version();
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="notice notice-info">
        <p><?php _e('This plugin allows you to backup and restore your WordPress installation with a GitHub repository.', 'wp-site-github-backup'); ?></p>
    </div>
    
    <div class="wp-site-github-backup-versions">
        <h2><?php _e('Version Information', 'wp-site-github-backup'); ?> <a href="<?php echo esc_url(add_query_arg('refresh_versions', '1')); ?>" class="page-title-action" title="<?php _e('Refresh Version Information', 'wp-site-github-backup'); ?>"><span class="dashicons dashicons-update"></span></a></h2>
        
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('Environment', 'wp-site-github-backup'); ?></th>
                    <th><?php _e('Version', 'wp-site-github-backup'); ?></th>
                    <th><?php _e('Status', 'wp-site-github-backup'); ?></th>
                    <th><?php _e('Actions', 'wp-site-github-backup'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?php _e('Local', 'wp-site-github-backup'); ?></strong></td>
                    <td><?php echo esc_html($local_version); ?></td>
                    <td>
                        <?php if (version_compare($local_version, $github_version, '==') && version_compare($local_version, $production_version, '==')): ?>
                            <span class="wp-site-github-backup-status-current"><?php _e('Current', 'wp-site-github-backup'); ?></span>
                        <?php elseif (version_compare($local_version, $github_version, '>') || version_compare($local_version, $production_version, '>')): ?>
                            <span class="wp-site-github-backup-status-ahead"><?php _e('Ahead', 'wp-site-github-backup'); ?></span>
                        <?php else: ?>
                            <span class="wp-site-github-backup-status-behind"><?php _e('Behind', 'wp-site-github-backup'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (version_compare($local_version, $github_version, '>')): ?>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('wp_site_github_backup_update_github', 'wp_site_github_backup_update_github_nonce'); ?>
                                <button type="submit" name="wp_site_github_backup_update_github" class="button button-secondary"><?php _e('Update GitHub', 'wp-site-github-backup'); ?></button>
                            </form>
                        <?php endif; ?>
                        
                        <?php if (version_compare($local_version, $production_version, '>')): ?>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('wp_site_github_backup_update_production', 'wp_site_github_backup_update_production_nonce'); ?>
                                <button type="submit" name="wp_site_github_backup_update_production" class="button button-secondary"><?php _e('Update Production', 'wp-site-github-backup'); ?></button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php _e('GitHub', 'wp-site-github-backup'); ?></strong></td>
                    <td><?php echo esc_html($github_version); ?></td>
                    <td>
                        <?php if (version_compare($github_version, $local_version, '==') && version_compare($github_version, $production_version, '==')): ?>
                            <span class="wp-site-github-backup-status-current"><?php _e('Current', 'wp-site-github-backup'); ?></span>
                        <?php elseif (version_compare($github_version, $local_version, '>') || version_compare($github_version, $production_version, '>')): ?>
                            <span class="wp-site-github-backup-status-ahead"><?php _e('Ahead', 'wp-site-github-backup'); ?></span>
                        <?php else: ?>
                            <span class="wp-site-github-backup-status-behind"><?php _e('Behind', 'wp-site-github-backup'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (version_compare($github_version, $local_version, '>')): ?>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('wp_site_github_backup_update_local', 'wp_site_github_backup_update_local_nonce'); ?>
                                <button type="submit" name="wp_site_github_backup_update_local" class="button button-secondary"><?php _e('Update Local', 'wp-site-github-backup'); ?></button>
                            </form>
                        <?php endif; ?>
                        
                        <?php if (version_compare($github_version, $production_version, '>')): ?>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('wp_site_github_backup_update_production_from_github', 'wp_site_github_backup_update_production_from_github_nonce'); ?>
                                <button type="submit" name="wp_site_github_backup_update_production_from_github" class="button button-secondary"><?php _e('Update Production', 'wp-site-github-backup'); ?></button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php _e('Production', 'wp-site-github-backup'); ?></strong></td>
                    <td><?php echo esc_html($production_version); ?></td>
                    <td>
                        <?php if (version_compare($production_version, $local_version, '==') && version_compare($production_version, $github_version, '==')): ?>
                            <span class="wp-site-github-backup-status-current"><?php _e('Current', 'wp-site-github-backup'); ?></span>
                        <?php elseif (version_compare($production_version, $local_version, '>') || version_compare($production_version, $github_version, '>')): ?>
                            <span class="wp-site-github-backup-status-ahead"><?php _e('Ahead', 'wp-site-github-backup'); ?></span>
                        <?php else: ?>
                            <span class="wp-site-github-backup-status-behind"><?php _e('Behind', 'wp-site-github-backup'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (version_compare($production_version, $local_version, '>')): ?>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('wp_site_github_backup_update_local_from_production', 'wp_site_github_backup_update_local_from_production_nonce'); ?>
                                <button type="submit" name="wp_site_github_backup_update_local_from_production" class="button button-secondary"><?php _e('Update Local', 'wp-site-github-backup'); ?></button>
                            </form>
                        <?php endif; ?>
                        
                        <?php if (version_compare($production_version, $github_version, '>')): ?>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('wp_site_github_backup_update_github_from_production', 'wp_site_github_backup_update_github_from_production_nonce'); ?>
                                <button type="submit" name="wp_site_github_backup_update_github_from_production" class="button button-secondary"><?php _e('Update GitHub', 'wp-site-github-backup'); ?></button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="wp-site-github-backup-settings">
        <h2><?php _e('GitHub Settings', 'wp-site-github-backup'); ?></h2>
        
        <form method="post" action="options.php">
            <?php settings_fields('wp_site_github_backup_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_github_username"><?php _e('GitHub Username', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_github_username" name="wp_site_github_backup_github_username" value="<?php echo esc_attr(get_option('wp_site_github_backup_github_username')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_github_repo"><?php _e('GitHub Repository Name', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_github_repo" name="wp_site_github_backup_github_repo" value="<?php echo esc_attr(get_option('wp_site_github_backup_github_repo')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_github_token"><?php _e('GitHub Personal Access Token', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="wp_site_github_backup_github_token" name="wp_site_github_backup_github_token" value="<?php echo esc_attr(get_option('wp_site_github_backup_github_token')); ?>" class="regular-text">
                        <p class="description"><?php _e('Create a token with \'repo\' permissions at GitHub Settings → Developer Settings → Personal Access Tokens', 'wp-site-github-backup'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_production_url"><?php _e('Production Site URL', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_production_url" name="wp_site_github_backup_production_url" value="<?php echo esc_attr(get_option('wp_site_github_backup_production_url')); ?>" class="regular-text">
                        <p class="description"><?php _e('URL of your production site (e.g., https://example.com)', 'wp-site-github-backup'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_ftp_host"><?php _e('FTP Host', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_ftp_host" name="wp_site_github_backup_ftp_host" value="<?php echo esc_attr(get_option('wp_site_github_backup_ftp_host')); ?>" class="regular-text">
                        <p class="description"><?php _e('FTP server hostname (e.g., ftp.example.com)', 'wp-site-github-backup'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_ftp_user"><?php _e('FTP Username', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_ftp_user" name="wp_site_github_backup_ftp_user" value="<?php echo esc_attr(get_option('wp_site_github_backup_ftp_user')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_ftp_pass"><?php _e('FTP Password', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="wp_site_github_backup_ftp_pass" name="wp_site_github_backup_ftp_pass" value="<?php echo esc_attr(get_option('wp_site_github_backup_ftp_pass')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_ftp_path"><?php _e('FTP Path', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="wp_site_github_backup_ftp_path" name="wp_site_github_backup_ftp_path" value="<?php echo esc_attr(get_option('wp_site_github_backup_ftp_path', '/')); ?>" class="regular-text">
                        <p class="description"><?php _e('Path to the plugin directory on the FTP server (e.g., /public_html/wp-content/plugins/wp-site-github-backup)', 'wp-site-github-backup'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wp_site_github_backup_excluded_dirs"><?php _e('Excluded Directories', 'wp-site-github-backup'); ?></label>
                    </th>
                    <td>
                        <textarea id="wp_site_github_backup_excluded_dirs" name="wp_site_github_backup_excluded_dirs" rows="5" class="large-text"><?php echo esc_textarea(get_option('wp_site_github_backup_excluded_dirs', "wp-content/uploads\nwp-content/cache\nwp-content/upgrade\nwp-content/backup*\n.git")); ?></textarea>
                        <p class="description"><?php _e('Enter one directory per line, relative to WordPress root', 'wp-site-github-backup'); ?></p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    
    <div class="wp-site-github-backup-actions">
        <h2><?php _e('Backup and Restore', 'wp-site-github-backup'); ?></h2>
        
        <p><?php _e('Use the buttons below to backup your WordPress installation to GitHub or restore from GitHub.', 'wp-site-github-backup'); ?></p>
        
        <div class="wp-site-github-backup-buttons">
            <button id="wp-site-github-backup-upload" class="button button-primary"><?php _e('Backup to GitHub', 'wp-site-github-backup'); ?></button>
            <button id="wp-site-github-backup-download" class="button"><?php _e('Restore from GitHub', 'wp-site-github-backup'); ?></button>
        </div>
        
        <div id="wp-site-github-backup-progress" style="display: none;">
            <h3><?php _e('Progress', 'wp-site-github-backup'); ?></h3>
            <div class="wp-site-github-backup-progress-bar">
                <div class="wp-site-github-backup-progress-bar-inner"></div>
            </div>
            <div class="wp-site-github-backup-progress-status"></div>
        </div>
        
        <div id="wp-site-github-backup-log" style="display: none;">
            <h3><?php _e('Log', 'wp-site-github-backup'); ?></h3>
            <div class="wp-site-github-backup-log-content"></div>
        </div>
    </div>
    
    <div class="wp-site-github-backup-update">
        <h2><?php _e('Plugin Updates', 'wp-site-github-backup'); ?></h2>
        
        <p><?php _e('Update this plugin directly from GitHub to get the latest features and bug fixes.', 'wp-site-github-backup'); ?></p>
        
        <form method="post" action="">
            <?php wp_nonce_field('wp_site_github_backup_update', 'wp_site_github_backup_update_nonce'); ?>
            <button type="submit" name="wp_site_github_backup_update_from_github" class="button button-secondary"><?php _e('Update Plugin from GitHub', 'wp-site-github-backup'); ?></button>
        </form>
    </div>
</div> 