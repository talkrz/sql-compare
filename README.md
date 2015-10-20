# sql-compare
Command line tool for comparing databases schema, built upon Doctrine DBAL

## Usage

TODO: Build phar archive of the applicaiton, and describe how to download it.

To compare two MySQL databases type:
```bash
bin/sql-compare mysql://user:secret@server/db1 mysql://user:secret@server/db2
```

The tool will output a list of SQL queries required to convert from first to second schema, like this:

```
CREATE TABLE missing_table (id INT NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB
ALTER TABLE some_table ADD missing_field INT DEFAULT NULL, CHANGE changed_field changed_field VARCHAR(255) DEFAULT NULL
CREATE INDEX missing_index ON some_table (some_id)
```

You can use any database URL allowed by Doctrine DBAL. Look ad the docs for
more details:
http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
