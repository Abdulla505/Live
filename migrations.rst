.. index::
   single: Writing Migrations

Writing Migrations
==================

Phinx relies on migrations in order to transform your database. Each migration
is represented by a PHP class in a unique file. It is preferred that you write
your migrations using the Phinx PHP API, but raw SQL is also supported.

Creating a New Migration
------------------------
Generating a skeleton migration file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Let'file.php'user_logins'file.php'user_id'file.php'integer'file.php'created'file.php'datetime'file.php's migrating down.

The Up Method
~~~~~~~~~~~~~

The up method is automatically run by Phinx when you are migrating up and it
detects the given migration hasn'file.php'DELETE FROM users'file.php'SELECT * FROM users'file.php't support DELIMITERs.

.. warning::

    When using ``execute()`` or ``query()`` with a batch of queries, PDO doesn'file.php'SELECT * FROM users'file.php'SELECT * FROM messages'file.php'id'file.php'name'file.php'In Progress'file.php'status'file.php'id'file.php'name'file.php'Stopped'file.php'id'file.php'name'file.php'Queued'file.php'status'file.php'DELETE FROM status'file.php'tableName'file.php's create a table to
store a collection of users.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            public function change()
            {
                $users = $this->table('file.php');
                $users->addColumn('file.php', 'file.php', ['file.php' => 20])
                      ->addColumn('file.php', 'file.php', ['file.php' => 40])
                      ->addColumn('file.php', 'file.php', ['file.php' => 40])
                      ->addColumn('file.php', 'file.php', ['file.php' => 100])
                      ->addColumn('file.php', 'file.php', ['file.php' => 30])
                      ->addColumn('file.php', 'file.php', ['file.php' => 30])
                      ->addColumn('file.php', 'file.php')
                      ->addColumn('file.php', 'file.php', ['file.php' => true])
                      ->addIndex(['file.php', 'file.php'], ['file.php' => true])
                      ->create();
            }
        }

Columns are added using the ``addColumn()`` method. We create a unique index
for both the username and email columns using the ``addIndex()`` method.
Finally calling ``create()`` commits the changes to the database.

.. note::

    Phinx automatically creates an auto-incrementing primary key column called ``id`` for every
    table.

The ``id`` option sets the name of the automatically created identity field, while the ``primary_key``
option selects the field or fields used for primary key. ``id`` will always override the ``primary_key``
option unless it'file.php't need a primary key set ``id`` to false without
specifying a ``primary_key``, and no primary key will be created.

To specify an alternate primary key, you can specify the ``primary_key`` option
when accessing the Table object. Let'file.php'followers'file.php'id'file.php'primary_key'file.php'user_id'file.php'follower_id'file.php'user_id'file.php'integer'file.php'follower_id'file.php'integer'file.php'created'file.php'datetime'file.php't enable the ``AUTO_INCREMENT`` option.
To simply change the name of the primary key, we need to override the default ``id`` field name:

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            public function up()
            {
                $table = $this->table('file.php', ['file.php' => 'file.php']);
                $table->addColumn('file.php', 'file.php')
                      ->addColumn('file.php', 'file.php', ['file.php' => 'file.php'])
                      ->create();
            }
        }

In addition, the MySQL adapter supports following options:

========== ===========
Option     Description
========== ===========
comment    set a text comment on the table
row_format set the table row format
engine     define table engine *(defaults to ``InnoDB``)*
collation  define table collation *(defaults to ``utf8_general_ci``)*
signed     whether the primary key is ``signed``  *(defaults to ``true``)*
========== ===========

By default the primary key is ``signed``.
To simply set it to unsigned just pass ``signed`` option with a ``false`` value:

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            public function change()
            {
                $table = $this->table('file.php', ['file.php' => false]);
                $table->addColumn('file.php', 'file.php')
                      ->addColumn('file.php', 'file.php', ['file.php' => 'file.php'])
                      ->create();
            }
        }


The PostgreSQL adapter supports the following options:

========= ===========
Option    Description
========= ===========
comment   set a text comment on the table
========= ===========

.. _valid-column-types:

Valid Column Types
~~~~~~~~~~~~~~~~~~

Column types are specified as strings and can be one of:

