# CSV converter
 CSV converter - программа, которая конвертирует csv файлы, используя пользовательские конфигурации.  
### Для работы с программой необходимо указать:
  1. Входной файл в csv формате с правами на чтение;  
  2. Конфигурационный файл с правами на чтение;  
  3. Выходной файл с правами на чтение и на запись.
  
      
      Доступные параметры:     
      -i|--input       <input path>  - путь до исходного файла,
      -c|--config      <config path> - путь до конфигурационного файла,
      -o|--output      <output path> - путь до результирующего файла,
      -d|--delimiter   <argument>    - разделитель, по умолчанию = ","
      -h|--help                      - вывод справки,
      --skip-first                   - пропуск первой строки,
      --strict                       - сравнение количества стобцов в конфигурационном и входном файлах
### Установка
 (Для установки небходим composer)
 
 1. Скопировать файлы в произвольную папку
 
 
    git clone https://github.com/WhiteFoOx/csv_converter.git path_to_dir   
 2. Выполнить команды:
 
 
     cd path_to_dir \
     && composer install \
     && make install
        
Пример запуска программы:
         
            
    php converter.php -i inputFile.csv -c configFile.php -o outputFile.csv -d ',' --skip-first
Запуск тестов:


        cd tests \
        && phpunit .