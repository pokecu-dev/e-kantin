#!/bin/bash

export $(grep -v '^#' .env | xargs)
echo "import db :D,pls wait:3...."
echo ""

docker exec -i ${DB_CONTAINER} mysql \
 -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} < database/init.sql

echo "database/init.sql imported successfully,see you later :D"
