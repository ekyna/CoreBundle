define(['jquery', 'ekyna-string'], function($) {
    "use strict";

    if (0 === $('link#core-flags-stylesheet').length) {
        var stylesheet = document.createElement('link');
        stylesheet.id = 'core-flags-stylesheet';
        stylesheet.href = '/bundles/ekynacore/css/flags.css';
        stylesheet.type = 'text/css';
        stylesheet.rel = 'stylesheet';
        $('head').append(stylesheet);
    }

    var countries = $.getJSON('/' + $('html').attr('lang') + '/js/countries.json');

    var PhoneNumberWidget = function(element) {
        this.$form = $(element);
        this.$country = this.$form.find('#' + this.$form.attr('id') + '_country');
        this.$number = this.$form.find('#' + this.$form.attr('id') + '_number');

        this.$dropdown = this.$form.find('.dropdown');
        this.$flag = this.$dropdown.find('button > .country-flag');
        this.$dial = this.$dropdown.find('button > .country-dial');
        this.$list = null;
        this.$current = null;

        this.$watch = null;

        this.spellString = '';
        this.spellTimeout = null;
    };

    PhoneNumberWidget.prototype.init = function () {
        var that = this;
        countries.then(function(data) {
            that.buildList(data);
        });

        this.onListClick = $.proxy(this.listClickHandler, this);
        this.onListKeydown = $.proxy(this.listKeydownHandler, this);

        this.onSpellTimeout = $.proxy(function() {
            this.spellString = '';
            this.spellTimeout = null;
        }, this);
    };

    PhoneNumberWidget.prototype.buildList = function (data) {
        var div = document.createElement('div'),
            ul = document.createElement('ul');

        this.$dropdown.find('> .dropdown-menu').empty().append(div);
        div.append(ul);

        $.each(data, function(code, conf) {
            var li = document.createElement('li'),
                flag = document.createElement('span'),
                name = document.createElement('span'),
                dial = document.createElement('span');

            li.setAttribute('data-name', conf['name'].removeDiatrics().toLowerCase().replace(/[^a-z]+/, ' '));
            li.setAttribute('data-code', code);
            li.setAttribute('data-dial', conf['dial']);
            li.setAttribute('data-fixed', conf['fixed']);
            li.setAttribute('data-mobile', conf['mobile']);

            flag.classList.add('country-flag', code.toLowerCase());
            li.append(flag);

            name.classList.add('country-name');
            name.innerText = conf['name'];
            li.append(name);

            if (0 < conf['dial']) {
                dial.classList.add('country-dial');
                dial.innerText = '+' + conf['dial'];
                li.append(dial);
            }

            ul.append(li);
        });

        this.$list = $(div);

        // Initial selection
        this.selectCountry(this.$dropdown.find('li[data-code="' + this.$country.val() + '"]'));

        this.$dropdown.on('show.bs.dropdown', $.proxy(this.enableListHandlers, this));
        this.$dropdown.on('hide.bs.dropdown', $.proxy(this.disableListHandlers, this));

        this.$dropdown.on('shown.bs.dropdown', $.proxy(this.scrollToSelected, this));

        this.$country.on('change', $.proxy(this.countryChangeHandler, this));

        if (this.$form.data('watch')) {
            this.$watch = $('#' + this.$form.data('watch'));
            if (this.$watch.length) {
                this.$watch.on('change', $.proxy(this.watchChangeHandler, this));
            }
        }
    };

    PhoneNumberWidget.prototype.enableListHandlers = function () {
        this.$dropdown.on('click', 'li', this.onListClick);
        this.$dropdown.on('keydown', this.onListKeydown);
    };

    PhoneNumberWidget.prototype.disableListHandlers = function () {
        this.$dropdown.off('click', 'li', this.onListClick);
        this.$dropdown.off('keydown', this.onListKeydown);
    };

    PhoneNumberWidget.prototype.countryChangeHandler = function () {
        if (!this.$country.val()) {
            return;
        }

        this.selectCountry(this.$dropdown.find('li[data-code="' + this.$country.val() + '"]'));
    };

    PhoneNumberWidget.prototype.watchChangeHandler = function () {
        if (this.$number.val()) {
            return;
        }

        this.selectCountry(this.$dropdown.find('li[data-code="' + this.$watch.val() + '"]'));
    };

    PhoneNumberWidget.prototype.listClickHandler = function (e) {
        this.selectCountry($(e.currentTarget));
    };

    PhoneNumberWidget.prototype.listKeydownHandler = function (e) {
        // Arrow up
        if (e.which === 38) {
            this.selectPrevious();
            return;
        }

        // Arrow down
        if (e.which === 40) {
            this.selectNext();
            return;
        }

        // Character
        if ((65 <= e.which && e.which <= 90) || (97 <= e.which && e.which <= 122)) {
            this.spellSelect(String.fromCharCode(e.which));
        }
    };

    PhoneNumberWidget.prototype.selectPrevious = function () {
        if (0 === this.$current.length) {
            this.selectCountry(this.$dropdown.find('li:first-child'));

            return;
        }

        var $prev = this.$current.prev();
        if ($prev.length) {
            this.selectCountry($prev);

            return;
        }

        this.selectCountry(this.$dropdown.find('li:last-child'));
    };

    PhoneNumberWidget.prototype.selectNext = function () {
        if (0 === this.$current.length) {
            this.selectCountry(this.$dropdown.find('li:first-child'));

            return;
        }

        var $next = this.$current.next();
        if ($next.length) {
            this.selectCountry($next);

            return;
        }

        this.selectCountry(this.$dropdown.find('li:first-child'));
    };

    PhoneNumberWidget.prototype.spellSelect = function (char) {
        if (this.spellTimeout) {
            clearTimeout(this.spellTimeout);
        }

        this.spellString += char;

        var $li, regex = new RegExp('^' + this.spellString, 'i');

        this.$dropdown.find('li').each(function(index, li) {
            if (regex.test(li.getAttribute('data-name'))) {
                $li = $(li);

                return false;
            }
        });

        this.spellTimeout = setTimeout(this.onSpellTimeout, 500);

        if ($li) {
            this.selectCountry($li);
        }
    };

    PhoneNumberWidget.prototype.selectCountry = function ($li) {
        if (0 === $li.length) {
            return;
        }

        if (this.$current === $li) {
            return;
        }

        if (this.$current) {
            this.$current.removeClass('active');
            this.$flag.removeClass(String(this.$current.data('code')).toLowerCase());
            this.$dial.empty();
            this.$current = null;
        }

        this.$current = $li;
        this.$current.addClass('active');

        var code = String(this.$current.data('code'));
        if (code !== this.$country.val()) {
            this.$country.val(code);
        }
        this.$flag.addClass(code.toLowerCase());
        this.$dial.text('+' + this.$current.data('dial'));

        this.$number.removeAttr('placeholder');
        var placeholder = this.$current.data(this.$form.data('type'));
        if (placeholder) {
            this.$number.attr('placeholder', placeholder);
        }

        this.scrollToSelected();
    };

    PhoneNumberWidget.prototype.scrollToSelected = function () {
        if (this.$current && this.$current.length) {
            this.$list.scrollTop(this.$current.position().top + this.$list.scrollTop());
        }
    };

    $.fn.phoneNumberWidget = function() {
        return this.each(function() {
            new PhoneNumberWidget(this).init();
        });
    };

    return {
        init: function($element) {
            $element.phoneNumberWidget();
        }
    };
});
