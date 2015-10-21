# sql-compare
Command line tool for comparing database schemas, built upon Doctrine DBAL

## Usage

1. Download the executable: [`sql-compare.phar`](https://github.com/talkrz/sql-compare/releases/download/v1.0.0/sql-compare.phar)

2. To compare two MySQL databases type:
```bash
php sql-compare.phar mysql://user:secret@server/db1 mysql://user:secret@server/db2
```

The tool will output a list of SQL queries required to update the first database schema to be identical with the second one.
Let's assume that 'db1' database, when compared the 'db2', has following differences:
 * missing the 'missing_table' table
 * 'some_table' table does not contain the 'missing_field' column
 * the 'changed_field' of 'some_table' has different type
 * the index on 'some_id' field on 'some_table' is missing

 Then the output of the command will be as follows:

```
CREATE TABLE missing_table (id INT NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB
ALTER TABLE some_table ADD missing_field INT DEFAULT NULL, CHANGE changed_field changed_field VARCHAR(255) DEFAULT NULL
CREATE INDEX missing_index ON some_table (some_id)
```

You can use any database URL allowed by Doctrine DBAL. Look ad the docs for
more details:
http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
