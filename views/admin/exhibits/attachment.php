<?php
$item = $attachment->getItem();
$file = $attachment->getFile();
$stem = $block->getFormStem() . "[attachments][{$index}]";
?>
<div class="attachment" data-attachment-index="<?php echo html_escape($index); ?>">
    <div class="attachment-header">
        <div class="delete-toggle close button"><span class="screen-reader-text"><?php echo __('Close'); ?></a></div>
    </div>
    <div class="attachment-body">
        <?php if ($file): ?>
        <div class="attachment-background" style="background: url(<?php echo html_escape(metadata($file, 'square_thumbnail_uri')); ?>) center / cover"></div>
        <?php endif; ?>
        <h5>
            #<?php echo html_escape($item->id); ?>:
            <?php echo metadata($item, array('Dublin Core', 'Title')); ?>
        </h5>
        <?php echo $this->formHidden($stem . '[item_id]', $item->id); ?>
        <?php if ($file): ?>
        <?php echo $this->formHidden($stem . '[file_id]', $file->id); ?>
        <?php endif; ?>
        <?php echo $this->formHidden($stem . '[caption]', $attachment->caption); ?>
        <?php echo $this->formHidden($stem . '[order]', $index + 1, array('class' => 'attachment-order')); ?>
    </div>

    <span class="edit-attachment edit button"><a href="#"><?php echo __('Edit'); ?></a></span>
</div>
