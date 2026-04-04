
get-content .env | foreach-object {
    if ($_ -match '^([^#][^=]*)=(.*)$'){
        [system.environment]::setenvironmentvariable($matches[1],$matches[2])
    }
}

write-host "export db :D,pls wait:3...."

new-item -itemtype directory -force -path database | out-null

docker exec $env:DB_CONTAINER mysqldump `
 -u root -p"$env:MYSQL_ROOT_PASSWORD" $env:MYSQL_DATABASE `
  | out-file -filepath database\init.sql -encoding utf8

write-host "db exported to database/init.sql successfully,see you later:D"
