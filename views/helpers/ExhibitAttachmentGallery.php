<?php

/**
 * Exhibit gallery view helper.
 * 
 * @package ExhibitBuilder\View\Helper
 */
class ExhibitBuilder_View_Helper_ExhibitAttachmentGallery extends Zend_View_Helper_Abstract
{
    /**
     * Return the markup for a gallery of exhibit attachments.
     *
     * @uses ExhibitBuilder_View_Helper_ExhibitAttachment
     * @param ExhibitBlockAttachment[] $attachments
     * @param array $fileOptions
     * @param array $linkProps
     * @return string
     */
	 
	 /**
	 *This is where I think the specification needs to go.
	 *$attachments --> $attachment1, $attachment 2
	 *but the division would have to happen before public function
	 */
	 
    public function exhibitAttachmentGallery($attachment1, $fileOptions = array(), $linkProps = array(), $forceImage = false, $zoom = false)
    {
        if (!isset($fileOptions['imageSize'])) {
            $fileOptions['imageSize'] = 'square_thumbnail';
        }
        
		$html .= '<div class="exhibit-item exhibit-gallery-item">';
			$html .= $this->view->exhibitAttachment($attachment1, $fileOptions, $linkProps, $forceImage, $zoom);
        $html .= '</div>';
        $html = '';
    
        return apply_filters('exhibit_attachment_gallery_markup', $html,
            compact('attachments', 'fileOptions', 'linkProps'));
    }
	
	public function exhibitAttachmentGallery($attachment2, $fileOptions = array(), $linkProps = array(), $forceImage = false, $zoom = false)
    {
        if (!isset($fileOptions['imageSize'])) {
            $fileOptions['imageSize'] = 'square_thumbnail';
        }
        
		$html .= '<div class="exhibit-item exhibit-gallery-item">';
			$html .= $this->view->exhibitAttachment($attachment2, $fileOptions, $linkProps, $forceImage, $zoom);
        $html .= '</div>';
        $html = '';
        }
    
        return apply_filters('exhibit_attachment_gallery_markup', $html,
            compact('attachments', 'fileOptions', 'linkProps'));
    }
}
