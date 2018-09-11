	jQuery( document ).ready( function() {

        /* @todo: allow multiple upload fields */
        /*jQuery('.sv_setting_upload_uploader').mouseenter(function () {
                    uploader.setOption("browse_button", this); //Assign the ID of the pickfiles button to pluploads browse_button
        });*/

		if( jQuery( '.sv_setting_upload_uploader' ).length > 0 ) {
			// file uploaded
			uploader.bind( 'FileUploaded', function( up, file, response ) {
				response = jQuery.parseJSON( response.response );

				if( response['status'] == 'success' ) {
					console.log( 'Success', up, file, response );
				} else {
					console.log( 'Error', up, file, response );
				}

			} );
		}

	} );