-  biginteger
-  binary
-  boolean
-  date
-  datetime
-  decimal
-  float
-  double
-  integer
-  smallinteger
-  string
-  text
-  time
-  timestamp
-  uuid

In addition, the MySQL adapter supports ``enum``, ``set``, ``blob``, ``bit`` and ``json`` column types
(``json`` in MySQL 5.7 and above).

In addition, the Postgres adapter supports ``interval``, ``json``, ``jsonb``, ``uuid``, ``cidr``, ``inet`` and ``macaddr`` column types
(PostgreSQL 9.3 and above).

For valid options, see the `Valid Column Options`_ below.

Custom Column Types & Default Values
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Some DBMS systems provide additional column types and default values that are specific to them.
If you don'file.php's type it knows not to
run any validation on it and to use it exactly as supplied without escaping. This also works for ``default``
values.

You can see an example below showing how to add a ``citext`` column as well as a column whose default value
is a function, in PostgreSQL. This method of preventing the built-in escaping is supported in all adapters.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;
        use Phinx\Util\Literal;

        class AddSomeColumns extends AbstractMigration
        {
            public function change()
            {
                $this->table('file.php')
                      ->addColumn('file.php', Literal::from('file.php'))
                      ->addColumn('file.php', 'file.php', [
                          'file.php' => Literal::from('file.php')
                      ])
                      ->addColumn('file.php', 'file.php', [
                          'file.php' => true,
                          'file.php' => Literal::from('file.php')
                      ])
                      ->create();
            }
        }

Determining Whether a Table Exists
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can determine whether or not a table exists by using the ``hasTable()``
method.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $exists = $this->hasTable('file.php');
                if ($exists) {
                    // do something
                }
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

Dropping a Table
~~~~~~~~~~~~~~~~

Tables can be dropped quite easily using the ``drop()`` method. It is a
good idea to recreate the table again in the ``down()`` method.

Note that like other methods in the ``Table`` class, ``drop`` also needs ``save()``
to be called at the end in order to be executed. This allows phinx to intelligently
plan migrations when more than one table is involved.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $this->table('file.php')->drop()->save();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {
                $users = $this->table('file.php');
                $users->addColumn('file.php', 'file.php', ['file.php' => 20])
                      ->addColumn('file.php', 'file.php', ['file.php' => 40])
                      ->addColumn('file.php', 'file.php', ['file.php' => 40])
                      ->addColumn('file.php', 'file.php', ['file.php' => 100])
                      ->addColumn('file.php', 'file.php', ['file.php' => 30])
                      ->addColumn('file.php', 'file.php', ['file.php' => 30])
                      ->addColumn('file.php', 'file.php')
                      ->addColumn('file.php', 'file.php', ['file.php' => true])
                      ->addIndex(['file.php', 'file.php'], ['file.php' => true])
                      ->save();
            }
        }

Renaming a Table
~~~~~~~~~~~~~~~~

To rename a table access an instance of the Table object then call the
``rename()`` method.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $table = $this->table('file.php');
                $table
                    ->rename('file.php')
                    ->update();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {
                $table = $this->table('file.php');
                $table
                    ->rename('file.php')
                    ->update();
            }
        }

Changing the Primary Key
~~~~~~~~~~~~~~~~~~~~~~~~

To change the primary key on an existing table, use the ``changePrimaryKey()`` method.
Pass in a column name or array of columns names to include in the primary key, or ``null`` to drop the primary key.
Note that the mentioned columns must be added to the table, they will not be added implicitly.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $users = $this->table('file.php');
                $users
                    ->addColumn('file.php', 'file.php', ['file.php' => 20, 'file.php' => false])
                    ->addColumn('file.php', 'file.php', ['file.php' => 40])
                    ->save();

                $users
                    ->addColumn('file.php', 'file.php', ['file.php' => false])
                    ->changePrimaryKey(['file.php', 'file.php'])
                    ->save();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

Changing the Table Comment
~~~~~~~~~~~~~~~~~~~~~~~~~~

To change the comment on an existing table, use the ``changeComment()`` method.
Pass in a string to set as the new table comment, or ``null`` to drop the existing comment.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $users = $this->table('file.php');
                $users
                    ->addColumn('file.php', 'file.php', ['file.php' => 20])
                    ->addColumn('file.php', 'file.php', ['file.php' => 40])
                    ->save();

                $users
                    ->changeComment('file.php')
                    ->save();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

