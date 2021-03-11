#!/bin/bash
echo enter version like: 1.2.3
read release

echo releasing: $release

# make sure branches are up to date
git checkout develop || exit 1
git pull || exit 1
git checkout main || exit 1
git pull || exit 1

# merge develop into main
git merge develop --commit --no-edit || exit 1

git tag -a $release -m "$release release" || exit

git push --follow-tags || exit 1

# back to main branch
git checkout main || exit 1