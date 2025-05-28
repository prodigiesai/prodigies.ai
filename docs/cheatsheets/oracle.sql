# Oracle Terminal Cheat Sheet

# -----------------------------
# 1. Login to Oracle Database
# -----------------------------

# Login as SYSDBA
sqlplus / as sysdba

# Login as a specific user
sqlplus username/password@ORCL

# -----------------------------
# 2. Data Export (Using exp/expdp)
# -----------------------------

# Export using `exp` (Legacy tool)
exp username/password@ORCL file=export.dmp full=y log=export.log

# Export a specific schema
exp username/password@ORCL file=schema_export.dmp owner=schema_name

# Export specific tables
exp username/password@ORCL file=tables_export.dmp tables=table1,table2

# Data Pump Export (expdp)
expdp username/password@ORCL full=y directory=EXPORT_DIR dumpfile=export.dmp logfile=export.log

# Export a specific schema (expdp)
expdp username/password@ORCL schemas=schema_name directory=EXPORT_DIR dumpfile=schema_export.dmp logfile=schema_export.log

# Export specific tables (expdp)
expdp username/password@ORCL tables=table1,table2 directory=EXPORT_DIR dumpfile=tables_export.dmp logfile=tables_export.log

# -----------------------------
# 3. Data Import (Using imp/impdp)
# -----------------------------

# Import using `imp` (Legacy tool)
imp username/password@ORCL file=export.dmp full=y log=import.log

# Import a specific schema
imp username/password@ORCL file=schema_export.dmp fromuser=schema_name touser=target_schema_name

# Import specific tables
imp username/password@ORCL file=tables_export.dmp tables=table1,table2

# Data Pump Import (impdp)
impdp username/password@ORCL full=y directory=IMPORT_DIR dumpfile=export.dmp logfile=import.log

# Import a specific schema (impdp)
impdp username/password@ORCL schemas=schema_name directory=IMPORT_DIR dumpfile=schema_export.dmp logfile=schema_import.log

# Import specific tables (impdp)
impdp username/password@ORCL tables=table1,table2 directory=IMPORT_DIR dumpfile=tables_export.dmp logfile=tables_import.log

# -----------------------------
# 4. Backup (RMAN - Recovery Manager)
# -----------------------------

# Login to RMAN
rman target /

# Backup entire database
BACKUP DATABASE;

# Backup entire database with archive logs
BACKUP DATABASE PLUS ARCHIVELOG;

# Backup a specific tablespace
BACKUP TABLESPACE users;

# Backup only archive logs
BACKUP ARCHIVELOG ALL;

# Show RMAN backup status
LIST BACKUP;

# Show archive logs
LIST ARCHIVELOG ALL;

# Delete old backups
DELETE NOPROMPT OBSOLETE;

# -----------------------------
# 5. Restore and Recovery (RMAN)
# -----------------------------

# Restore and recover the entire database
RESTORE DATABASE;
RECOVER DATABASE;

# Restore a specific tablespace
RESTORE TABLESPACE users;
RECOVER TABLESPACE users;

# Restore from a specific backup
RESTORE DATABASE FROM TAG 'backup_tag';
RECOVER DATABASE;

# -----------------------------
# 6. Flashback Database
# -----------------------------

# Enable Flashback
ALTER DATABASE FLASHBACK ON;

# Flashback to a specific time or SCN
FLASHBACK DATABASE TO TIMESTAMP (SYSDATE - 1/24);  # 1 hour ago
FLASHBACK DATABASE TO SCN 123456;

# View Flashback Logs
SELECT * FROM V$FLASHBACK_DATABASE_LOG;

# -----------------------------
# 7. Oracle Data Guard (Replication)
# -----------------------------

# Create a physical standby database
# Ensure primary database is in ARCHIVELOG mode
ALTER DATABASE FORCE LOGGING;
ALTER DATABASE ADD SUPPLEMENTAL LOG DATA;

# Configure Data Guard
DGMGRL
CREATE CONFIGURATION 'my_dg' AS PRIMARY DATABASE IS 'primary_db' CONNECT IDENTIFIER IS 'ORCL';
ADD DATABASE 'standby_db' AS CONNECT IDENTIFIER IS 'STDBY' MAINTAINED AS PHYSICAL;

# Enable Data Guard
ENABLE CONFIGURATION;

