#!/bin/bash

DB="hotelapp"
USER="root"

echo "🟡 Starting setup..."

# Step 1 - Create DB if not exists
mysql -u $USER -p -e "CREATE DATABASE IF NOT EXISTS $DB;" && \
echo "✅ Database '$DB' created or already exists."

# Step 2 - Apply schema
if [ -f "sql/migrations/001_initial_schema.sql" ]; then
  mysql -u $USER -p $DB < sql/migrations/001_initial_schema.sql
  echo "✅ Schema applied."
else
  echo "❌ Error: Schema file not found at sql/migrations/001_initial_schema.sql"
fi

# Step 3 - Apply seed data
if [ -f "sql/seeds/001_seed_public_data.sql" ]; then
  mysql -u $USER -p $DB < sql/seeds/001_seed_public_data.sql
  echo "✅ Public seed data loaded."
else
  echo "❌ Error: Seed file not found at sql/seeds/001_seed_public_data.sql"
fi

