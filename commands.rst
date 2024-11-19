.. index::
   single: Commands

Commands
========

Phinx is run using a number of commands.

The Breakpoint Command
----------------------

The Breakpoint command is used to set breakpoints, allowing you to limit
rollbacks. You can toggle the breakpoint of the most recent migration by
not supplying any parameters.

.. code-block:: bash

        $ phinx breakpoint -e development

To toggle a breakpoint on a specific version then use the ``--target``
parameter or ``-t`` for short.

.. code-block:: bash

        $ phinx breakpoint -e development -t 20120103083322

You can remove all the breakpoints by using the ``--remove-all`` parameter
or ``-r`` for short.

.. code-block:: bash

        $ phinx breakpoint -e development -r

You can set or unset (rather than just toggle) the breakpoint on the most
recent migration (or on a specific migration when combined with the
``--target`` or ``-t`` parameter) by using ``-set`` or ``--unset``.

Breakpoints are visible when you run the ``status`` command.

The Create Command
------------------

The Create command is used to create a new migration file. It requires one
argument: the name of the migration. The migration name should be specified in
CamelCase format.

.. code-block:: bash

        $ phinx create MyNewMigration

Open the new migration file in your text editor to add your database
transformations. Phinx creates migration files using the path specified in your
``phinx.yml`` file. Please see the :doc:`Configuration <configuration>` chapter
for more information.

You are able to override the template file used by Phinx by supplying an
alternative template filename.

.. code-block:: bash

        $ phinx create MyNewMigration --template="<file>"

You can also supply a template generating class. This class must implement the
interface ``Phinx\Migration\CreationInterface``.

.. code-block:: bash

        $ phinx create MyNewMigration --class="<class>"

In addition to providing the template for the migration, the class can also define
a callback that will be called once the migration file has been generated from the
template.

You cannot use ``--template`` and ``--class`` together.

The Init Command
----------------

The Init command (short for initialize) is used to prepare your project for
Phinx. This command generates the ``phinx.yml`` file in the root of your
project directory.

.. code-block:: bash

        $ cd yourapp
        $ phinx init

Optionally you can specify a custom location for Phinx'file.php'DB_HOST'file.php'DB_NAME'file.php'DB_USER'file.php'DB_PASS'file.php'DB_PORT'file.php'PHINX_DBNAME'file.php'migrate'file.php'command'file.php'migrate'file.php'--environment'file.php'production'file.php'--configuration'file.php'/path/to/config/phinx.yml'file.php'migrate'file.php'll need to give Phinx a specific PDO instance. You can interact with Phinx directly
using the Manager class :

.. code-block:: php

        use PDO;
        use Phinx\Config\Config;
        use Phinx\Migration\Manager;
        use PHPUnit\Framework\TestCase;
        use Symfony\Component\Console\Input\StringInput;
        use Symfony\Component\Console\Output\NullOutput;

        class DatabaseTestCase extends TestCase {

            public function setUp ()
            {
                $pdo = new PDO('file.php', null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                $configArray = require('file.php');
                $configArray['file.php']['file.php'] = [
                    'file.php'    => 'file.php',
                    'file.php' => $pdo
                ];
                $config = new Config($configArray);
                $manager = new Manager($config, new StringInput('file.php'), new NullOutput());
                $manager->migrate('file.php');
                $manager->seed('file.php');
                // You can change default fetch mode after the seeding
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                $this->pdo = $pdo;
            }

        }