# Show Data Guard status
SHOW CONFIGURATION;
SHOW DATABASE VERBOSE 'primary_db';
SHOW DATABASE VERBOSE 'standby_db';

# Switchover (role switch between primary and standby)
SWITCHOVER TO 'standby_db';

# Failover (in case of failure on primary)
FAILOVER TO 'standby_db';

# -----------------------------
# 8. Synchronization/Transportable Tablespaces
# -----------------------------

# Convert a tablespace for transport
ALTER TABLESPACE users READ ONLY;

# Export tablespace metadata
expdp username/password@ORCL directory=EXPORT_DIR transport_tablespaces=users dumpfile=ts_users.dmp logfile=ts_users.log

# Import tablespace metadata on target database
impdp username/password@ORCL directory=IMPORT_DIR transport_datafiles='/path/to/users01.dbf' dumpfile=ts_users.dmp logfile=ts_users_import.log

# -----------------------------
# 9. Optimization and Performance Tuning
# -----------------------------

# Gather schema statistics
EXEC DBMS_STATS.GATHER_SCHEMA_STATS('SCHEMA_NAME');

# Gather table statistics
EXEC DBMS_STATS.GATHER_TABLE_STATS('SCHEMA_NAME', 'TABLE_NAME');

# Analyze index statistics
EXEC DBMS_STATS.GATHER_INDEX_STATS('SCHEMA_NAME', 'INDEX_NAME');

# View execution plan for a SQL statement
EXPLAIN PLAN FOR SELECT * FROM employees;

# Display the execution plan
SELECT * FROM TABLE(DBMS_XPLAN.DISPLAY);

# Monitor long-running queries
SELECT * FROM V$SESSION_LONGOPS WHERE TIME_REMAINING > 0;

# Check database wait events
SELECT EVENT, TOTAL_WAITS FROM V$SYSTEM_EVENT ORDER BY TOTAL_WAITS DESC;

# -----------------------------
# 10. Archivelog Maintenance
# -----------------------------

# Enable Archivelog mode
SHUTDOWN IMMEDIATE;
STARTUP MOUNT;
ALTER DATABASE ARCHIVELOG;
ALTER DATABASE OPEN;

# Disable Archivelog mode
SHUTDOWN IMMEDIATE;
STARTUP MOUNT;
ALTER DATABASE NOARCHIVELOG;
ALTER DATABASE OPEN;

# View Archivelog status
ARCHIVE LOG LIST;

# Delete old archive logs
DELETE ARCHIVELOG ALL COMPLETED BEFORE 'SYSDATE-7';  # Delete older than 7 days

# -----------------------------
# 11. Managing Tablespaces
# -----------------------------

# Add datafile to a tablespace
ALTER TABLESPACE users ADD DATAFILE '/path/to/datafile.dbf' SIZE 100M AUTOEXTEND ON NEXT 10M MAXSIZE UNLIMITED;

# Resize datafile
ALTER DATABASE DATAFILE '/path/to/datafile.dbf' RESIZE 200M;

# Make tablespace read-only
ALTER TABLESPACE users READ ONLY;

# Make tablespace read-write
ALTER TABLESPACE users READ WRITE;

# Drop a tablespace
DROP TABLESPACE users INCLUDING CONTENTS AND DATAFILES;

# -----------------------------
# 12. Miscellaneous Commands
# -----------------------------

# Show current database status
SELECT STATUS FROM V$INSTANCE;

# Show current sessions
SELECT SID, SERIAL#, USERNAME, STATUS FROM V$SESSION;

# Kill a session
ALTER SYSTEM KILL SESSION 'sid,serial#';

# Check database size
SELECT SUM(BYTES)/1024/1024 AS SIZE_MB FROM DBA_DATA_FILES;

# Check free space in tablespaces
SELECT TABLESPACE_NAME, SUM(BYTES)/1024/1024 AS FREE_MB FROM DBA_FREE_SPACE GROUP BY TABLESPACE_NAME;

# -----------------------------
# 13. Data Pump (Full Database Export/Import)
# -----------------------------

# Full database export using expdp
expdp username/password@ORCL full=y directory=EXPORT_DIR dumpfile=full_db_export.dmp logfile=full_db_export.log

# Full database import using impdp
impdp username/password@ORCL full=y directory=IMPORT_DIR dumpfile=full_db_export.dmp logfile=full_db_import.log
