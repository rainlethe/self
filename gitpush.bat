echo "git start"
git add *.*
set /P comment=Comment?
git commit -m %comment%
git push origin master