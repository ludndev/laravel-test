#!/bin/sh

# Create the database directory and file if they do not exist
if [ ! -f /var/www/database/database.sqlite ]; then
    mkdir -p /var/www/database
    touch /var/www/database/database.sqlite
    echo "SQLite database file created."
else
    echo "SQLite database file already exists."
fi

# Run the original command
exec "$@"
