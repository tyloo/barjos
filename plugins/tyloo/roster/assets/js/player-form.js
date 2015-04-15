+function ($) { "use strict";
    var PlayerForm = function () {
        this.$preview = $('#player-preview')
        this.$form = this.$preview.closest('form')
        this.formAction = this.$form.attr('action')
        this.sessionKey = $('input[name=_session_key]', this.$form).val()
        this.$textarea = $('[name="Player[content]"]', this.$form)
        this.$previewContent = $('.preview-content', this.$preview)
        this.codeEditor = $('textarea[name="Player[content]"]', this.$form).closest('.field-codeeditor').data('oc.codeEditor')
        this.createIndicator()

        this.$textarea.on('oc.codeEditorChange', $.proxy(this.handleChange, this))

        this.loading = false
        this.updatesPaused = false
        this.initPreview()
        this.initDropzones()
        this.initFormEvents()
        this.initLayout()
    }

    PlayerForm.prototype.handleChange = function() {
        if (this.updatesPaused)
            return

        var self = this

        if (this.loading) {
            if (this.dataTrackInputTimer === undefined) {
                this.dataTrackInputTimer = window.setInterval(function(){
                    self.handleChange()
                }, 100)
            }

            return
        }

        window.clearTimeout(this.dataTrackInputTimer)
        this.dataTrackInputTimer = undefined

        var self = this;
        self.update();
    }

    PlayerForm.prototype.createIndicator = function() {
        var $previewContainer = $('#player-preview').closest('.loading-indicator-container')
        this.$indicator = $('<div class="loading-indicator transparent"><div></div><span></span></div>')
        $previewContainer.prepend(this.$indicator)
    }

    PlayerForm.prototype.update = function() {
        var self = this

        this.loading = true
        this.showIndicator()

        this.$form.request('onRefreshPreview', {
            success: function(data) {
                self.$previewContent.html(data.preview)
                self.initPreview()
                self.updateScroll()
            }
        }).done(function(){
            self.hideIndicator()
            self.loading = false
        })
    }

    PlayerForm.prototype.showIndicator = function() {
        this.$indicator.css('display', 'block')
    }

    PlayerForm.prototype.hideIndicator = function() {
        this.$indicator.css('display', 'none')
    }

    PlayerForm.prototype.initPreview = function() {
        prettyPrint()
        this.initImageUploaders()
    }

    PlayerForm.prototype.updateScroll = function() {
        this.$preview.data('oc.scrollbar').update()
    }

    PlayerForm.prototype.initImageUploaders = function() {
        var self = this
        $('span.image-placeholder .upload-dropzone', this.$preview).each(function(){
            var
                $placeholder = $(this).parent(),
                $link = $('span.label', $placeholder),
                placeholderIndex = $placeholder.data('index')

            var dropzone = new Dropzone($(this).get(0), {
                url: self.formAction,
                clickable: [$(this).get(0), $link.get(0)],
                previewsContainer: $('<div />').get(0),
                paramName: 'file'
            })

            dropzone.on('error', function(file, error) {
                alert('Error uploading file: ' + error)
            })
            dropzone.on('success', function(file, data){
                if (data.error)
                    alert(data.error)
                else {
                    self.pauseUpdates()
                    var $img = $('<img src="'+data.path+'">')
                    $img.load(function(){
                        self.updateScroll()
                    })

                    $placeholder.replaceWith($img)

                    self.codeEditor.editor.replace('!['+data.file+']('+data.path+')', {
                        needle: '!['+placeholderIndex+'](image)'
                    })
                    self.resumeUpdates()
                }
            })
            dropzone.on('complete', function(){
                $placeholder.removeClass('loading')
            })
            dropzone.on('sending', function(file, xhr, formData) {
                formData.append('X_PLAYER_IMAGE_UPLOAD', 1)
                formData.append('_session_key', self.sessionKey)
                $placeholder.addClass('loading')
            })
        })
    }

    PlayerForm.prototype.pauseUpdates = function() {
        this.updatesPaused = true
    }

    PlayerForm.prototype.resumeUpdates = function() {
        this.updatesPaused = false
    }

    PlayerForm.prototype.initDropzones = function() {
        $(document).bind('dragover', function (e) {
            var dropZone = $('span.image-placeholder .upload-dropzone'),
                foundDropzone,
                timeout = window.dropZoneTimeout

            if (!timeout)
                dropZone.addClass('in');
            else
                clearTimeout(timeout);

            var found = false,
                node = e.target

            do {
                if ($(node).hasClass('dropzone')) {
                    found = true
                    foundDropzone = $(node)
                    break
                }

                node = node.parentNode;

            } while (node != null);

            dropZone.removeClass('in hover')

            if (found)
                foundDropzone.addClass('hover')

            window.dropZoneTimeout = setTimeout(function () {
                window.dropZoneTimeout = null
                dropZone.removeClass('in hover')
            }, 100)
        })
    }

    PlayerForm.prototype.initFormEvents = function() {
        $(document).on('ajaxSuccess', '#player-form', function(event, context, data){
            if (context.handler == 'onSave' && !data.X_OCTOBER_ERROR_FIELDS) {
                $(this).trigger('unchange.oc.changeMonitor')
            }
        })

        $('#DatePicker-formPublishedAt-input-published_at').triggerOn({
            triggerAction: 'enable',
            trigger: '#Form-field-Player-published',
            triggerCondition: 'checked'
        })
    }

    PlayerForm.prototype.initLayout = function() {
        $('#Form-secondaryTabs .tab-pane.layout-cell:not(:first-child)').addClass('padded-pane')
    }

    PlayerForm.prototype.replacePlaceholder = function(placeholder, placeholderHtmlReplacement, mdCodePlaceholder, mdCodeReplacement) {
        this.pauseUpdates()
        placeholder.replaceWith(placeholderHtmlReplacement)

        this.codeEditor.editor.replace(mdCodeReplacement, {
            needle: mdCodePlaceholder
        })
        this.updateScroll()
        this.resumeUpdates()
    }
    
    $(document).ready(function(){
        var form = new PlayerForm()

        if ($.oc === undefined)
            $.oc = {}

        $.oc.PlayerForm = form
    })

}(window.jQuery);