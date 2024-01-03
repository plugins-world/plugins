#!/usr/bin/env bash

set -ex

# mkdir -p apps
zip -r LaravelSaas.zip LaravelSaas -x "*node_modules/*" -x "*vendor/*"
zip -r DcatSaas.zip DcatSaas -x "*node_modules/*" -x "*vendor/*"
zip -r SanctumAuth.zip SanctumAuth -x "*node_modules/*" -x "*vendor/*"
zip -r FileStorage.zip FileStorage -x "*node_modules/*" -x "*vendor/*"
zip -r WechatLogin.zip WechatLogin -x "*node_modules/*" -x "*vendor/*"
zip -r PayCenter.zip PayCenter -x "*node_modules/*" -x "*vendor/*"
zip -r GithubAuth.zip GithubAuth -x "*node_modules/*" -x "*vendor/*"
zip -r EasySms.zip EasySms -x "*node_modules/*" -x "*vendor/*"
zip -r SmsAuth.zip SmsAuth -x "*node_modules/*" -x "*vendor/*"
zip -r EasyMap.zip EasyMap -x "*node_modules/*" -x "*vendor/*"
zip -r BaiduOcr.zip BaiduOcr -x "*node_modules/*" -x "*vendor/*"

# mv apps/* /path/to/apps/public/
