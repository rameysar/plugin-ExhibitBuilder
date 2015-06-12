<?php

/**
 * Exhibit attachment view helper.
 * 
 * @package ExhibitBuilder\View\Helper
 */
class ExhibitBuilder_View_Helper_ExhibitAttachment extends Zend_View_Helper_Abstract
{
    /**
     * Return the markup for displaying an exhibit attachment.
     *
     * @param ExhibitBlockAttachment $attachment
     * @param array $fileOptions Array of options for file_markup
     * @param array $linkProps Array of options for exhibit_builder_link_to_exhibit_item
     * @param boolean $forceImage Whether to display the attachment as an image
     *  always Defaults to false.
     * @return string
     */
	
	public function exhibitAttachment($attachment1, $fileOptions = array(), $linkProps = array(), $forceImage = false, $zoom = false)
    {
        $item = $attachment1->getItem();
        $file = $attachment1->getFile();
        
   
		if ($file) {
            if (!isset($fileOptions['imgAttributes']['alt'])) {
                $fileOptions['imgAttributes']['alt'] = metadata($item, array('Dublin Core', 'Title'), array('no_escape' => true));
            }
            if ($forceImage) {
                $imageSize = isset($fileOptions['imageSize'])
                    ? $fileOptions['imageSize']
                    : 'fullsize';
                $image = file_image($imageSize, $fileOptions['imgAttributes'], $file);
                $html = exhibit_builder_link_to_exhibit_item($image, $linkProps, $item);
            } 
			if ($zoom) {
                $record = get_record_by_id('Item',$attachment1->item_id);
                $view = get_view();
                $html = $view->openLayersZoom()->zoom($record); 
			} else {
                if (!isset($fileOptions['linkAttributes']['href'])) {
                    $fileOptions['linkAttributes']['href'] = exhibit_builder_exhibit_item_uri($item);
                }
                $html = file_markup($file, $fileOptions, null);
                
            }
        } else if($item) {
            $html = exhibit_builder_link_to_exhibit_item(null, $linkProps, $item);
        }

        // Don't show a caption if we couldn't show the Item or File at all
        if (isset($html)) {
            $html .= $this->view->exhibitAttachmentCaption($attachment1);
        } else {
            $html = '';
        }
		

        return apply_filters('exhibit_attachment_markup', $html,
            compact('attachment', 'fileOptions', 'linkProps', 'forceImage')
        );
		
public function exhibitAttachment($attachment2, $fileOptions = array(), $linkProps = array(), $forceImage = false, $zoom = false)
    {
        $item = $attachment2->getItem();
        $file = $attachment2->getFile();
        
   
		if ($file) {
            if (!isset($fileOptions['imgAttributes']['alt'])) {
                $fileOptions['imgAttributes']['alt'] = metadata($item, array('Dublin Core', 'Title'), array('no_escape' => true));
            }
            if ($forceImage) {
                $imageSize = isset($fileOptions['imageSize'])
                    ? $fileOptions['imageSize']
                    : 'fullsize';
                $image = file_image($imageSize, $fileOptions['imgAttributes'], $file);
                $html = exhibit_builder_link_to_exhibit_item($image, $linkProps, $item);
            } 
			if ($zoom) {
                $record = get_record_by_id('Item',$attachment2->item_id);
                $view = get_view();
                $html = $view->openLayersZoom()->zoom($record); 
			} else {
                if (!isset($fileOptions['linkAttributes']['href'])) {
                    $fileOptions['linkAttributes']['href'] = exhibit_builder_exhibit_item_uri($item);
                }
                $html = file_markup($file, $fileOptions, null);
                
            }
        } else if($item) {
            $html = exhibit_builder_link_to_exhibit_item(null, $linkProps, $item);
        }

        // Don't show a caption if we couldn't show the Item or File at all
        if (isset($html)) {
            $html .= $this->view->exhibitAttachmentCaption($attachment2);
        } else {
            $html = '';
        }
		

        return apply_filters('exhibit_attachment_markup', $html,
            compact('attachment', 'fileOptions', 'linkProps', 'forceImage')
        );
    }

    /**
     * Return the markup for an attachment's caption.
     *
     * @param ExhibitBlockAttachment $attachment
     * @return string
     */
	 
	 
	 //I'll deal with this later
    protected function _caption($attachment)
    {
        if (!is_string($attachment['caption']) || $attachment['caption'] == '') {
            return '';
        }

        $html = '<div class="exhibit-item-caption">'
              . $attachment['caption']
              . '</div>';

        return apply_filters('exhibit_attachment_caption', $html, array(
            'attachment' => $attachment
        ));
    }
}
