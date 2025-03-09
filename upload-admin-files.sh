#!/bin/bash

# Script to upload admin files to production server via curl
# This script uses the configuration in ftp-config.sh

# Source the FTP configuration
if [ ! -f "./ftp-config.sh" ]; then
    echo "Error: FTP configuration file (ftp-config.sh) not found."
    echo "Please create the configuration file first."
    exit 1
fi

source ./ftp-config.sh

# Check if required variables are set
if [ -z "$FTP_HOST" ] || [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo "Error: Required FTP configuration variables are not set."
    echo "Please edit ftp-config.sh and set all required variables."
    exit 1
fi

echo "Starting upload of admin files to production server..."
echo "FTP Host: $FTP_HOST"
echo "Remote Path: $REMOTE_PATH (directly in plugin folder)"
echo "Local Path: $LOCAL_PATH"

# Function to upload a file using curl
upload_file() {
    local local_file="$1"
    local remote_file="$2"
    
    echo "Uploading $local_file to $remote_file..."
    
    # Create directory if needed
    if [[ "$remote_file" == *"/"* ]]; then
        local remote_dir=$(dirname "$remote_file")
        echo "Creating directory $remote_dir..."
        curl -s --ftp-create-dirs -u "$FTP_USER:$FTP_PASS" "ftp://$FTP_HOST$remote_dir/"
    fi
    
    # Upload the file
    curl -T "$local_file" -u "$FTP_USER:$FTP_PASS" "ftp://$FTP_HOST$remote_file"
    
    if [ $? -eq 0 ]; then
        echo "Successfully uploaded $local_file to $remote_file"
        return 0
    else
        echo "Failed to upload $local_file to $remote_file"
        return 1
    fi
}

# Upload admin files
echo "Uploading admin display file..."
upload_file "admin/partials/admin-display.php" "$REMOTE_PATH/admin/partials/admin-display.php"

echo "Uploading admin CSS file..."
upload_file "admin/css/admin.css" "$REMOTE_PATH/admin/css/admin.css"

echo "Uploading admin JS file..."
upload_file "admin/js/admin.js" "$REMOTE_PATH/admin/js/admin.js"

echo "Upload completed!"
echo "Admin files have been updated on the production server." 