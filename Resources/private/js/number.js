;(function (_number, _intl) {

    var defaultLocale = (navigator.language || navigator.browserLanguage).split('-')[0] || 'en';

    function testSupportToLocaleString() {
        var number = 0;
        try {
            number.toLocaleString("i");
        } catch (e) {
            return e instanceof RangeError;
        }
        return false;
    }

    function toLocaleStringSupportsOptions() {
        return !!(_intl && typeof _intl === 'object' && typeof _intl.NumberFormat === 'function');
    }

    if (toLocaleStringSupportsOptions()) {
        _number.prototype.formatPrice = function (currency, locale) {
            return this.toLocaleString(locale || defaultLocale, {style: "currency", currency: currency || 'USD'});
        };
    } else if (testSupportToLocaleString()) {
        _number.prototype.formatPrice = function (currency, locale) {
            return this.toLocaleString(locale || defaultLocale) + '&nbsp;' + currency;
        };
    } else if (_number.hasOwnProperty('toLocaleString')) {
        _number.prototype.formatPrice = function (currency) {
            return this.toLocaleString() + '&nbsp;' + currency;
        };
    } else {
        _number.prototype.formatPrice = function (currency) {
            return _number(price).toFixed(2) + '&nbsp;' + currency;
        }
    }
})(Number, Intl);
