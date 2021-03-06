CHANGELOG
=========

This changelog references the relevant changes (bug and security fixes) done
in 1.x versions.

To get the diff for a specific change, go to https://github.com/joomlatools/joomla-console/commit/xxx where xxx is the change hash.
To view the diff between two versions, go to https://github.com/joomlatools/joomla-console/compare/v1.0.0...v1.0.1

* 1.4.0 (2015-09-01)
 * Added - `site:deploy`: deploy command to push sites using [git-ftp](https://github.com/git-ftp/git-ftp)
 * Added - `cache:list`, `cache:purge`, `cache:clear`: commands to handle cache manipulation in Joomla sites
 * Added - `site:checkin`: check-in locked rows
 * Added - `extension:uninstall`: Uninstall command to remove extensions
 * Added - `finder:index`, `finder:purge`: commands to build and purge com_finder indexes
 * Added - `extension:enable`, `extension:disable`: ability to enable and/or disable extensions
 * Added - Interactive mode (prompts for configuration details) 
 * Improved - Implemented support for [joomla-platform](http://github.com/joomlatools/joomla-platform)
 * Improved - Decoupled the various installation steps into separate commands (#5)
 * Improved - Symlinker logic is now extendible using plugins.
 * Improved - Ability to install from a different repository instead of the joomla/joomla-cms
 * Improved - Renamed the --joomla version flag to --release
 * Improved - It is now possible to symlink all available projects in the Projects directory at once using _all_ as argument
 * Improved - Install all discovered extensions in one command using the _all_ argument
 * Fixed - Documentroot in virtual hosts now works for non-default --www arguments
 * Fixed - Improved Joomla bootstrapper, add client_id as configuration option


* 1.3.3 (2015-07-30)
 * Fixed - Update dependency names in symlink command

* 1.3.2 (2015-05-12)
 * Improved - Updated installation instructions and README
 * Added - Allow custom toplevel namespace for plugins
 * Improved - Load all plugin classes before instantiating custom commands
 * Fixed - Search for stable versions by default when installing plugins instead of defaulting to dev-master

* 1.3.1 (2015-05-07)
 * Fix the missing extension:register command.

* 1.3.0 (2015-05-05)
 * New plugin system to easily extend Joomla console with new commands
 * New extension:register command to register components to extensions table for development
 * Add SSL support to the virtual hosts created by site:create command
 * Show default creations after site:create command
 * Fix Joomla 2.5 installation issues
 * Fix site:delete issue when it's called from the site folder
 * Fİx latest version check

* 1.2.0 (2014-11-06)
 * Added - redirect() stub to Joomla Application class
 * Added - Try/catch block in Joomla extension installer
 * Added - Symlinking support for Koowa components
 * Fixed - Use LF endings for bin/joomla
 * Fixed - Database creation with a dash in the name

* 1.1.0 (2014-07-09)
 * Improved - Cleary indicate default login credentials
 * Added - Created `extension:installfile` command to install directly from directory/package
 * Added - Implemented `site:token` command to generate JWT tokens
 * Added - Expose `--clear-cache` flag to purge the downloaded files cache

* 1.0.0 (2014-03-12)
 * Added - Initial release