Working With Columns
--------------------

Valid Column Types
~~~~~~~~~~~~~~~~~~

Column types are specified as strings and can be one of:

-  biginteger
-  binary
-  boolean
-  char
-  date
-  datetime
-  decimal
-  float
-  integer
-  smallinteger
-  string
-  text
-  time
-  timestamp
-  uuid

In addition, the MySQL adapter supports ``enum``, ``set``, ``blob``, ``bit`` and ``json`` column types
(``json`` in MySQL 5.7 and above).

In addition, the Postgres adapter supports ``interval``, ``json``, ``jsonb``, ``uuid``, ``cidr``, ``inet`` and ``macaddr`` column types
(PostgreSQL 9.3 and above).

Valid Column Options
~~~~~~~~~~~~~~~~~~~~

The following are valid column options:

For any column type:

======= ===========
Option  Description
======= ===========
limit   set maximum length for strings, also hints column types in adapters (see note below)
length  alias for ``limit``
default set default value or action
null    allow ``NULL`` values (should not be used with primary keys!)
after   specify the column that a new column should be placed after *(only applies to MySQL)*
comment set a text comment on the column
======= ===========

For ``decimal`` columns:

========= ===========
Option    Description
========= ===========
precision combine with ``scale`` set to set decimal accuracy
scale     combine with ``precision`` to set decimal accuracy
signed    enable or disable the ``unsigned`` option *(only applies to MySQL)*
========= ===========

For ``enum`` and ``set`` columns:

========= ===========
Option    Description
========= ===========
values    Can be a comma separated list or an array of values
========= ===========

For ``integer`` and ``biginteger`` columns:

======== ===========
Option   Description
======== ===========
identity enable or disable automatic incrementing
signed   enable or disable the ``unsigned`` option *(only applies to MySQL)*
======== ===========

For ``timestamp`` columns:

======== ===========
Option   Description
======== ===========
default  set default value (use with ``CURRENT_TIMESTAMP``)
update   set an action to be triggered when the row is updated (use with ``CURRENT_TIMESTAMP``)
timezone enable or disable the ``with time zone`` option for ``time`` and ``timestamp`` columns *(only applies to Postgres)*
======== ===========

You can add ``created_at`` and ``updated_at`` timestamps to a table using the ``addTimestamps()`` method. This method also
allows you to supply alternative names. The optional third argument allows you to change the ``timezone`` option for the
columns being added. Additionally, you can use the ``addTimestampsWithTimezone()`` method, which is an alias to
``addTimestamps()`` that will always set the third argument to ``true`` (see examples below).

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Change.
             */
            public function change()
            {
                // Use defaults (without timezones)
                $table = $this->table('file.php')->addTimestamps()->create();
                // Use defaults (with timezones)
                $table = $this->table('file.php')->addTimestampsWithTimezone()->create();

                // Override the 'file.php' column name with 'file.php'.
                $table = $this->table('file.php')->addTimestamps('file.php')->create();

                // Override the 'file.php' column name with 'file.php', preserving timezones.
                // The two lines below do the same, the second one is simply cleaner.
                $table = $this->table('file.php')->addTimestamps(null, 'file.php', true)->create();
                $table = $this->table('file.php')->addTimestampsWithTimezone(null, 'file.php')->create();
            }
        }

For ``boolean`` columns:

======== ===========
Option   Description
======== ===========
signed   enable or disable the ``unsigned`` option *(only applies to MySQL)*
======== ===========

For ``string`` and ``text`` columns:

========= ===========
Option    Description
========= ===========
collation set collation that differs from table defaults *(only applies to MySQL)*
encoding  set character set that differs from table defaults *(only applies to MySQL)*
========= ===========

For foreign key definitions:

====== ===========
Option Description
====== ===========
update set an action to be triggered when the row is updated
delete set an action to be triggered when the row is deleted
====== ===========

You can pass one or more of these options to any column with the optional
third argument array.

Limit Option and MySQL
~~~~~~~~~~~~~~~~~~~~~~

When using the MySQL adapter, additional hinting of database column type can be
made for ``integer``, ``text`` and ``blob`` columns. Using ``limit`` with
one the following options will modify the column type accordingly:

