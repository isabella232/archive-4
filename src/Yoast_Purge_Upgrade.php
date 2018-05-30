<?php

final class Yoast_Purge_Upgrade {
	/**
	 * @var Yoast_Purge_Options
	 */
	private $options;

	/**
	 * Yoast_Purge_Upgrade constructor.
	 *
	 * @param Yoast_Purge_Options $options
	 */
	public function __construct( Yoast_Purge_Options $options ) {
		$this->options = $options;
	}

	/**
	 * Registers WordPress hooks and filters.
	 *
	 * @return void
	 */
	public function register_hooks() {
		$this->run();
	}

	/**
	 * Runs upgrades, if applicable.
	 */
	private function run() {
		$version = $this->options->get_version();
		if ( $version === YOAST_PURGE_VERSION ) {
			return;
		}

		$this->options->set_default_options();

		if ( version_compare( $version, '1.0.0', '<' ) ) {
			$this->upgrade_100();
		}

		$this->finish_up();
	}

	/**
	 * Completes the upgrade routine.
	 *
	 * @return void
	 */
	private function finish_up() {
		$this->options->set_version( YOAST_PURGE_VERSION );

		// Flush the sitemap cache, to make sure the changes are picked up.
		WPSEO_Sitemaps_Cache::clear();
	}

	/**
	 * Upgrades to version 1.0.0
	 */
	private function upgrade_100() {
		// Disable the attachment pages and redirect them to the attachment itself.
		WPSEO_Options::set( 'disable-attachment', true );

		// Make sure the Purge messages will never be shown again.
		WPSEO_Options::set( 'is-media-purge-relevant', false );
	}
}
