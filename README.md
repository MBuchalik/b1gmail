# b1gMail

> **Warning** **This project is work-in-progress. You should not use it in a production environment. There will be breaking changes.**

## Local development installation

To run a local development version, you first need a local web server. The recommended way is to use our Docker setup. Simply perform the following steps to start your local, Docker-powered development instance:

1. Clone the repository
2. Go to `/dev/` and follow the instructions on how to run the Docker development server.
3. Open `localhost:5000` in your web browser. The UI will guide you through the rest of the setup process.

After the installation, if you want to see detailed debug information like php errors and similar, open `/src/serverlib/config.inc.php` and add the following to it:

```php
define('DEBUG', true);
```

Often, when developing locally, you don't want to actually send emails (and even deal with properly configuring email sending). Simply add the following to your `config.inc.php` to replace the actual email sending with a mock that won't actually send the email:

```php
define('DEV_MOCK_MAIL_SENDING', true);
```

## Migrating from the commercial to the GPL version

A guide still has to be created. One important thing to note is that the B1GMAIL_SIGNKEY
from the commercial version's `init.inc.php` has to be moved to `config.inc.php` in order
to prevent data loss, especially w.r.t. to encrypted resources.
