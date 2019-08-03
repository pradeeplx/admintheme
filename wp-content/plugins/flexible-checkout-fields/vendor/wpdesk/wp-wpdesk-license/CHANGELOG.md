## [2.3.0] - 2019-06-26
### Added
- InfoActivationBuilder with capability to set info if plugin subscription is active

## [2.2.2] - 2019-06-26
### Fixed
- Activation check

## [2.2.1] - 2019-06-13
### Fixed
- Plugin still using old WPDesk_Helper_Plugin sometimes can be shown twice

## [2.2.0] - 2019-06-04
### Added
- wpdesk_show_plugin_activation_notice filter added

## [2.1.0] - 2019-06-04
### Added
- PluginRegistrator - no need for helper
### Changed
- license key -> api key; subscription -> license

## [2.0.2] - 2019-06-03
### Added
- Is using new field in plugin information section: description_base64

## [2.0.1] - 2019-04-30
### Fixed
- Remove call-time-pass-by-reference

## [2.0.0] - 2019-04-17
### Changed
- Renamed to wp-wpdesk-license
- Classes to integrate with helper moved to wp-wpdesk-helper

## [1.1.5] - 2019-04-10
### Fixed
- Fixed fatal error when some clousures are hooked in admin_notices #2

## [1.1.4] - 2019-04-04
### Fixed
- Fixed fatal error when some clousures are hooked in admin_notices

## [1.1.3] - 2019-03-28
### Fixed
- WordPress <5.0 compatibility PB-380

## [1.1.2] - 2019-03-27
### Fixed
- Fixed forced locale in translations PB-349

## [1.1.1] - 2019-03-26
### Fixed
- Fixed helper action removal PB-351

## [1.1.0] - 2019-03-26
### Added
- Supports translations

## [1.0.1] - 2019-03-25
### Fixed
- Can remove info about helper requirement in more ways
- Checks if WPDesk_Logger_Factory exists before using

## [1.0.0] - 2019-03-25
### Added
- Subscriptions