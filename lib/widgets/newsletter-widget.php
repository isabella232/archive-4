<?php
/**
 * Newsletter Subscription widget to easily let visitors subscribe to your newsletter *
 *
 * @package      Yoast Newsletter Subscription widget
 * @since        1.0.0
 * @author       Joost de Valk <joost@yoast.com>
 * @copyright    Copyright (c) 2013, Joost de Valk
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Sanity check to prevent double inclusion of this class.
if ( ! class_exists( 'YST_NewsletterSubscription_Widget' ) ) {
	class YST_NewsletterSubscription_Widget extends WP_Widget {

		/**
		 * @var array The variables used in this newsletter subscription widget, the keys are the captions, which is why they need .
		 */
		var $vars = array();

		/**
		 * @var array The defaults for the values of this newsletter subscription
		 */
		var $defaults = array(
			'title'      => '',
			'text'       => '',
			'name'       => '',
			'email'      => '',
			'faf'        => '',
			'extrahtml'  => '',
			'name_name'  => 'yst_ns_name',
			'name_email' => 'yst_ns_email',
			'hide_name'  => false
		);

		/**
		 * Constructor
		 **/
		public function __construct() {

			$this->vars = array(
				__( 'Form Title', 'yoast-theme' )          => 'title',
				__( 'Text', 'yoast-theme' )                => 'text',
				__( 'Name', 'yoast-theme' )                => 'name',
				__( 'E-mail address', 'yoast-theme' )      => 'email',
				__( 'Form Action Field', 'yoast-theme' )   => 'faf',
				__( 'Extra HTML', 'yoast-theme' )          => 'extrahtml',
				__( 'Name of name-field', 'yoast-theme' )  => 'name_name',
				__( 'Name of email-field', 'yoast-theme' ) => 'name_email',
				__( 'Hide name', 'yoast-theme' )           => 'hide_name',
			);

			$widget_ops = array( 'classname' => 'widget-yns' );
			$this->WP_Widget( 'yst_yns_widget', __( 'Yoast &mdash; Newsletter Subscriptions', 'yoast-theme' ), $widget_ops );
		}

		/**
		 * Outputs the HTML for this widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     An array of standard parameters for widgets in this theme
		 * @param array $instance An array of settings for this widget instance
		 *
		 * @return void Echoes its output
		 **/
		public function widget( $args, $instance ) {
			// Return if no value is entered for the Form Action Field
			if ( ! isset( $instance['faf'] ) || empty( $instance['faf'] ) )
				return;

			$out = '';
			if ( isset ( $instance['title'] ) && ! empty( $instance['title'] ) ) {
				$out .= '<h4 class="widget-title">' . $instance['title'] . '</h4>';
				if (isset ($instance['text']) && ! empty ($instance['text']))
					$out .= '<p class="newslettersubscription-text">' . $instance['text'] . '</p>';
				$out .= '<p><form name="' . $instance['title'] . '" method=post action="' . $instance['faf'] . '">';
			}
			else {
				if (isset ($instance['text']) && ! empty ($instance['text']))
				$out .= '<p class="newslettersubscription-text">' . $instance['text'] . '</p>';
				$out .= '<p><form name="yst-newslettersubscription-form" method=post action="' . $instance['faf'] . '">';

			}

			if ( ! isset ( $instance['hide_name'] ) || ! $instance['hide_name'] ) {
				$out .= '<label for="' . $instance['name_name'] . '">' . __( 'Name', 'yoast-theme' ) . '</label>';
				$out .= '<input type="text" name="' . $instance['name_name'] . '" placeholder="John Doe">';
			}
			$out .= '<label for="' . $instance['name_email'] . '">' . __( 'E-mail address', 'yoast-theme' ) . '</label>';
			$out .= '<input type="text" name="' . $instance['name_email'] . '" pattern="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$" placeholder="john@doe.com" autocomplete="on" required>';
			$out .= '<input type="submit" value="&#9654;" />';
			$out .= $instance['extrahtml'];
			$out .= '</form>';
			$out .= '</p>';


			echo $args['before_widget'];
			echo $out;
			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 *
		 * @return string|void echoes output to the screen
		 */
		public function form( $instance ) {

			$instance = wp_parse_args( (array) $instance, $this->defaults );
			echo '<p class="ssl_hint">' . __( 'We suggest you use SSL for all forms. Read more about setting up SSL on <a href="http://yoast.com/wordpress-ssl-setup/" alt="WordPress SSL Setup by Yoast" target="_blank">Yoast.com</a>.', 'yoast-theme' ) . '</p>';

			foreach ( $this->vars as $label => $var ) {
				$varlabel = $label;
				echo '<p>';
				$label      = '<label for="' . $this->get_field_name( $var ) . '">' . $label . '</label>';
				$input_attr = 'title="' . $varlabel . '" name="' . $this->get_field_name( $var ) . '" id="' . $this->get_field_id( $var ) . '" ';

				if ( $var == 'name' || $var == 'email' ) {
					continue;
				}
				else if ( $var == 'faf' ) {
					$input_attr .= 'required ';
					echo $label;
					echo '<input class="widefat" ' . $input_attr . ' type="text" placeholder="For example; Mailchimp." value="' . esc_html( $instance[$var] ) . '" />';
					echo "<span class=\"yst_required_field\">This is a required field</span>";
				}
				else if ( $var == 'extrahtml' ) {
					$input_attr .= 'required ';
					echo $label;
					echo '<input class="widefat" ' . $input_attr . ' type="text" placeholder="For example; <input type=hidden ... />" value="' . esc_html( $instance[$var] ) . '" />';
				}
				else if ( $var == 'hide_name' ) {
					echo '<input class="checkbox" ' . $input_attr . ' type="checkbox" ' . checked( $instance[$var], true, false ) . '/> ';
					echo $label;
				}
				else {
					echo $label;
					echo '<input class="widefat" ' . $input_attr . ' type="text" value="' . esc_html( $instance[$var] ) . '" />';
				}
				echo '</p>';
			}
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$new_instance['title']      = strip_tags( trim( $new_instance['title'] ) );
			$new_instance['text']       = strip_tags( trim( $new_instance['text'] ) );
			$new_instance['name']       = strip_tags( trim( $new_instance['name'] ) );
			$new_instance['email']      = strip_tags( trim( $new_instance['email'] ) );
			$new_instance['faf']        = strip_tags( trim( $new_instance['faf'] ) );
			$new_instance['name_name']  = strip_tags( trim( $new_instance['name_name'] ) );
			$new_instance['name_email'] = strip_tags( trim( $new_instance['name_email'] ) );

			if ( isset( $new_instance['hide_name'] ) )
				$new_instance['hide_name'] = true;

			return $new_instance;
		}
	}

	/**
	 * Register the newsletter subscription widget.
	 */
	function yst_register_yns_widget() {
		register_widget( 'YST_NewsletterSubscription_Widget' );
	}

	add_action( 'widgets_init', 'yst_register_yns_widget' );

}