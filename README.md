# sql-compare
Command line tool for comparing databases schema, built upon Doctrine DBAL

## Usage

TODO: Build phar archive of the applicaiton, and describe how to download it.

To compare two MySQL databases type:
```bash
bin/sql-compare mysql://user:secret@server/db1 mysql://user:secret@server/db2
```

You can use any database URL allowed by Doctrine DBAL. Look ad the docs for
more details:
http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
