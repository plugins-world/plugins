#!/usr/bin/env bash

set -e

mkdir apps -p
zip -r apps/LaravelSaas.zip LaravelSaas -x "*node_modules/*" -x "*vendor/*"
zip -r apps/SanctumAuth.zip SanctumAuth -x "*node_modules/*" -x "*vendor/*"
zip -r apps/FileStorage.zip FileStorage -x "*node_modules/*" -x "*vendor/*"
zip -r apps/WechatLogin.zip WechatLogin -x "*node_modules/*" -x "*vendor/*"
zip -r apps/PayCenter.zip PayCenter -x "*node_modules/*" -x "*vendor/*"
zip -r apps/GithubAuth.zip GithubAuth -x "*node_modules/*" -x "*vendor/*"

# mv apps/* /path/to/apps/public/
