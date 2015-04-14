DBNAME=barjos
DATE=`date +"%Y%m%d"`
SQLFILE=$DBNAME-${DATE}.sql
mysqldump --opt --user=homestead --password $DBNAME > $SQLFILE
gzip $SQLFILE