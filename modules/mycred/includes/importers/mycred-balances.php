<?php
if ( ! defined( 'myCRED_VERSION' ) ) exit;

/**
 * Import: Balances
 * @since 1.2
 * @version 1.3
 */
if ( ! class_exists( 'myCRED_Importer_Balances' ) ) :
	class myCRED_Importer_Balances extends WP_Importer {

		var $id            = '';
		var $file_url      = '';
		var $import_page   = '';
		var $delimiter     = '';
		var $posts         = array();
		var $imported      = 0;
		var $skipped       = 0;
		var $documentation = '';

		/**
		 * Construct
		 * @version 1.0
		 */
		public function __construct() {

			$this->import_page   = MYCRED_SLUG . '-import-balance';
			$this->delimiter     = empty( $_POST['delimiter'] ) ? ',' : (string) strip_tags( trim( $_POST['delimiter'] ) );
			$this->documentation = '';

		}

		/**
		 * Registered callback function for the WordPress Importer
		 * Manages the three separate stages of the CSV import process
		 * @version 1.0
		 */
		public function load() {

			$this->header();

			$load = true;
			$step = ( ! isset( $_GET['step'] ) ) ? 0 : absint( $_GET['step'] );
			if ( $step > 1 ) $step = 0;

			switch ( $step ) {

				case 1 :

					check_admin_referer( 'import-upload' );

					if ( $this->handle_upload() ) {

						if ( $this->id )
							$file = get_attached_file( $this->id );
						else
							$file = ABSPATH . $this->file_url;

						if ( $file !== false ) {

							add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

							$load = $this->import( $file );

						}

					}

				break;

			}

			if ( $load )
				$this->greet();

			$this->footer();

		}

		/**
		 * UTF-8 encode the data if `$enc` value isn't UTF-8.
		 * @version 1.0
		 */
		public function format_data_from_csv( $data, $enc ) {
			return ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
		}

		/**
		 * Handles the CSV upload and initial parsing of the file to prepare for
		 * displaying author import options
		 * @return bool False if error uploading or invalid file, true otherwise
		 * @version 1.1
		 */
		public function handle_upload() {

			if ( empty( $_POST['file_url'] ) ) {

				$file = wp_import_handle_upload();

				if ( isset( $file['error'] ) ) {

					echo '<div class="error notice notice-error is-dismissible"><p>' . esc_html( $file['error'] ) . '</p></div>';
					return false;

				}

				$this->id = (int) $file['id'];

			} else {

				if ( file_exists( ABSPATH . $_POST['file_url'] ) ) {

					$this->file_url = esc_attr( $_POST['file_url'] );

				} else {

					echo '<div class="error notice notice-error is-dismissible"><p>' . __( 'The file does not exist or could not be read.', 'twodayssss' ) . '</p></div>';
					return false;

				}

			}

			return true;

		}

		/**
		 * Import Function
		 * Handles the actual import based on a given file.
		 * @version 1.0
		 */
		public function import( $file ) {

			global $wpdb;

			$ran        = false;
			$show_greet = true;
			$loop       = 0;

			// Make sure the file exists
			if ( ! is_file( $file ) ) {

				echo '<div class="error notice notice-error is-dismissible"><p>' . __( 'The file does not exist or could not be read.', 'twodayssss' ) . '</p></div>';
				return true;

			}

			if ( function_exists( 'gc_enable' ) )
				gc_enable();

			@set_time_limit(0);
			@ob_flush();
			@flush();

			// Begin by opening the file
			if ( ( $handle = fopen( $file, "r" ) ) !== false ) {

				// Need to get the header of the CSV file so we know how many columns we are using
				$header        = fgetcsv( $handle, 0, $this->delimiter );
				$no_of_columns = sizeof( $header );

				// Make sure we have the correct number of columns
				if ( $no_of_columns == 3 || $no_of_columns == 4 ) {

					// Begin import loop
					while ( ( $row = fgetcsv( $handle, 0, $this->delimiter ) ) !== false ) {

						$log_entry = '';
						if ( $no_of_columns == 3 )
							list ( $identification, $balance, $point_type ) = $row;
						else
							list ( $identification, $balance, $point_type, $log_entry ) = $row;

						// Attempt to identify the user
						$user_id = mycred_get_user_id( $identification );

						// Failed to find the user - Next!
						if ( $user_id === false ) {
							$this->skipped ++;
							continue;
						}

						// Make sure the point type exists
						if ( ! mycred_point_type_exists( $point_type ) ) {

							// The point type column actually holds the log entry
							if ( $point_type != '' )
								$log_entry = $point_type;

							$point_type = MYCRED_DEFAULT_TYPE_KEY;

						}

						$mycred = mycred( $point_type );
						$method = trim( $_POST['method'] );

						// If a log entry should be added with the import
						if ( ! empty( $log_entry ) )
							$mycred->add_to_log( 'import', $user_id, $balance, $log_entry );

						// Add to the balance
						if ( $method == 'add' )
							$mycred->update_users_balance( $user_id, $balance );

						// Change the balance
						else
							$mycred->set_users_balance( $user_id, $balance );

						$loop ++;
						$this->imported++;

					}

					$show_greet = false;
					$ran        = true;

				} else {

					echo '<div class="error notice notice-error is-dismissible"><p>' . __( 'Invalid CSV file. Please consult the documentation for further assistance.', 'twodayssss' ) . '</p></div>';

				}

				fclose( $handle );

			}

			if ( $ran ) {
				echo '<div class="updated notice notice-success is-dismissible"><p>' . sprintf( __( 'Import complete - A total of <strong>%d</strong> balances were successfully imported. <strong>%d</strong> was skipped.', 'twodayssss' ), $this->imported, $this->skipped ) . '</p></div>';
				echo '<p><a href="' . admin_url( 'users.php' ) . '" class="button button-large button-primary">' . __( 'View Users', 'twodayssss' ) . '</a></p>';
			}

			do_action( 'import_end' );

			return $show_greet;

		}

		/**
		 * Render Screen Header
		 * @version 1.0
		 */
		public function header() {

			$label = __( 'Import Balances', 'twodayssss' );
			if ( MYCRED_DEFAULT_LABEL === 'twodayssss' )
				$label .= ' <a href="' . $this->documentation . '" target="_blank" class="page-title-action">' . __( 'Documentation', 'twodayssss' ) . '</a>';

			echo '<div class="wrap"><h1>' . $label . '</h1>';

		}

		/**
		 * Render Screem Fppter
		 * @version 1.0
		 */
		public function footer() {

			echo '</div>';

		}

		/**
		 * Greet Screen
		 * @version 1.0
		 */
		public function greet() {

			$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
			$size       = size_format( $bytes );
			$upload_dir = wp_upload_dir();
			$action_url = add_query_arg( array( 'import' => $this->import_page, 'step' => 1 ), admin_url( 'admin.php' ) );

			if ( ! empty( $upload_dir['error'] ) ) :

?>
<div class="error notice notice-error"><p><?php echo $upload_dir['error']; ?></p></div>
<?php

			else :

?>
<form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr( wp_nonce_url( $action_url, 'import-upload' ) ); ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th>
					<label for="upload"><?php _e( 'Choose a file from your computer:', 'twodayssss' ); ?></label>
				</th>
				<td>
					<input type="file" id="upload" name="import" size="25" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
					<small><?php printf( __( 'Maximum size: %s', 'twodayssss' ), $size ); ?></small>
				</td>
			</tr>
			<tr>
				<th>
					<label for="file_url"><?php _e( 'OR enter path to file:', 'twodayssss' ); ?></label>
				</th>
				<td>
					<?php echo ABSPATH . ' '; ?><input type="text" id="file_url" name="file_url" size="25" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="delimiter"><?php _e( 'Delimiter', 'twodayssss' ); ?></label>
				</th>
				<td>
					<input type="text" name="delimiter" id="delimiter" placeholder="," size="2" />
				</td>
			</tr>
			<tr>
				<th>
					<label><?php _e( 'Method', 'twodayssss' ); ?></label>
				</th>
				<td>
					<select name="method">
						<option value=""><?php _e( 'Replace current balances with the amount in this CSV file', 'twodayssss' ); ?></option>
						<option value="add"><?php _e( 'Adjust current balances according to the amount in this CSV file', 'twodayssss' ); ?></option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" class="button button-primary" value="<?php _e( 'Import', 'twodayssss' ); ?>" />
	</p>
</form>
<?php

			endif;

		}

		/**
		 * Added to http_request_timeout filter to force timeout at 60 seconds during import
		 * @return int 60
		 */
		public function bump_request_timeout( $val ) {

			return 60;

		}

	}
endif;
