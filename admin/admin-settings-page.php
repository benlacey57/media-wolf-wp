<div class="wrap">
  <h1>Media Wolf Settings</h1>

  <div class="dashboard-sections">
        <!-- Plugin Status Section -->
        <div class="dashboard-section">
            <h2><?php esc_html_e('Plugin Status', 'media-wolf'); ?></h2>
            <p><?php esc_html_e('The plugin is currently active and working as expected.', 'media-wolf'); ?></p>
            <p>
                <strong><?php esc_html_e('Last Scan:', 'media-wolf'); ?></strong>
                <?php echo esc_html(get_option('media_wolf_last_scan', __('No scans yet.', 'media-wolf'))); ?>
            </p>
        </div>

        <!-- Quick Links Section -->
        <div class="dashboard-section">
            <h2><?php esc_html_e('Quick Links', 'media-wolf'); ?></h2>
            <ul>
                <li><a href="<?php echo esc_url(admin_url('admin.php?page=media-wolf-security-facts')); ?>"><?php esc_html_e('Manage Security Facts', 'media-wolf'); ?></a></li>
                <li><a href="<?php echo esc_url(admin_url('admin.php?page=media-wolf-content-restriction')); ?>"><?php esc_html_e('Content Restriction Settings', 'media-wolf'); ?></a></li>
                <li><a href="<?php echo esc_url(admin_url('admin.php?page=media-wolf-woocommerce')); ?>"><?php esc_html_e('WooCommerce Settings', 'media-wolf'); ?></a></li>
            </ul>
        </div>

        <!-- Activity Log Section -->
        <div class="dashboard-section">
            <h2><?php esc_html_e('Recent Activity', 'media-wolf'); ?></h2>
            <?php
            $activity_log = get_option('media_wolf_activity_log', []);
            if (!empty($activity_log)) :
                echo '<ul>';
                foreach ($activity_log as $entry) {
                    echo '<li>' . esc_html($entry) . '</li>';
                }
                echo '</ul>';
            else :
                echo '<p>' . esc_html__('No recent activity.', 'media-wolf') . '</p>';
            endif;
            ?>
        </div>

        <!-- Help & Support Section -->
        <div class="dashboard-section">
            <h2><?php esc_html_e('Help & Support', 'media-wolf'); ?></h2>
            <p><?php esc_html_e('Need assistance? Visit our support page for FAQs, tutorials, and more.', 'media-wolf'); ?></p>
            <p>
                <a href="https://support.media-wolf.co.uk" target="_blank" rel="noopener noreferrer" class="button button-primary">
                    <?php esc_html_e('Visit Support Portal', 'media-wolf'); ?>
                </a>
            </p>
        </div>
    </div>
  </div>
</div>