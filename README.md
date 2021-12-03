# b1gMail developer repository

## Getting started
It is recommended to install the b1gMail developer copy on a local web server,
e.g. standard Apache/PHP/MySQL on Linux or Wamp on Windows. Even better results
on Windows can be achieved with a WSL setup.

In order to install a development environment, proceed as follows:
1. Clone the repository
2. Go to `src/serverlib/` and copy `config.default.inc.php` to `config.inc.php`
3. Open the folder `src` in your web browser, e.g. `http://localhost/b1gMail/src/`
4. Follow the setup instructs, use the normal serial number of your b1gMail license

## Staying up to date
When pulling new changes from the server, you will need to update your database
structure in case it changed. In order to do so, log in to the ACP of your b1gMail
development copy, go to "Tools" -> "Optimize" and chose "Check structure". Let
the ACP fix any issues it found.

## Contributing
You want to contribute to the b1gMail code? Great! In order to do so, it's
probably the best idea to fork the b1gMail repository in your GitLab account
here and start creating your own commits. As soon as you feel the commit is mature
and you would like to integrate it into the b1gMail code base, create a merge
request to the master repository (b1gmail-dev/b1gMail) and we will review it.

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
