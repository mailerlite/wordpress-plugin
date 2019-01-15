#!/bin/bash
latest="$(cat latest_version.txt)"

echo latest version was: $latest
echo enter version like: 1.2.3
read release

echo releasing: $release

# make sure branches are up to date
git checkout develop || exit 1
git pull || exit 1
git checkout master || exit 1
git pull || exit 1

# merge develop into master
git merge develop --commit --no-edit || exit 1

git tag -a $release -m "$release release" || exit

git push --follow-tags || exit 1

# back to master branch
git checkout master || exit 1