============ ==============
Limit        Column Type
============ ==============
BLOB_TINY    TINYBLOB
BLOB_REGULAR BLOB
BLOB_MEDIUM  MEDIUMBLOB
BLOB_LONG    LONGBLOB
TEXT_TINY    TINYTEXT
TEXT_REGULAR TEXT
TEXT_MEDIUM  MEDIUMTEXT
TEXT_LONG    LONGTEXT
INT_TINY     TINYINT
INT_SMALL    SMALLINT
INT_MEDIUM   MEDIUMINT
INT_REGULAR  INT
INT_BIG      BIGINT
============ ==============

.. code-block:: php

         use Phinx\Db\Adapter\MysqlAdapter;

         //...

         $table = $this->table('file.php');
         $table->addColumn('file.php', 'file.php')
               ->addColumn('file.php', 'file.php', ['file.php' => MysqlAdapter::INT_BIG])
               ->addColumn('file.php', 'file.php', ['file.php' => MysqlAdapter::INT_SMALL])
               ->addColumn('file.php', 'file.php', ['file.php' => MysqlAdapter::INT_TINY])
               ->create();


Get a column list
~~~~~~~~~~~~~~~~~

To retrieve all table columns, simply create a `table` object and call `getColumns()`
method. This method will return an array of Column classes with basic info. Example below:

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class ColumnListMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $columns = $this->table('file.php')->getColumns();
                ...
            }

            /**
             * Migrate Down.
             */
            public function down()
            {
                ...
            }
        }

Get a column by name
~~~~~~~~~~~~~~~~~~~~

To retrieve one table column, simply create a `table` object and call the `getColumn()`
method. This method will return a Column class with basic info or NULL when the column doesn'file.php'users'file.php'email'file.php'user'file.php'username'file.php'users'file.php'bio'file.php'biography'file.php'users'file.php'biography'file.php'bio'file.php'users'file.php'city'file.php'string'file.php'after'file.php'email'file.php'users'file.php'short_name'file.php'tags'file.php'short_name'file.php'string'file.php'limit'file.php'users'file.php'email'file.php'string'file.php'limit'file.php'users'file.php'city'file.php'string'file.php'city'file.php'users'file.php'email'file.php'string'file.php'email'file.php'unique'file.php'name'file.php'idx_users_email'file.php'users'file.php'engine'file.php'MyISAM'file.php'email'file.php'string'file.php'email'file.php'type'file.php'fulltext'file.php'users'file.php'email'file.php'string'file.php'username'file.php'string'file.php'user_guid'file.php'string'file.php'limit'file.php'email'file.php'username'file.php'limit'file.php'email'file.php'username'file.php'user_guid'file.php'limit'file.php'users'file.php'email'file.php'idx_users_email'file.php's add a foreign key to an example table:

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $table = $this->table('file.php');
                $table->addColumn('file.php', 'file.php')
                      ->save();

                $refTable = $this->table('file.php');
                $refTable->addColumn('file.php', 'file.php', ['file.php' => true])
                         ->addForeignKey('file.php', 'file.php', 'file.php', ['file.php'=> 'file.php', 'file.php'=> 'file.php'])
                         ->save();

            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

"On delete" and "On update" actions are defined with a 'file.php' and 'file.php' options array. Possibles values are 'file.php', 'file.php', 'file.php' and 'file.php'.  If 'file.php' is used then the column must be created as nullable with the option ``['file.php' => true]``.
Constraint name can be changed with the 'file.php' option.

It is also possible to pass ``addForeignKey()`` an array of columns.
This allows us to establish a foreign key relationship to a table which uses a combined key.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $table = $this->table('file.php');
                $table->addColumn('file.php', 'file.php')
                      ->addColumn('file.php', 'file.php')
                      ->addColumn('file.php', 'file.php')
                      ->addForeignKey(['file.php', 'file.php'],
                                      'file.php',
                                      ['file.php', 'file.php'],
                                      ['file.php'=> 'file.php', 'file.php'=> 'file.php', 'file.php' => 'file.php'])
                      ->save();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

