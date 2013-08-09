if (typeof Omeka === 'undefined') {
    Omeka = {};
}
Omeka.ExhibitBuilder = {};

(function ($) {
    Omeka.ExhibitBuilder.setUpBlocks = function(blockFormUrl) {
        function sortAttachments(ancestor) {
            $(ancestor).find('.selected-item-list').sortable({
                items: '> .attachment',
                revert: 200,
                placeholder: 'ui-sortable-highlight',
                tolerance: 'pointer',
                stop: function () {
                    $(this).find('.attachment-order').each(function(index) {
                        $(this).val(index + 1);
                    });
                }
            });
        }

        $('#block-container').sortable({
            items: '> .block-form',
            handle: 'h2',
            revert: 200,
            placeholder: 'ui-sortable-highlight',
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            forceHelperSize: true,
            stop: function () {
                $(this).find('.block-order').each(function(index) {
                    $(this).val(index + 1);
                });
            }
        });
        
        var blockIndex = $('.block-form').length;

        $('.add-link').hide();
        $('.add-link').click(function (event) {
            event.preventDefault();

            var newLayout = $('input[name=new-block-layout]:checked').val();
            if (!newLayout) return;

            $.get(
                blockFormUrl,
                {
                    layout: newLayout,
                    order: ++blockIndex
                },
                function (data) {
                    $(data)
                        .insertBefore('.add-block')
                        .trigger('exhibit-builder-refresh-wysiwyg')
                        .trigger('exhibit-builder-add-block')
                        ;
                    $('input[name=new-block-layout]').prop('checked', false);
                    $('.selected').removeClass('selected');
                    $('.add-link').hide();
                },
                'html'
            );
        });
        
        $('.layout').click(function (event) {
            $(this).children('input[type="radio"]').prop('checked', true);
            $('.selected').removeClass('selected');
            $(this).addClass('selected');
            $('.add-link').show();
        });

        $('#block-container').on('click', '.delete-toggle, .remove-attachment', function (event) {
            event.preventDefault();
            $(this).toggleClass('undo-delete')
                .parent().toggleClass('deleted')
                .siblings('div').toggleClass('frozen');

            var target = $(this).parent().parent();
            var removedClass = 'removed';
            if (!target.hasClass(removedClass)) {
                target.addClass(removedClass);
                target.find('input, select, textarea').prop('disabled', true);
            } else {
                target.removeClass(removedClass);
                target.find('input, select, textarea').each(function () {
                    if (!$(this).parent().parent().hasClass(removedClass)) {
                        this.disabled = false;
                    }
                });
            }
        });

        $('#block-container').on('exhibit-builder-add-block', '.block-form', function () {
            sortAttachments(this);
        });
        
        $('#block-container').on('click', '.drawer', function (event) {
            event.preventDefault();
            $(this).toggleClass('closed');
            $(this).toggleClass('opened');
            $(this).parent().siblings('div').toggle();
        });

        sortAttachments('#block-container');
    };

    Omeka.ExhibitBuilder.setUpItemsSelect = function (itemOptionsUrl, attachmentUrl) {
        /*
         * Use AJAX to retrieve the list of items that can be attached.
         */
        function getItems(uri, parameters) {
            $.ajax({
                url: uri,
                data: parameters,
                method: 'GET',
                success: function(data) {
                    $('#item-select').html(data);
                    $(document).trigger("omeka:loaditems");
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert('Error getting items: ' . textStatus);
                }
            });
        };

        function setSearchVisibility(show) {
            var searchForm = $('#page-search-form');
            var searchButton = $('#show-or-hide-search');

            if (typeof show === 'undefined') {
                show = !searchForm.is(':visible');
            }
            if (show) {
                searchForm.show();
                searchButton.text('Hide Search Form');
            } else {
                searchForm.hide();
                searchButton.text('Show Search Form');
            }
        }

        /**
         * Use AJAX to load the form for an attachment.
         */
        this.loadItemOptionsForm = function(data) {
            $.ajax({
                url: itemOptionsUrl,
                method: 'POST',
                dataType: 'html',
                data: data,
                success: function (response) {
                    if (typeof data.caption !== 'undefined') {
                        if (!data.caption) {
                            data.caption = '';
                        }
                        tinymce.get('attachment-caption').setContent(data.caption);
                    }
                    $('#attachment-item-options').html(response);
                }
            });
        };

        // Initially load the paginated items
        getItems($('#search').attr('action'));

        // Make search and pagination use AJAX to respond.
        $('#search').submit(function(event) {
            event.preventDefault();
            getItems(this.action, $(this).serialize());
            setSearchVisibility(false);
        });
        $('#item-form').on('click', '.pagination a, #view-all-items', function (event) {
            event.preventDefault();
            getItems(this.href);
            setSearchVisibility(false);
        });
        $('#item-select').on('submit', '.pagination form', function (event) {
            event.preventDefault();
            getItems(this.action + '?' + $(this).serialize());
            setSearchVisibility(false);
        });

        setSearchVisibility(false);
        $('#show-or-hide-search').click(function (event) {
            event.preventDefault();
            setSearchVisibility();
        });

        // Make item listings selectable
        $('#item-select').on('click', '.item-listing', function (event) {
            $('#item-list div.item-selected').removeClass('item-selected');
            $(this).addClass('item-selected');
        });

        // Hook select buttons to item options form
        $('#item-select').on('click', '.select-item', function (event) {
            event.preventDefault();
            Omeka.ExhibitBuilder.loadItemOptionsForm(
                {item_id: $('#item-select .item-selected').data('itemId')}
            );
            $('#attachment-panel').addClass('editing-attachment');
            $(document).trigger('exhibit-builder-select-item');
        });

        $('#change-selected-item').on('click', function (event) {
            event.preventDefault();
            $('#attachment-panel').removeClass('editing-attachment');
        });
    };

    Omeka.ExhibitBuilder.setUpAttachments = function (attachmentUrl) {
        function applyAttachment() {
            var options = $('#attachment-options');
            data = getAttachmentData(options, false);

            var targetedItem = $('.item-targeted').removeClass('item-targeted');
            var targetedBlock = targetedItem.parents('.block-form');
            data['block_index'] = targetedBlock.data('blockIndex');

            if (targetedItem.is('.attachment')) {
                data['index'] = targetedItem.data('attachment-index');
            } else {
                data['index'] = targetedBlock.find('.attachment').length;
            }

            $.ajax({
                url: attachmentUrl,
                method: 'POST',
                dataType: 'html',
                data: data,
                success: function (response) {
                    if (targetedItem.is('.attachment')) {
                        targetedItem.replaceWith(response);
                    } else {
                        targetedBlock.find('.selected-item-list').append(response);
                    }
                }
            });
        };

        function getAttachmentData(container, hidden) {
            var item_id, file_id, caption;

            if (hidden) {
                item_id = container.find('input[name*="[item_id]"]').val()
                file_id = container.find('input[name*="[file_id]"]').val();
                caption = container.find('input[name*="[caption]"]').val();
            } else {
                item_id = container.find('input[name="item_id"]').val()
                file_id = container.find('input[name="file_id"]:checked').val();
                caption = tinymce.get(container.find('textarea[name="caption"]').attr('id')).getContent();
            }
            
            return {
                'item_id': item_id,
                'file_id': file_id,
                'caption': caption,
            };
        }

        function targetAttachment(attachment) {
            $('.item-targeted').removeClass('item-targeted');
            $(attachment).addClass('item-targeted');
        }

        var attachmentPanel = $('#attachment-panel');
        // Search Items Dialog Box
        attachmentPanel.dialog({
            autoOpen: false,
            modal: true,
            resizable: false,
            create: function () {
                $(this).dialog('widget').draggable('option', {
                    containment: 'window',
                    scroll: false
                });
            },
            open: function () {
                function refreshDialog() {
                    attachmentPanel.dialog('option', {
                        width: Math.min($(window).width() - 100, 600),
                        height: Math.min($(window).height() - 100, 500),
                        position: {my: 'center', at: 'center center+22'}
                    });
                }

                refreshDialog();
                $('body').css('overflow', 'hidden');
                $(window).on('resize.ExhibitBuilder', function () {
                    refreshDialog();
                });
            },
            beforeClose: function () {
                $('body').css('overflow', 'inherit');
                $(window).off('resize.ExhibitBuilder');
                $('#attachment-item-options').empty();
            },
            dialogClass: 'item-dialog'
        });
        
        $('#attachment-item-options').on('click','.file-select .item-file', function(event) {
            $(this).find('input[type="radio"]').prop('checked', true);
            $('.selected').removeClass('selected');
            $(this).addClass('selected');
        });

        $('#apply-attachment').on('click', function (event) {
            event.preventDefault();
            applyAttachment();
            attachmentPanel.dialog('close');
        });

        $('#block-container').on('click', '.add-item', function (event) {
            event.preventDefault();
            targetAttachment(this);

            tinymce.get('attachment-caption').setContent('');
            attachmentPanel.removeClass('editing-attachment').dialog('open');
        });

        $('#block-container').on('click', '.edit-attachment a', function (event) {
            var attachment;
            event.preventDefault();

            attachment = $(this).parent().parent();
            targetAttachment(attachment);
            Omeka.ExhibitBuilder.loadItemOptionsForm(getAttachmentData(attachment, true));
            $(document).trigger('exhibit-builder-select-item');
            attachmentPanel.addClass('editing-attachment').dialog('open');
        });
    }

    /**
     * Enable drag and drop sorting for elements.
     */
    Omeka.ExhibitBuilder.enableSorting = function () {
        $('.sortable').nestedSortable({
            listType: 'ul',
            items: 'li.page',
            handle: '.sortable-item',
            revert: 200,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            toleranceElement: '> div',
            placeholder: 'ui-sortable-highlight',
            containment: 'document',
            maxLevels: 3
        });
    };

    Omeka.ExhibitBuilder.activateDeleteLinks = function () {
        $('#page-list .delete-toggle').click(function (event) {
            event.preventDefault();
            header = $(this).parent();
            if ($(this).hasClass('delete-element')) {
                $(this).removeClass('delete-element').addClass('undo-delete');
                header.addClass('deleted');
            } else {
                $(this).removeClass('undo-delete').addClass('delete-element');
                header.removeClass('deleted');
            }
        });
    };

    Omeka.ExhibitBuilder.setUpFormSubmission = function () {
        $('#exhibit-metadata-form').submit(function (event) {
            // add ids to li elements so that we can pull out the parent/child relationships
            var listData = $('#page-list').nestedSortable('serialize');
            var deletedIds = [];
            $('#page-list .deleted').each(function () {
                deletedIds.push($(this).parent().attr('id').match(/_(.*)/)[1]);
            });
            
            $('#pages-hidden').val(listData);
            $('#pages-delete-hidden').val(deletedIds.join(','));
        });
    };
})(jQuery);
