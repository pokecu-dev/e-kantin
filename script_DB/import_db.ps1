
get-content .env | foreach-object {
    if ($_ -match '^([^#][^=]*)=(.*)$'){
        [system.environment]::setenvironmentvariable($matches[1],$matches[2])
    }
}

Write-Host "import db :D,pls wait:3...."

Get-Content database\init.sql | docker exec -i $env:DB_CONTAINER mysql `
 -u root -p"$env:MYSQL_ROOT_PASSWORD" $env:MYSQL_DATABASE

Write-Host "database/init.sql imported successfully,see you later :D"
