## [1.6.0] - 2019-05-21
### Added
- wpdesk_is_wp_log_capture_permitted filter to disable log capture

## [1.5.4] - 2019-05-06
### Fixed
- Exception: must be an instance of WC_Logger, instance of WPDesk\Logger\WC\WooCommerceMonologPlugin given

## [1.5.3] - 2019-04-25
### Fixed
- Exception while trying to disable log

## [1.5.2] - 2019-04-25
### Changed
- wp-notice 3.x

## [1.5.1] - 2019-04-23
### Changed
- Two files for custom loggers (default+custom)
### Fixed
- Tests

## [1.5.0] - 2019-04-18
### Changed
- Log file is unified with old way logger and all is logged in /wp-content/uploads/wpdesk-logs/wpdesk_debug.log
- Old static logger methods are deprecated
### Added
- All old way loggers are in deprecated dir and should work for old plugins
- Support for $shouldLoggerBeActivated static flag in factory - can return null logger

## [1.4.0] - 2019-01-21
### Changed
- WC integration now considers broken WC_Logger implementation
- Does not capture WC logger in WC < 3.5

## [1.3.1] - 2018-10-30
### Changed
- setDisableLog changes to disableLog

## [1.2.0] - 2018-10-29 
### Changed 
- getWPDeskFileName renamed to getFileName
- isWPDeskLogWorking renamed to isLogWorking
### Added
- most methods have $name parameter for using specific logger

## [1.1.1] - 2018-10-29
### Fixed
- should not capture all error - only log them
- boolean filter fixed

## [1.1.0] - 2018-10-29
### Added
- can disable logs using one bool
- less static variables

## [1.0.0] - 2018-10-28
### Added
- first stable version
- 80% coverage in integration tests