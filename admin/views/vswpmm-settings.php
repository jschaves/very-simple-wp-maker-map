<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    exit();
}
?>
<div class="wrap">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">

		<!-- Identify your business so that you can collect the payments. -->
		<input type="hidden" name="business" value="juan.cha63@gmail.com">

		<!-- Specify a Donate button. -->
		<input type="hidden" name="cmd" value="_donations">

		<!-- Specify details about the contribution -->
		<input type="hidden" name="item_name" value="Very Sinple WordPress Maker Map (WordPress plugin)">
		<input type="hidden" name="currency_code" value="EUR">

		<!-- Display the payment button. -->
		<input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate">
		<img alt="" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >

	</form>
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<h3 class="vswpmm-help-maker-map" style="display:none"><?php esc_html_e( 'The data was inserted in the form. Make the changes and then save the form', 'very-simple-wp-maker-map' ); ?></h3>
    <form method="post" autocomplete="off" id="vswpmm-maker-map-save" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
        <div id="universal-message-container">
            <h2><?php echo  esc_html_e( 'Maker Map Configurator', 'very-simple-wp-maker-map' ); ?></h2>
            <div class="options">
				<p>
					<label><?php esc_html_e( 'Address', 'very-simple-wp-maker-map' ); ?></label>
					<br />
					<input placeholder="seventh avenue new york" name="vswpmm-address" id="vswpmm-address" type="text" style="width:300px" class="vswpmm-new" value="" maxlength="100" required />
				</p>
                <p>
                    <label><?php esc_html_e( 'Width map', 'very-simple-wp-maker-map' ); ?></label>
                    <br />
                    <input style="width:65px" id="vswpmm-width-maker-map" name="vswpmm-width-maker-map" type="number" class="vswpmm-new" min="10" max="1999" value="50" required />%
                </p>
                <p>
                    <label><?php esc_html_e( 'Height map', 'very-simple-wp-maker-map' ); ?></label>
                    <br />
                    <input style="width:65px" id="vswpmm-height-maker-map" name="vswpmm-height-maker-map" type="number" class="vswpmm-new" min="10" max="1999" value="400" required />px
                </p>
				<p>	
					<label><?php esc_html_e( 'Marker title', 'very-simple-wp-maker-map'  ); ?></label>
					<br />
					<input name="vswpmm-maker-title" type="text" style="width:300px" class="vswpmm-new" id="vswpmm-maker-title" value="" maxlength="100" required />
				</p>
				<p>
					<label><?php esc_html_e( 'Color of the text', 'very-simple-wp-maker-map'  ); ?></label>
					<br />
					<input name="vswpmm-maker-text-color" id="vswpmm-maker-text-color" class="vswpmm-add-color-text-new" type="color" value="#000000" required />
				</p>
				<p>
					<label><?php esc_html_e( 'Marker background color', 'very-simple-wp-maker-map'  ); ?></label>
					<br />
					<input name="vswpmm-background-text-color" id="vswpmm-background-text-color" class="vswpmm-add-color-text-new" type="color" value="#ffffff" required />
				</p>
				<p>
					<label><?php esc_html_e( 'Marker text, supports tag <a>', 'very-simple-wp-maker-map'  ); ?></label>
					<br />
					<textarea name="vswpmm-maker-text" rows="10" class="vswpmm-new" style="width:300px" id="vswpmm-maker-text" cols="30" value="" required ></textarea>
				</p>
				<input id="vswpmm-lon-lat-maker-map-save" type="hidden" name="vswpmm-lon-lat-maker-map-save" value="" required />
				<input id="vswpmm-id-maker-map-edit" type="hidden" name="vswpmm-edit" value="null" />
				<input type="hidden" name="action" value="vswpmm">
			</div>
		</div>
        <?php
            wp_nonce_field( 'settings-save', 'id-message' );
            submit_button( __( 'Save' ) );
        ?>
		<input type="button" id="vswpmm-reset" class="button button-primary" value="Reset" />
    </form>
</div>
<br>
<p><a id="vswpmm-link-data-maker-map" class="vswpmm-preview-maker-map" ><?php esc_html_e( 'Preview' ); ?></a></p>
<div id="vswpmm-paint"></div>
<br />
<br />
<h3><?php esc_html_e( 'Marker text, supports tag <a>', 'very-simple-wp-maker-map' ); ?></h3>
<p><?php esc_html_e( 'Click Copy on the selected map and paste the short code on the page where the map should appear.', 'very-simple-wp-maker-map' ); ?></p>
<br />
<div id="vswpmm-list-maker-map">
<?php 
	$values = esc_attr( $this->deserializer_vswpmm->get_value( 'very\_simple\_wp\_maker\_map\_%' ) );
	if( ! empty( $values ) ) {
		$values = explode( '#makermap#', $values );
		if( count( $values ) > 0 ) {
			foreach( $values as $value ) {
				$idMakerMap = explode( '[', $value );
				$idMakerMap = explode( ',', $idMakerMap[1] );
				$deleteEditId = explode( '=', $idMakerMap[0] );
				$title = $idMakerMap[1];
?>
				<table cellspacing='0' class="vswpmm-ul-maker-map">
					<tr>
						<td class="vswpmm-preview-maker-map">
							[<?php echo $idMakerMap[0] . ' ' . $title; ?>]
						</td>
						<td class="vswpmm-array-data-maker-map"> 
							<span class="vswpmm-view-maker-map" id="<?php echo $deleteEditId[1]; ?>" viewMakerMap="<?php echo str_replace( array( '[', ']' ), array( '', '' ), $value ); ?>"></span>
							<input type="button" vswpmmId="<?php echo $deleteEditId[1]; ?>" class="button button-primary vswpmm-view-input" value="<?php echo __( 'Edit' ); ?>" />
						</td>
						<td>
						</td>
						<td>
							<form class="vswpmm-form-maker-map" method="post" autocomplete="off" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
								<input type="hidden" name="delete" value="<?php echo $deleteEditId[1]; ?>" />
								<input type="hidden" name="action" value="vswpmm">
							<?php 
								wp_nonce_field( 'settings-save', 'id-message' );
								submit_button( __( 'Delete' ) );
							?>
							</form>
						</td>
						<td>
						</td>
						<td>
							<input type="submit" copy="[<?php echo $idMakerMap[0] . ' ' . $title; ?>]" class="button button-primary vswpmm-copy" value="<?php echo _e( 'Copy' ); ?> " />
						</td>
						<td>
						</td>
					</tr>
				</table>
<?php
			}	
		} else {
			echo _e( 'Nothing saved', 'very-simple-wp-maker-map' );
		}
	}
?>
</div>
<span id="vswpmm-js-map-new"></span>