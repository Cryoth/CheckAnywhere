#!/bin/sh

date=$(date +%Y-%m-%d)

php /var/www/html/Modele/SQL/resetAll.php
mysql -u root -p'My$QL_$3rv3r' ELVIR < '/var/www/html/Modele/SQL/data_ResetAll.sql'
mv /var/www/html/Modele/SQL/data_ResetAll.sql /var/www/html/Modele/SQL/Semaines_OK/
