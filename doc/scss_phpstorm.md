
Порядок настройки компиляции SCSS для phpStorm

1. Скачиваем и устанавливаем Ruby (https://rubyinstaller.org/)

2. В терминале 
      gem install sass
   Проверяем наличие пакета:
      sass -v
      
3. В phpStorm
    Settings - Tools - File Watchers - Add
    Настройки:
        Name:                                            SCSS
        File type:                                       SCSS
        Scope:                                           Project Files
        Program:                                         C:\Ruby24-x64\bin\scss.bat
        Arguments:                                       --no-cache --update $FileName$:../../public/build/$FileNameWithoutExtension$.css
        Output paths to refresh:                         $FileNameWithoutExtension$.css:$FileNameWithoutExtension$.css.map
        Working directory:                               $FileDir$
        Environment directory: 
        Auto-save edited files to trigger the watcher:   +
        Trigger the watcher on external changes:         +
        Trigger the watcher regardless of syntax errors: -
        Cheate output file from stdout:                  -
        Show console:                                    On error
        Output filters: 
        