# b1gMail

## Local development installation

To run a local development version, you first need a local web server. The recommended way is to use our Docker setup. Simply perform the following steps to start your local, Docker-powered development instance:
1. Clone the repository
2. Go to `/src/serverlib/` and copy `config.default.inc.php` to `config.inc.php`
3. Go to `/dev/` and follow the instructions on how to run the Docker development server.
4. Open `localhost:5000` in your web browser. The UI will guide you through the rest of the setup process.

If you want to see detailed debug information like php errors and similar, open `/src/serverlib/config.inc.php` and add the following to it:

``` php
define('DEBUG', true);
```

## Staying up to date
When pulling new changes from the server, you will need to update your database
structure in case it changed. In order to do so, log in to the ACP of your b1gMail
development copy, go to "Tools" -> "Optimize" and chose "Check structure". Let
the ACP fix any issues it found.

### Basic guidelines for commits
* Adhere to the b1gMail coding style
* If your commit requires database structure changes, include the updated database
  structure in the commit (you can export it using the `tools/db_struct.php` tool)
* If your commit requires other DB changes (i.e. change values), include update code
  in the update script (it should be executed when updating to the next major version)

## Migrating from the commercial to the GPL version
A guide still has to be created. One important thing to note is that the B1GMAIL_SIGNKEY
from the commercial version's `init.inc.php` has to be moved to `config.inc.php` in order
to prevent data loss, especially w.r.t. to encrypted resources.
