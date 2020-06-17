<div class="sv_setting sv_<?php echo $this->get_type();?>_parent" data-sv_prefix="<?php echo $this->get_parent()->get_parent()->get_prefix(); ?>" data-sv_field_id="<?php echo $this->get_field_id(); ?>">
    <div class="sv_setting_header">
        <h4>
			<?php echo $props['title']; ?>
			<?php if($props['description']){ ?>
                <i class="fas fa-info-circle"></i>
                <div class="sv_setting_description"><?php echo $props['description']; ?></div>
			<?php } ?>
        </h4>
		<?php if($this->get_is_responsive()){ ?>
            <?php if($this->get_is_expert_mode()){ ?>
                <div class="sv_setting_responsive_force sv_setting_responsive_headline_button">
                    <i class="fas fa-coffee" title="Force setting for all media sizes."></i>
                </div>
            <?php } ?>
            <div class="sv_setting_responsive_select">
                <i class="fas fa-mobile active" data-sv_setting_responsive_select="mobile"></i>
                <i class="fas fa-mobile fa-rotate-90" data-sv_setting_responsive_select="mobile_landscape"></i>
                <i class="fas fa-tablet" data-sv_setting_responsive_select="tablet"></i>
                <i class="fas fa-tablet fa-rotate-90" data-sv_setting_responsive_select="tablet_landscape"></i>
                <i class="fas fa-desktop" data-sv_setting_responsive_select="desktop"></i>
            </div>
		<?php } ?>
    </div>
	<?php echo $settings_html; ?>
</div>