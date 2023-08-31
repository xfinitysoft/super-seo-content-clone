<?php
// Exit if directly access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'report';
?>
<div class="warp">
    <div id="icon-options-general" class="icon32"></div>
    <h1>
        <?php esc_html_e('Support' , 'super-seo-content-cloner') ?>
    </h1>
    <nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
        <a class="nav-tab <?php  if($tab =='report' ){ echo 'nav-tab-active'; } ?>" href="?page=xsspd-support&tab=report" class="nav-tab">
                <?php esc_html_e( 'Report a bug' , 'super-seo-content-cloner' ); ?>
        </a>
        <a class="nav-tab <?php  if($tab =='request' ){ echo 'nav-tab-active'; } ?>" href="?page=xsspd-support&tab=request" class="nav-tab">
                <?php esc_html_e( 'Request a Feature' , 'super-seo-content-cloner' ); ?>
        </a>
        <a class="nav-tab <?php  if($tab =='hire' ){ echo 'nav-tab-active'; } ?>" href="?page=xsspd-support&tab=hire" class="nav-tab">
                <?php esc_html_e( 'Hire US' , 'super-seo-content-cloner' ); ?>
        </a>
        <a class="nav-tab <?php  if($tab =='review' ){ echo 'nav-tab-active'; } ?>" href="?page=xsspd-support&tab=review" class="nav-tab">
                <?php esc_html_e( 'Review' , 'super-seo-content-cloner' ); ?>
        </a>

    </nav>
    <div class="tab-content">
        <?php switch ($tab) {
            case 'request':
                ?>
                <div class="xs-send-email-notice xsspd-top-margin">
                    <p></p>
                    <button type="button" class="notice-dismiss xs-notice-dismiss"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.', 'super-seo-content-cloner');?></span></button>
                </div>
                <form method="post" class="xsspd_support_form">
                    <input type="hidden" name="type" value="request">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th>
                                    <label for='xsspd_name'><?php esc_html_e('Your Name:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="xsspd_name" name="xsspd_name" required>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for="xsspd_email"><?php esc_html_e('Your Email:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <input type="email" id="xsspd_email" name="xsspd_email" required>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for="xsspd_message"><?php esc_html_e('Message:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <textarea id="xsspd_message" name="xsspd_message" rows="12", cols="47" required></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="input-group">
                        <?php submit_button(__( 'Send', 'super-seo-content-cloner' ), 'primary xsspd-send-mail'); ?>
                        <span class="spinner xsspd-mail-spinner"></span> 
                    </div>
                </form>
                <?php
                break;
            case 'hire':
                ?>
                <h2 class="xsspd-top-margin"><?php esc_html_e("Hire us for Customization and Development of WordPress Plugins and Themes." , 'super-seo-content-cloner') ?></h2>
                <div class="xs-send-email-notice xsspd-top-margin">
                    <p></p>
                    <button type="button" class="notice-dismiss xs-notice-dismiss"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.' ,'super-seo-content-cloner');?></span></button>
                </div>
                <form method="post" class="xsspd_support_form">
                    <input type="hidden" name="type" value="hire">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th>
                                    <label for='xsspd_name'><?php esc_html_e('Your Name:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="xsspd_name" name="xsspd_name" required="required">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for="xsspd_email"><?php esc_html_e('Your Email:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <input type="email" id="xsspd_email" name="xsspd_email" required="required">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for="xsspd_message"><?php esc_html_e('Message:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <textarea id="xsspd_message" name="xsspd_message" rows="12", cols="47" required="required"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="input-group">
                        <?php submit_button(__( 'Send', 'super-seo-content-cloner' ), 'primary xsspd-send-mail'); ?>
                        <span class="spinner xsspd-mail-spinner"></span> 
                    </div>
                </form>
                <?php
                break;
            case 'review':
            ?>
                <p class="about-description xsspd-top-margin"><?php esc_html_e("If you like our plugin and support than kindly share your  " , 'super-seo-content-cloner') ?> <a href="https://codecanyon.net/item/woocommerce-advanced-product-duplicator/22147932" target="_blank"> <?php esc_html_e("feedback" , 'super-seo-content-cloner') ?> </a><?php esc_html_e("Your feedback is valuable." , 'super-seo-content-cloner') ?> </p>
            <?php
                break;
            default:
                ?>
                <div class="xs-send-email-notice xsspd-top-margin">
                    <p></p>
                    <button type="button" class="notice-dismiss xs-notice-dismiss"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.', 'super-seo-content-cloner' );?></span></button>
                </div>
                <form method="post" class="xsspd_support_form">
                    <input type="hidden" name="type" value="report">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th>
                                    <label for='xsspd_name'><?php esc_html_e('Your Name:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="xsspd_name" name="xsspd_name" required="required">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for="xsspd_email"><?php esc_html_e('Your Email:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <input type="email" id="xsspd_email" name="xsspd_email" required="required">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for="xsspd_message"><?php esc_html_e('Message:', 'super-seo-content-cloner'); ?></label>
                                </th>
                                <td>
                                    <textarea id="xsspd_message" name="xsspd_message" rows="12", cols="47" required="required"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="input-group">
                        <?php submit_button(__( 'Send', 'super-seo-content-cloner' ), 'primary xsspd-send-mail'); ?>
                        <span class="spinner xsspd-mail-spinner"></span> 
                    </div>
                </form>
                <?php
                break;
        }
        ?>
    </div>
</div>