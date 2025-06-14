<?php
/* @var $options array contains all the options the current block we're ediging contains */
/* @var $controls NewsletterControls */
/* @var $fields NewsletterFields */

$controls->data['block_style'] = '';
?>

<p>
    <?php echo sprintf(__('Company data can be globally set on <a href="%s" target="_blank">company info panel</a>.', 'newsletter'), '?page=newsletter_main_info'); ?><br>
    Use an image block to have a single top banner.
</p>

<?php
$fields->select('block_layout', __('Layout', 'newsletter'), [
    'default' => __('Default', 'newsletter'),
    'logo' => __('Only the logo', 'newsletter'),
    'titlemotto' => 'Title and motto'
        ], ['after-rendering' => 'reload'])
?>

<?php
$fields->block_style('', [
    'default' => __('Default', 'newsletter'),
    'inverted' => __('Inverted', 'newsletter'),
    'boxed' => __('Boxed', 'newsletter'),
        ], ['after-rendering' => 'reload'])
?>

<div class="psource-accordion">
    <div class="psource-accordion-item active">
        <button class="psource-accordion-header"><?php esc_html_e('Fonts', 'newsletter'); ?></button>
        <div class="psource-accordion-content">
            <?php
            $fields->font('title_font', __('Title', 'newsletter'), [
                'family_default' => true,
                'size_default' => true,
                'weight_default' => true
            ])
            ?>

            <?php
            $fields->font('font', __('Text', 'newsletter'), [
                'family_default' => true,
                'size_default' => true,
                'weight_default' => true
            ])
            ?>
        </div>
    </div>
    <div class="psource-accordion-item">
        <button class="psource-accordion-header"><?php esc_html_e('Logo', 'newsletter'); ?></button>
        <div class="psource-accordion-content">
            <?php $fields->number('logo_width', __('Width')) ?>
        </div>
    </div>
    <div class="psource-accordion-item">
        <button class="psource-accordion-header"><?php esc_html_e('Commons', 'newsletter'); ?></button>
        <div class="psource-accordion-content">
            <?php $fields->block_commons() ?>
        </div>
    </div>
</div>