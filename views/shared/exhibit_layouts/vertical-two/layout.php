<?php
$galleryPosition = isset($options['gallery-position'])
    ? html_escape($options['gallery-position'])
    : 'left';
$galleryFileSize = isset($options['gallery-file-size'])
    ? html_escape($options['gallery-file-size'])
    : 'fullsize';
$captionPosition = isset($options['captions-position'])
    ? html_escape($options['captions-position'])
    : 'center';
?>

<div class="gallery <?php if ($showcaseFile || !empty($text)) echo "$galleryPosition"; ?> captions-<?php echo $captionPosition; ?>">
    <?php echo $this->exhibitAttachmentGallery($attachments, array('imageSize' => $galleryFileSize)); ?>
</div>