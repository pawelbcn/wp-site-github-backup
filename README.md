# wp-site-github-backup
Wp Site Github Backup plugin for Wordpress

# WP Site GitHub Backup

A WordPress plugin that lets you backup and restore your entire WordPress installation to/from a GitHub repository.

## Description

WP Site GitHub Backup provides a simple way to version control your entire WordPress site using GitHub. With a single click, you can backup your WordPress files to a GitHub repository, or restore your site from a previously created backup.

## Features

- **One-Click Backup**: Backup your entire WordPress installation to a GitHub repository
- **Automatic Version Tagging**: Each backup is automatically tagged with the current date and time
- **Detailed Progress Tracking**: See real-time progress of your backup/restore operations
- **Comprehensive Logs**: Review detailed logs of all operations for troubleshooting
- **Excluded Directories**: Configure which directories to exclude from backups
- **Secure Authentication**: Uses GitHub Personal Access Tokens for secure repository access
- **Backup Before Restore**: Automatically creates a local backup before restoring from GitHub
- **Production Updates**: Easily upload changes from your production site to GitHub using the included script
- **Self-Update**: Update the plugin directly from GitHub with one click

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Git installed on your web server
- GitHub account and repository
- GitHub Personal Access Token with 'repo' permissions

## Installation

1. Upload the `wp-site-github-backup` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the 'GitHub Backup' menu in your WordPress admin
4. Configure your GitHub credentials and repository settings

## Configuration

After installation, you need to configure the following settings:

1. **GitHub Username**: Your GitHub username
2. **GitHub Repository Name**: The name of the repository to use for backups
3. **GitHub Personal Access Token**: A token with 'repo' permissions
4. **Excluded Directories**: Directories to exclude from backup (defaults provided)

## Usage

### Backing Up Your WordPress Installation

1. Go to the 'GitHub Backup' menu in your WordPress admin
2. Click the 'Backup to GitHub' button
3. Monitor the progress and wait for completion
4. Check the detailed log for any issues

### Restoring Your WordPress Installation

1. Go to the 'GitHub Backup' menu in your WordPress admin
2. Click the 'Restore from GitHub' button
3. Monitor the progress and wait for completion
4. A backup of your current installation will be automatically created before restoring

### Uploading Production Site Changes

1. When you have new code on your production site that you want to back up:
2. Run the included script from the repository root:
   ```
   ./upload-production-changes.sh /path/to/production/site "Description of changes"
   ```
3. The script will copy files from your production site to the repository, commit the changes, and push to GitHub
4. All production files will be stored in the `production-updates` directory

## Changelog

### Version 2.0.0
- Added "Update from GitHub" button to update the plugin directly from the admin panel
- Added self-update functionality to keep the plugin up to date
- Improved UI with better progress tracking and logging
- Added production updates feature with script to upload changes from production site
- Created dedicated directory for production site files
- Added documentation for the production updates workflow

### Version 1.2.0
- Added production updates feature with script to upload changes from production site
- Created dedicated directory for production site files
- Added documentation for the production updates workflow

### Version 1.1.0
- Added detailed progress tracking during backup and restore operations
- Implemented comprehensive logging for all operations
- Added file count and size reporting
- Improved error handling and reporting
- Added automatic tagging with date and time for each backup
- File scanning and copy operations now show detailed progress
- Added status indicators for each step of the process
- Added GitHub repository information display after cloning
- Updated UI with more detailed progress indicators
- Improved CSS styling for log display

### Version 1.0.0
- Initial release
- Basic backup and restore functionality
- GitHub integration
- Excluded directories configuration

## FAQ

**Q: Is Git required on my web server?**
A: Yes, Git must be installed on your web server and accessible to the PHP process.

**Q: What files are backed up?**
A: By default, all WordPress files are backed up except for uploads, .git directories, and any other directories you specify in the exclusion list.

**Q: Is my database backed up?**
A: No, this plugin only backs up files. You should use a separate solution for database backups.

**Q: Can I schedule automatic backups?**
A: Not in this version. You need to manually trigger backups.

**Q: How do I create a GitHub Personal Access Token?**
A: Go to GitHub Settings → Developer Settings → Personal Access Tokens → Generate New Token, and grant it 'repo' permissions.

## Support

If you encounter any issues or have questions, please open an issue on the plugin's GitHub repository.

## License

This plugin is licensed under the GPL v2 or later.

## Credits

Developed by pawelbcn
