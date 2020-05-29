# command
## container起動
`docker-compose up -d workspace phpmyadmin apache2 mysql`
## workspaceへlogin
`docker-compose exec --user=laradock workspace bash`
## container削除
`docker-compose down`