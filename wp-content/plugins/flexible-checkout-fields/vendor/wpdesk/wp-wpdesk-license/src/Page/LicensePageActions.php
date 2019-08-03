<?php

namespace WPDesk\License\Page;

use WPDesk\License\Page\License\Action\LicenseActivation;
use WPDesk\License\Page\License\Action\LicenseDeactivation;
use WPDesk\License\Page\License\Action\Nothing;

/**
 * Action factory.
 *
 * @package WPDesk\License\Page\License
 */
class LicensePageActions {
	/**
	 * Creates action object according to given param
	 *
	 * @param string $action
	 *
	 * @return Action
	 */
	public function create_action( $action ) {
		if ( $action === 'activate' ) {
			return new LicenseActivation();
		}

		if ( $action === 'deactivate' ) {
			return new LicenseDeactivation();
		}

		return new Nothing();
	}
}
