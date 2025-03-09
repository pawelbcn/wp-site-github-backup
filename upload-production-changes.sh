#!/bin/bash

# Script to upload changes from production site to GitHub
# Usage: ./upload-production-changes.sh /path/to/production/files "Commit message"

# Check if source directory is provided
if [ -z "$1" ]; then
    echo "Error: Source directory not provided"
    echo "Usage: ./upload-production-changes.sh /path/to/production/files \"Commit message\""
    exit 1
fi

# Check if commit message is provided
if [ -z "$2" ]; then
    echo "Error: Commit message not provided"
    echo "Usage: ./upload-production-changes.sh /path/to/production/files \"Commit message\""
    exit 1
fi

SOURCE_DIR="$1"
COMMIT_MESSAGE="$2"
TARGET_DIR="./production-updates"

# Check if source directory exists
if [ ! -d "$SOURCE_DIR" ]; then
    echo "Error: Source directory does not exist: $SOURCE_DIR"
    exit 1
fi

echo "Starting upload process..."
echo "Source: $SOURCE_DIR"
echo "Target: $TARGET_DIR"

# Copy files from production to the repository
echo "Copying files from production..."
rsync -av --delete "$SOURCE_DIR/" "$TARGET_DIR/"

# Add changes to git
echo "Adding changes to git..."
git add "$TARGET_DIR"

# Commit changes
echo "Committing changes..."
git commit -m "$COMMIT_MESSAGE"

# Push to GitHub
echo "Pushing changes to GitHub..."
git push origin main

echo "Upload complete!" 