#!/bin/bash

# Configuration variables
DB_NAME="my_first_db"
DB_USER="postgres"
SQL_FILE="setup.sql"

if [ -z "${DB_PASSWORD:-}" ]; then
  echo "DB_PASSWORD is not set" >&2
  exit 1
fi

# Export password so psql doesn't prompt you for it
export PGPASSWORD="$DB_PASSWORD"

echo "Running $SQL_FILE on database $DB_NAME..."

# Execute the file
psql -h localhost -U $DB_USER -d $DB_NAME -f $SQL_FILE

# Clear the password variable for security
unset PGPASSWORD

echo "Done."