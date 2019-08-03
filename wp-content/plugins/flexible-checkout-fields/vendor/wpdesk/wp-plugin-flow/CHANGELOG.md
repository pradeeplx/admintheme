## [1.9.1] - 2019-07-11
### Fixed
- Changed name of WPDesk_Plugin_Compatibility_Guard to WPDesk_Plugin_Compatibility_Guard_V2, 
  so there is no chance for conflict now. 

## [1.9.0] - 2019-07-01
### Added
- Translations
### Changed
- Info about conflict in Notice
- Info about conflict in logs
- Library conflicts with dev-master are ignored

## [1.8.0] - 2019-06-26
### Added
- Can use activation aware builder

## [1.7.0] - 2019-06-26
### Added
- Filter wpdesk_compatibility_guard_plugins_to_check to change what plugins should be checked

## [1.6.2] - 2019-06-26
### Changed
- Free plugins should not disable helper

## [1.6.0] - 2019-06-06
### Fixed
- Fix for get_plugin_name

## [1.6.0] - 2019-06-04
### Added
- Using subscription library 2.1 and PluginRegistrator class. Plugins no longer have to user Helper Plugin class
- Using basic-requirement 2.4 to pass plugin name
### Changed
- Error when old plugin is moved from init to build phase
- Error when old plugin disables plugin but now the dependent classes are still loaded
- do_action wpdesk_last_register_init_flow params changed from array many params(breaking change but nobody used it yet) 
### Fixed
- Small incompatibility for PHP 5.2 in plugin-init-php52-free.php

## [1.5.3] - 2019-06-04
### Fixed
- Invalid use of class_exists

## [1.5.2] - 2019-06-03
### Fixed
- Error when OLD plugin using new flow is trying to access this plugin dependencies and this plugin is disabled

## [1.5.1] - 2019-05-28
### Fixed
- Error when invalid array of libs is read

## [1.5.0] - 2019-05-23
### Added
- Compatibility checks when plugin version is changed
### Changed
- requirements_and_load_init_flow filter now takes params and not array

## [1.4.2] - 2019-05-10
### Fixed
- Fatal when require interface more than once

## [1.4.1] - 2019-05-08
### Fixed
- Build flow class can be successfully injected

## [1.4.0] - 2019-04-29
### Changed
- Removed array action get v2 to their names

## [1.3.1] - 2019-04-25
### Fixed
- Removed array from action init parameters

## [1.3.0] - 2019-04-24
### Added
- Flow for free plugins

## [1.2.0] - 2019-04-23
### Added
- PHPDoc info about flow
### Fixed
- Apigen fatal
### Changed
- Helper is initialized faster

## [1.1.1] - 2019-04-18
### Fixed
- Major problem with product_id, plugin_name passed to plugin_info
### Added
- Modules requirements

## [1.1.0] - 2019-04-17
### Added
- Helper library
- Helper plugin supression

## [1.0.6] - 2019-03-29
### Fixed
- Major problem in plugin name given to class loader. It should be WP plugin name dir/file

## [1.0.5] - 2019-03-25
### Fixed
- Fixed requirements in composer
- Fixed requirements in require once

## [1.0.0] - 2019-03-25
### Added
- Init, load, build flow