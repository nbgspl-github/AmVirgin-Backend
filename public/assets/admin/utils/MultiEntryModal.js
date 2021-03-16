/**
 * Developer - Aviral Singh (dev.aviralx@gmail.com)
 */

class MultiEntryModal {

    constructor(config) {
        this.config = config;
        this.count = 0;
    }

    bind = () => {
        this.header = $('#' + this.config.key + '_modalTitle');
        this.header.text(this.config.title);
        this.key = this.config.key;
        this.boundEditBox = $(this.identify() + this.config.boundEditBoxId);
        this.boundEditBox.click(() => {
            this.handleInvokeModal();
        });
        this.boundEditBox.attr('readonly', true);
        this.boundEditBox.addClass('bg-white');
        this.boundEditBox.attr('autocomplete', 'off');
        this.addMoreButton = $(this.identify() + this.config.addMoreButtonId);
        this.addMoreButton.click(() => {
            this.handleAddRow();
        });
        this.modal = $(this.identify() + this.config.modalId);
        this.listGroup = $(this.identify() + this.config.listGroupId);
        this.deleteButtons = $(this.classify() + this.config.deleteButtonClass);
        this.deleteButtons.click((event) => {
            this.handleDeleteRow(event);
        });
        this.doneButton = $(this.identify() + this.config.doneButtonId);
        this.doneButton.click(() => {
            this.handleConcealModal();
        });
        this.template = this.config.template;
    };

    handleInvokeModal = () => {
        this.listGroup.children().each((index, element) => {
            $(element).remove();
        });
        this.modal.modal('show');
        const value = this.readBoundData();
        if (value.length >= 3) {
            const split = this.splitItems(value);
            $(split).each((index, element) => {
                const rendered = this.renderTemplateToUI({value: element});
                this.handleAddRow(rendered);
            });
        }
        if (this.listGroup.children().length < 1) {
            this.handleAddRow();
        }
    };

    renderTemplateToUI = (values) => {
        if (values === null) {
            values = {
                value: ''
            };
        }
        return Mustache.render(this.config.template, values);
    };

    handleConcealModal = () => {
        const elements = $(this.classify() + this.config.inputClass);
        let items = [];
        elements.each((index, element) => {
            const value = $(element).val();
            if (value.trim().length > 0) {
                items.push(value);
            }
        });
        const value = this.mergeItems(items);
        this.updateBoundData(value);
        this.modal.modal('hide');
    };

    readBoundData = () => {
        return this.boundEditBox.val();
    };

    updateBoundData = (value) => {
        this.boundEditBox.val(value);
    };

    handleAddRow = (rendered = this.renderTemplateToUI(null)) => {
        console.log("Count is " + this.count);
        this.count++;
        this.listGroup.append(rendered);
        this.deleteButtons = $('.' + this.config.deleteButtonClass);
        this.deleteButtons.click((event) => {
            this.handleDeleteRow(event);
        });
        this.handleEnableAdd();
    };

    handleDeleteRow = (event) => {
        this.count--;
        this.handleEnableAdd();
        event.target.parentNode.parentNode.parentNode.parentNode.remove();
    };

    splitItems = (value) => {
        return value.split(this.config.separator);
    };

    mergeItems = (items) => {
        let merged = '';
        for (let i = 0; i < items.length; i++) {
            merged += (items[i] + this.config.separator);
        }
        if (merged.charAt(merged.length - 1) === this.config.separator)
            merged = merged.substring(0, merged.length - 1);
        return merged;
    };

    identify = () => {
        return ('#');
    };

    classify = () => {
        return ('.');
    };

    static setupMultiEntryModal(config) {
        const obj = new MultiEntryModal(config);
        obj.bind();
    };

    filterCharacters(event) {
        const typed = String.fromCharCode(event.keyCode);
        let contains = false;
        this.config.exclude.forEach(char => {
            if (char === typed)
                contains = true;
        });
        return contains;
    }

    handleEnableAdd = () => {
        if (this.count <= 9) {
            this.addMoreButton.removeClass('d-none');
        } else {
            this.addMoreButton.addClass('d-none');
        }
    }
}