#!/bin/bash

export $(grep -v '^#' .env | xargs)
echo "export db :D,pls wait:3...."
echo ""

mkdir -p database

docker exec ${DB_CONTAINER} mysqldump \
 -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} > database/init.sql 

echo "db exported to database/init.sql successfully,see you later:D"

