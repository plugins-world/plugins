#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="master"

SPLITSH_LITE_BIN=splitsh-lite
if [ ! -x $SPLITSH_LITE_BIN ]; then
    SPLITSH_LITE_BIN=./bin/splitsh-lite
fi

function split()
{
    SHA1=`${SPLITSH_LITE_BIN} --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
    git remote remove $2 || true
    git branch -D $2 || true
}

function remote()
{
    git remote add $1 $2 || git remote set-url $1 $2 || true
}

git pull origin $CURRENT_BRANCH || true


#remote foundation git@github.com:mouyong/laravel-foundation.git
remote DcatSaas git@github.com:plugins-world/DcatSaas.git
remote LaravelJwtAuth git@github.com:plugins-world/LaravelJwtAuth.git
remote LaravelQiNiu git@github.com:plugins-world/LaravelQiNiu.git
remote LaravelLocalStorage git@github.com:plugins-world/LaravelLocalStorage.git

remote SsoServer git@github.com:plugins-world/SsoServer.git
remote SsoClient git@github.com:plugins-world/SsoClient.git
remote FileManage git@github.com:plugins-world/FileManage.git
remote SystemAuthorization git@github.com:plugins-world/SystemAuthorization.git
remote WuKongAuthCode git@github.com:plugins-world/WuKongAuthCode.git
remote Editor git@github.com:plugins-world/Editor.git

#split 'src/Illuminate/Foundation' foundation
split 'DcatSaas' DcatSaas
split 'LaravelJwtAuth' LaravelJwtAuth
split 'LaravelQiNiu' LaravelQiNiu
split 'LaravelLocalStorage' LaravelLocalStorage

split 'SsoServer' SsoServer
split 'SsoClient' SsoClient
split 'FileManage' FileManage
split 'SystemAuthorization' SystemAuthorization
split 'Editor' Editor