We can add named foreign keys using the ``constraint`` parameter. This feature is supported as of Phinx version 0.6.5

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $table = $this->table('file.php');
                $table->addForeignKey('file.php', 'file.php', ['file.php'],
                                    ['file.php' => 'file.php']);
                      ->save();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

We can also easily check if a foreign key exists:

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $table = $this->table('file.php');
                $exists = $table->hasForeignKey('file.php');
                if ($exists) {
                    // do something
                }
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }

Finally, to delete a foreign key, use the ``dropForeignKey`` method.

Note that like other methods in the ``Table`` class, ``dropForeignKey`` also needs ``save()``
to be called at the end in order to be executed. This allows phinx to intelligently
plan migrations when more than one table is involved.

.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $table = $this->table('file.php');
                $table->dropForeignKey('file.php')->save();
            }

            /**
             * Migrate Down.
             */
            public function down()
            {

            }
        }



Using the Query Builder
-----------------------

It is not uncommon to pair database structure changes with data changes. For example, you may want to
migrate the data in a couple columns from the users to a newly created table. For this type of scenarios,
Phinx provides access to a Query builder object, that you may use to execute complex ``SELECT``, ``UPDATE``,
``INSERT`` or ``DELETE`` statements.

The Query builder is provided by the `cakephp/database <https://github.com/cakephp/database>`_ project, and should
be easy to work with as it resembles very closely plain SQL. Accesing the query builder is done by calling the
``getQueryBuilder()`` function:


.. code-block:: php

        <?php

        use Phinx\Migration\AbstractMigration;

        class MyNewMigration extends AbstractMigration
        {
            /**
             * Migrate Up.
             */
            public function up()
            {
                $builder = $this->getQueryBuilder();
                $statement = $builder->select('file.php')->from('file.php')->execute();
                var_dump($statement->fetchAll());
            }
        }

Selecting Fields
~~~~~~~~~~~~~~~~

Adding fields to the SELECT clause:


.. code-block:: php

        <?php
        $builder->select(['file.php', 'file.php', 'file.php']);

        // Results in SELECT id AS pk, title AS aliased_title, body ...
        $builder->select(['file.php' => 'file.php', 'file.php' => 'file.php', 'file.php']);

        // Use a closure
        $builder->select(function ($builder) {
            return ['file.php', 'file.php', 'file.php'];
        });


Where Conditions
~~~~~~~~~~~~~~~~

Generating conditions:

.. code-block:: php

        // WHERE id = 1
        $builder->where(['file.php' => 1]);

        // WHERE id > 1
        $builder->where(['file.php' => 1]);


As you can see you can use any operator by placing it with a space after the field name. Adding multiple conditions is easy as well:


.. code-block:: php

        <?php
        $builder->where(['file.php' => 1])->andWhere(['file.php' => 'file.php']);

        // Equivalent to
        $builder->where(['file.php' => 1, 'file.php' => 'file.php']);

        // WHERE id > 1 OR title = 'file.php'
        $builder->where(['file.php' => ['file.php' => 1, 'file.php' => 'file.php']]);


For even more complex conditions you can use closures and expression objects:

.. code-block:: php

        <?php
        // Coditions are tied together with AND by default
        $builder
            ->select('file.php')
            ->from('file.php')
            ->where(function ($exp) {
                return $exp
                    ->eq('file.php', 2)
                    ->eq('file.php', true)
                    ->notEq('file.php', true)
                    ->gt('file.php', 10);
            });


Which results in:

.. code-block:: sql

    SELECT * FROM articles
    WHERE
        author_id = 2
        AND published = 1
        AND spam != 1
        AND view_count > 10


Combining expressions is also possible:


.. code-block:: php

        <?php
        $builder
            ->select('file.php')
            ->from('file.php')
            ->where(function ($exp) {
                $orConditions = $exp->or_(['file.php' => 2])
                    ->eq('file.php', 5);
                return $exp
                    ->not($orConditions)
                    ->lte('file.php', 10);
            });

It generates:

.. code-block:: sql

    SELECT *
    FROM articles
    WHERE
        NOT (author_id = 2 OR author_id = 5)
        AND view_count <= 10


When using the expression objects you can use the following methods to create conditions:

