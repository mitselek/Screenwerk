cd %~dp0
git fetch --tags
for /f "delims=" %%a in ('git rev-list --tags --max-count=1') do @SET rev=%%a
for /f "delims=" %%a in ('git describe --tags %rev%') do @SET tag=%%a
git checkout --force %tag%
call nwjs use 0.12.3
call npm update
@ECHO Enjoy ScreenWerk
call nwjs
@ECHO Exited ScreenWerk
