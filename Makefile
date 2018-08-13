install:
	composer install
    cp tests/files/notRdbExample.csv tests/files/notRdb.csv
	chmod 111 tests/files/notRdb.csv
    cp tests/files/OutputCSVExample.csv tests/files/OutputCSV.csv
