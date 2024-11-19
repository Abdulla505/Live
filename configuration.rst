.. index::
   single: Configuration

Configuration
=============

When you initialize your project using the :doc:`Init Command<commands>`, Phinx
creates a default file in the root of your project directory. By default, this
file uses the YAML data serialization format, but you can use the ``--format``
command line option to specify either ``yaml``, ``yml``, ``json``, or ``php``.

If a ``--configuration`` command line option is given, Phinx will load the
specified file. Otherwise, it will attempt to find ``phinx.php``, ``phinx.json``,
``phinx.yml``, or ``phinx.yaml`` and load the first file found. See the
:doc:`Commands <commands>` chapter for more information.

.. warning::

    Remember to store the configuration file outside of a publicly accessible
    directory on your webserver. This file contains your database credentials
    and may be accidentally served as plain text.

Note that while JSON and YAML files are *parsed*, the PHP file is *included*.
This means that:

* It must `return` an array of configuration items.
* The variable scope is local, i.e. you would need to explicitly declare
  any global variables your initialization file reads or modifies.
* Its standard output is suppressed.
* Unlike with JSON and YAML, it is possible to omit environment connection details
  and instead specify ``connection`` which must contain an initialized PDO instance.
  This is useful when you want your migrations to interact with your application
  and/or share the same connection. However remember to also pass the database name
  as Phinx cannot infer this from the PDO connection.

.. code-block:: php

    $app = require 'file.php';
    $pdo = $app->getDatabase()->getPdo();

    return [
        'file.php' => [
            'file.php' => 'file.php',
            'file.php' => [
                'file.php' => 'file.php',
                'file.php' => $pdo
            ]
        ]
    ];

Migration Paths
---------------

The first option specifies the path to your migration directory. Phinx uses
``%%PHINX_CONFIG_DIR%%/db/migrations`` by default.

.. note::

    ``%%PHINX_CONFIG_DIR%%`` is a special token and is automatically replaced
    with the root directory where your ``phinx.yml`` file is stored.

In order to overwrite the default ``%%PHINX_CONFIG_DIR%%/db/migrations``, you
need to add the following to the yaml configuration.

.. code-block:: yaml

    paths:
        migrations: /your/full/path

You can also provide multiple migration paths by using an array in your configuration:

.. code-block:: yaml

    paths:
        migrations:
            - application/module1/migrations
            - application/module2/migrations


You can also use the ``%%PHINX_CONFIG_DIR%%`` token in your path.

.. code-block:: yaml

    paths:
        migrations: 'file.php'

Migrations are captured with ``glob``, so you can define a pattern for multiple
directories.

.. code-block:: yaml

    paths:
        migrations: 'file.php'

Custom Migration Base
---------------------

By default all migrations will extend from Phinx'file.php'%%PHINX_CONFIG_DIR%%/your/relative/path'file.php''file.php''file.php'%%PHINX_DBUSER%%'file.php'%%PHINX_DBHOST%%'file.php'%%PHINX_DBNAME%%'file.php'%%PHINX_DBUSER%%'file.php'%%PHINX_DBPASS%%'file.php's values are merged with the already existing
connection options. Values in specified in a DSN will never override any value
specified directly as connection options.

.. code-block:: yaml

    environments:
        default_migration_table: phinxlog
        default_database: development
        development:
            dsn: %%DATABASE_URL%%
        production:
            dsn: %%DATABASE_URL%%
            name: production_database

If the supplied DSN is invalid, then it is completely ignored.

Supported Adapters
------------------

Phinx currently supports the following database adapters natively:

* `MySQL <http://www.mysql.com/>`_: specify the ``mysql`` adapter.
* `PostgreSQL <http://www.postgresql.org/>`_: specify the ``pgsql`` adapter.
* `SQLite <http://www.sqlite.org/>`_: specify the ``sqlite`` adapter.
* `SQL Server <http://www.microsoft.com/sqlserver>`_: specify the ``sqlsrv`` adapter.

SQLite
`````````````````

Declaring an SQLite database uses a simplified structure:

.. code-block:: yaml

    environments:
        development:
            adapter: sqlite
            name: ./data/derby
            suffix: ".db"    # Defaults to ".sqlite3"
        testing:
            adapter: sqlite
            memory: true     # Setting memory to *any* value overrides name

SQL Server
`````````````````

When using the ``sqlsrv`` adapter and connecting to a named instance you should
omit the ``port`` setting as SQL Server will negotiate the port automatically.
Additionally, omit the ``charset: utf8`` or change to ``charset: 65001`` which
corresponds to UTF8 for SQL Server.

Custom Adapters
`````````````````

You can provide a custom adapter by registering an implementation of the `Phinx\\Db\\Adapter\\AdapterInterface`
with `AdapterFactory`:

.. code-block:: php

    $name  = 'file.php';
    $class = 'file.php';

    AdapterFactory::instance()->registerAdapter($name, $class);

Adapters can be registered any time before `$app->run()` is called, which normally
called by `bin/phinx`.

Aliases
-------

Template creation class names can be aliased and used with the ``--class`` command line option for the :doc:`Create Command <commands>`.

The aliased classes will still be required to implement the ``Phinx\Migration\CreationInterface`` interface.

.. code-block:: yaml

    aliases:
        permission: \Namespace\Migrations\PermissionMigrationTemplateGenerator
        view: \Namespace\Migrations\ViewMigrationTemplateGenerator

Version Order
-------------

When rolling back or printing the status of migrations, Phinx orders the executed migrations according to the
``version_order`` option, which can have the following values:

* ``creation`` (the default): migrations are ordered by their creation time, which is also part of their filename.
* ``execution``: migrations are ordered by their execution time, also known as start time.

Bootstrap Path
---------------

You can provide a path to a `bootstrap` php file that will included before any commands phinx commands are run. Note that
setting External Variables to modify the config will not work because the config has already been parsed by this point.

.. code-block:: yaml

    paths:
        bootstrap: 'file.php'
