> This file describes important steps that need to be performed when upgrading from one major b1gmail version to another.

## Version 7.4.0 to 8.0.0

### Before running the update

Make sure you have installed the latest available patch level. Also, please keep in mind that an update from a version lower than 7.4.0 is not supported.

After you have placed the updated files and folders in their directory, you need to copy+rename file `config.inc.php` from the old `serverlib/` directory to `config/config.php`.

### After running the update

You can delete the `clientlib/`, `languages/`, `plz/`, and `res/` folders and their content.
