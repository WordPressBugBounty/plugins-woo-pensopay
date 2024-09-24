<?php

class WC_PensoPay_Anyday extends WC_PensoPay_Instance {

	public $main_settings = null;

	public function __construct() {
		parent::__construct();

        // Get gateway variables
        $this->id = 'anyday-split';

        $this->method_title = 'Pensopay - Anyday';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

        add_filter( 'woocommerce_available_payment_gateways', [ $this, 'maybe_disable_gateway' ] );
        add_filter( 'woocommerce_pensopay_cardtypelock_anyday-split', [ $this, 'filter_cardtypelock' ] );
    }


	/**
	 * init_form_fields function.
	 *
	 * Initiates the plugin settings form fields
	 *
	 * @access public
	 * @return array
	 */
	public function init_form_fields(): void {
        $this->form_fields = [
            'enabled' => [
                'title' => __( 'Enable', 'woo-pensopay' ),
                'type' => 'checkbox',
                'label' => __( 'Enable Anyday payments', 'woo-pensopay' ),
                'default' => 'no'
            ],
            '_Shop_setup' => [
                'type' => 'title',
                'title' => __( 'Shop setup', 'woo-pensopay' ),
            ],
            'title' => [
                'title' => __( 'Title', 'woo-pensopay' ),
                'type' => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woo-pensopay' ),
                'default' => __('Anyday', 'woo-pensopay')
            ],
            'description' => [
                'title' => __( 'Customer Message', 'woo-pensopay' ),
                'type' => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'woo-pensopay' ),
                'default' => __('Pay with Anyday', 'woo-pensopay')
            ],
        ];
    }


	/**
	 * filter_cardtypelock function.
	 *
	 * Sets the cardtypelock
	 *
	 * @access public
	 * @return string
	 */
	public function filter_cardtypelock() {
		return 'anyday-split';
	}

	/**
	 * @param array $gateways
	 */
	public function maybe_disable_gateway( $gateways ) {
		if ( isset( $gateways[ $this->id ] ) && is_checkout() && ( $cart = WC()->cart ) ) {
			$cart_total = (float) $cart->get_total( 'edit' );
			$cart_min   = 300;
			$cart_max   = 30000;

			if ( ! ( $cart_total >= $cart_min && $cart_total <= $cart_max ) || 'DKK' !== strtoupper( get_woocommerce_currency() ) ) {
				unset( $gateways[ $this->id ] );
			}
		}

		return $gateways;
	}
}
