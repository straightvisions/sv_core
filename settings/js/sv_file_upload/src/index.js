import React, { useState, useEffect } from 'react';
import _ from 'lodash';

export default function FeaturedImage(props){
	_.noConflict();
	let frame;
	
	const runUploader = (event) => {
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if(frame){
			frame.open();
			return;
		}
		
		// Create a new media frame
		frame = wp.media({
			title: 'Select or Upload Media Of Your Chosen Persuasion',
			button: {
				text: 'Use this media',
			},
			multiple: false, // Set to true to allow multiple files to be selected
		});
		
		// Finally, open the modal on click
		frame.open();
	};
	
	return (
		<React.Fragment>
			<button type='button' onClick={runUploader}>
				Open Uploader
			</button>
		</React.Fragment>
	);
}