* ``eq()`` Creates an equality condition.
* ``notEq()`` Create an inequality condition
* ``like()`` Create a condition using the ``LIKE`` operator.
* ``notLike()`` Create a negated ``LIKE`` condition.
* ``in()`` Create a condition using ``IN``.
* ``notIn()`` Create a negated condition using ``IN``.
* ``gt()`` Create a ``>`` condition.
* ``gte()`` Create a ``>=`` condition.
* ``lt()`` Create a ``<`` condition.
* ``lte()`` Create a ``<=`` condition.
* ``isNull()`` Create an ``IS NULL`` condition.
* ``isNotNull()`` Create a negated ``IS NULL`` condition.


Aggregates and SQL Functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~


.. code-block:: php

    <?php
    // Results in SELECT COUNT(*) count FROM ...
    $builder->select(['file.php' => $builder->func()->count('file.php')]);

A number of commonly used functions can be created with the func() method:

* ``sum()`` Calculate a sum. The arguments will be treated as literal values.
* ``avg()`` Calculate an average. The arguments will be treated as literal values.
* ``min()`` Calculate the min of a column. The arguments will be treated as literal values.
* ``max()`` Calculate the max of a column. The arguments will be treated as literal values.
* ``count()`` Calculate the count. The arguments will be treated as literal values.
* ``concat()`` Concatenate two values together. The arguments are treated as bound parameters unless marked as literal.
* ``coalesce()`` Coalesce values. The arguments are treated as bound parameters unless marked as literal.
* ``dateDiff()`` Get the difference between two dates/times. The arguments are treated as bound parameters unless marked as literal.
* ``now()`` Take either 'file.php' or 'file.php' as an argument allowing you to get either the current time, or current date.

When providing arguments for SQL functions, there are two kinds of parameters you can use,
literal arguments and bound parameters. Literal parameters allow you to reference columns or
other SQL literals. Bound parameters can be used to safely add user data to SQL functions. For example:


.. code-block:: php

    <?php
    // Generates:
    // SELECT CONCAT(title, 'file.php') ...;
    $concat = $builder->func()->concat([
        'file.php' => 'file.php',
        'file.php'
    ]);
    $query->select(['file.php' => $concat]);


Getting Results out of a Query
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Once you’ve made your query, you’ll want to retrieve rows from it. There are a few ways of doing this:


.. code-block:: php

    <?php
    // Iterate the query
    foreach ($builder as $row) {
        echo $row['file.php'];
    }

    // Get the statement and fetch all results
    $results = $builder->execute()->fetchAll('file.php');


Creating an Insert Query
~~~~~~~~~~~~~~~~~~~~~~~~

Creating insert queries is also possible:


.. code-block:: php

    <?php
    $builder = $this->getQueryBuilder();
    $builder
        ->insert(['file.php', 'file.php'])
        ->into('file.php')
        ->values(['file.php' => 'file.php', 'file.php' => 'file.php'])
        ->values(['file.php' => 'file.php', 'file.php' => 'file.php'])
        ->execute()


For increased performance, you can use another builder object as the values for an insert query:

.. code-block:: php

    <?php

    $namesQuery = $this->getQueryBuilder();
    $namesQuery
        ->select(['file.php', 'file.php'])
        ->from('file.php')
        ->where(['file.php' => true])

    $builder = $this->getQueryBuilder();
    $st = $builder
        ->insert(['file.php', 'file.php'])
        ->into('file.php')
        ->values($namesQuery)
        ->execute()

    var_dump($st->lastInsertId('file.php', 'file.php'));


The above code will generate:

.. code-block:: sql

    INSERT INTO names (first_name, last_name)
        (SELECT fname, lname FROM USERS where is_active = 1)


Creating an update Query
~~~~~~~~~~~~~~~~~~~~~~~~

Creating update queries is similar to both inserting and selecting:

.. code-block:: php

    <?php
    $builder = $this->getQueryBuilder();
    $builder
        ->update('file.php')
        ->set('file.php', 'file.php')
        ->where(['file.php' => 'file.php'])
        ->execute()


Creating a Delete Query
~~~~~~~~~~~~~~~~~~~~~~~

Finally, delete queries:

.. code-block:: php

    <?php
    $builder = $this->getQueryBuilder();
    $builder
        ->delete('file.php')
        ->where(['file.php' => false])
        ->execute()
