> This file describes important steps that need to be performed when upgrading from one major b1gmail version to another.

## Version 8.0.0 to 9.0.0

### Before running the update

You should uninstall all Plugins that provide Widgets. Widgets are not supported in version 9.0.0 anymore.

### After running the update

You can safely delete files from the `data/` directory with the following names:

- `backup_(3DigitNumber)_patchlevel_*`
- `toolboxImage_*`
- `toolboxRelease_*`

Here, "3DigitNumber" is a number like "720", "730" or "740".

## Version 7.4.0 to 8.0.0

### Before running the update

Make sure you have installed the latest available patch level. Also, please keep in mind that an update from a version lower than 7.4.0 is not supported.

Now, perform the following steps.

#### Step 1

First, you need to rename some folders:

| Old Folder Name | New Folder Name  |
| --------------- | ---------------- |
| `admin/`        | `admin-old/`     |
| `interface/`    | `interface-old/` |
| `m/`            | `m-old/`         |
| `serverlib/`    | `serverlib-old/` |
| `share/`        | `share-old/`     |
| `templates/`    | `templates-old/` |

So, rename folder `admin/` to `admin-old/`, `interface/` to `interface-old/`, and so on.

#### Step 2

Fetch the latest 8.x.x release and open the `src/` folder. In the following, we only need the content of this folder and thus don't mention "src" anymore.

Upload the new versions of the folders that you have renamed in step 1, i.e. upload the new `admin/`, `interface/`, ... folders from the 8.x.x release.

And, override the entrypoint files like `email.php`.

Finally, upload `config/` and `setup/`.

#### Step 3

After you have placed the updated files and folders in their directory, you need to copy+rename file `serverlib-old/config.inc.php` to `config/config.php`.

Then, open `serverlib-old/init.inc.php` and look for a line looking like this:

```php
define('B1GMAIL_SIGNKEY', '(some-random-string)');
```

Add this line to your new `config/config.php`.

### Run the update

Now, simply open `yourdomain.tld/setup/update.php`. The UI will guide you through the update process.
