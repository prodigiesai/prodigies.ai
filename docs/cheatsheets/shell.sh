# Basic File Management

ls  # List files and directories in the current directory
ls -l  # List files in long format (permissions, ownership, size, modification time)
mkdir <directory-name>  # Create a new directory
touch <file-name>  # Create a new empty file
rm <file-name>  # Remove a file
rm -r <directory-name>  # Remove a directory and its contents
cp <source-file> <destination>  # Copy a file
cp -r <source-directory> <destination-directory>  # Copy a directory and its contents
mv <source-file> <destination>  # Move or rename a file or directory

# File Permissions and Ownership

chmod 755 <file-name>  # Change file permissions (read, write, execute for owner; read and execute for others)
chown <owner>:<group> <file-name>  # Change file owner and group
chown -R <owner>:<group> <directory>  # Change owner and group recursively for a directory

# Viewing and Editing Files

cat <file-name>  # Display the contents of a file
head <file-name>  # Display the first 10 lines of a file
tail <file-name>  # Display the last 10 lines of a file
tail -f <file-name>  # Follow the changes to a file in real time
echo "Text" >> <file-name>  # Append text to a file
nano <file-name>  # Edit a file using the nano text editor
vim <file-name>  # Edit a file using the Vim text editor

# Synchronization and Backup

rsync -av <source> <destination>  # Sync files and directories (archive mode, verbose)
rsync -av --delete <source> <destination>  # Sync files and delete from destination if not present in source
cp -u <source-file> <destination>  # Copy only if the source file is newer than the destination

# SSH and SFTP Commands

ssh <user>@<remote-host>  # Connect to a remote server via SSH
ssh -i <key-file> <user>@<remote-host>  # Connect to a remote server using an SSH key
sftp <user>@<remote-host>  # Connect to a remote server via SFTP
sftp> put <local-file> <remote-directory>  # Transfer a file from local to remote server
sftp> get <remote-file> <local-directory>  # Transfer a file from remote to local
sftp> mkdir <remote-directory>  # Create a directory on the remote server

# SSH Key Management

ssh-keygen -t rsa -b 4096 -C "your_email@example.com"  # Generate a new SSH key pair
ssh-copy-id <user>@<remote-host>  # Copy your public key to the remote server for passwordless login
cat ~/.ssh/id_rsa.pub  # Display your public SSH key
eval "$(ssh-agent -s)"  # Start the SSH agent in the background
ssh-add ~/.ssh/id_rsa  # Add your SSH private key to the SSH agent

# File Compression and Archiving

tar -czvf <archive-name>.tar.gz <directory>  # Create a tar.gz archive
tar -xzvf <archive-name>.tar.gz  # Extract a tar.gz archive
zip -r <archive-name>.zip <directory>  # Create a zip archive
unzip <archive-name>.zip  # Extract a zip archive

# File and Disk Usage

df -h  # Show disk space usage (human-readable)
du -sh <directory>  # Show the size of a directory (human-readable)
find <directory> -name "<file-pattern>"  # Find files by name
grep "pattern" <file>  # Search for a pattern in a file
grep -r "pattern" <directory>  # Search recursively for a pattern in a directory

# Process Management

ps aux  # Show all running processes
kill <PID>  # Kill a process by PID
top  # Show real-time system resource usage
htop  # Enhanced interactive system monitor (if installed)



# 1. Loop Through Files in a Directory
for file in /path/to/directory/*; do
    echo "Processing $file"
    # Perform operations like copying, moving, etc.
done

# 2. Check if a File Exists
if [ -f "/path/to/file" ]; then
    echo "File exists."
else
    echo "File does not exist."
fi


# 3. Backup Files with Date in Filename
backup_file="/path/to/backup-$(date +%Y%m%d).tar.gz"
tar -czvf $backup_file /path/to/important-files

# 4. SSH into Multiple Servers and Run Commands
for server in server1 server2 server3; do
    ssh user@$server "uptime && df -h"
done


# 5. Append to a Log File with Timestamp
echo "$(date '+%Y-%m-%d %H:%M:%S') - Log message" >> /path/to/logfile.log


# 6. Find and Delete Files Older than X Days
find /path/to/directory -type f -mtime +30 -exec rm {} \;  # Deletes files older than 30 days


# 7. SFTP Transfer in a Script
sftp user@remote-server <<EOF
put /path/to/local-file /path/to/remote-directory/
get /path/to/remote-file /path/to/local-directory/
bye
EOF

# 8. Automatic Rsync Backup with Exclusions
rsync -av --exclude 'node_modules' --exclude '.git' /source/directory/ /backup/directory/


# 9. Check Disk Space and Send Alert if Low
THRESHOLD=90
USED=$(df / | grep / | awk '{ print $5 }' | sed 's/%//g')
if [ $USED -gt $THRESHOLD ]; then
    echo "Disk space critically low: $USED% used!" | mail -s "Disk Alert" user@example.com
fi

# 10. Generate SSH Key Pair in a Script
ssh-keygen -t rsa -b 4096 -f ~/.ssh/my_new_key -C "your_email@example.com" -N ""  # Generate SSH key without passphrase
ssh-add ~/.ssh/my_new_key  # Add the new key to the SSH agent

# 1. Creating a Cron Job Without Root
crontab -e  # Edit your crontab file (as the current user)
# * * * * * /path/to/script.sh  # Runs every minute
crontab -l  # List all cron jobs for the current user
crontab -r  # Remove all cron jobs for the current user
* * * * * /path/to/script.sh >> /path/to/logfile.log 2>&1  #You can log the output of a cron job to a file:

# Triggering Jobs When a File is Created in a Folder
# 1. Using inotifywait (Linux)
sudo apt install inotify-tools  # Ubuntu/Debian
sudo yum install inotify-tools  # RHEL/CentOS
inotifywait -m /path/to/directory -e create -e moved_to |
while read dir action file; do
    echo "The file '$file' appeared in directory '$dir' via '$action'"
    # Call your script or command here
done
# 2. Automating Inotify with a Script
#!/bin/bash
inotifywait -m /path/to/directory -e create -e moved_to |
while read dir action file; do
    echo "New file detected: $file"
    /path/to/your/script.sh "$file"
done
chmod +x file_watcher.sh


# Common Shell Snippets for Cron Jobs and File Watching
# 1. Run a Job When a File Is Created in a Directory
inotifywait -m /path/to/dir -e create -e moved_to |
while read dir action file; do
    echo "File '$file' created or moved in directory '$dir'."
    /path/to/your_script.sh "$file"
done
# 2. Backup Files Every Day Using Cron
0 2 * * * /path/to/backup_script.sh >> /path/to/backup_log.log 2>&1  # Runs at 2:00 AM
# 3. Delete Files Older than 30 Days Using Cron
0 0 * * * find /path/to/directory -type f -mtime +30 -exec rm {} \;  # Runs at midnight
# 4. Cron Job That Sends Disk Usage Alert
*/10 * * * * /path/to/disk_alert.sh  # Run every 10 minutes

# disk_alert.sh content
THRESHOLD=90
USED=$(df / | grep / | awk '{print $5}' | sed 's/%//g')
if [ $USED -gt $THRESHOLD ]; then
    echo "Disk usage critically high: $USED%" | mail -s "Disk Alert" your_email@example.com
fi
