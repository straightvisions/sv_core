<style data-id="<?php echo $this->get_prefix(); ?>">
	.sv_ajax_fragmented_requests_start > span > .label{
		padding-right:10px;
	}
	.sv_ajax_fragmented_requests_start .processing,
	.sv_ajax_fragmented_requests_start .finished{
		display:none;
	}
</style>
<form class="sv_ajax_fragmented_requests" data-id="<?php echo $prefix; ?>">
	<p>
		<button class="sv_ajax_fragmented_requests_start button button-primary">
			<span class="start"><span class="label"><?php _e('Start', $this->get_prefix()); ?></span><i class="fas fa-play"></i></span>
			<span class="processing"><span class="label"><?php _e('Processing', $this->get_prefix()); ?></span><i class="fas fa-cog fa-spin"></i></span>
			<span class="finished"><span class="label"><?php _e('Finished', $this->get_prefix()); ?></span><i class="fas fa-check-circle"></i></span>
		</button>
		<?php if(get_transient($prefix)){ ?>
			<button class="sv_ajax_fragmented_requests_continue button button-secondary"><?php _e('Continue Import from Cycle', $this->get_prefix()); ?> <?php echo get_transient($prefix); ?></button>
		<?php } ?>
	</p>
	<div class="sv_ajax_fragmented_requests_progress_bar"></div>
	<div class="sv_ajax_fragmented_requests_progress_text"><div class="left"></div><div class="center"></div><div class="right"></div></div>
	<div class="sv_ajax_fragmented_requests_log"></div>
</form>