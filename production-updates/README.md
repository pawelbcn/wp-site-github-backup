# Production Updates

This directory contains files and changes from the production WordPress site. These files are automatically uploaded using the `upload-production-changes.sh` script.

## Purpose

The purpose of this directory is to maintain a backup of production site changes and to track modifications made directly on the production server. This helps with:

1. Version control for production changes
2. Disaster recovery
3. Code synchronization between environments
4. Tracking changes made by different developers or administrators

## How to Use

When you have new code or changes on your production site that you want to back up to GitHub:

1. Run the upload script from the repository root:
   ```
   ./upload-production-changes.sh /path/to/production/site "Description of changes"
   ```

2. The script will:
   - Copy all files from the specified production directory to this folder
   - Commit the changes with your provided message
   - Push the changes to GitHub

## Important Notes

- This process does not replace proper development workflows
- Consider using proper staging environments for major changes
- Database changes are not tracked by this process
- Be careful not to expose sensitive information (credentials, etc.) 