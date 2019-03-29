define(['require'], function(require) {

    Math.fRound = function(number, precision) {
        var factor = Math.pow(10, precision);
        return Math.round(number * factor) / factor;
    };

    var defaultLocale = document.documentElement.lang || (navigator.language || navigator.browserLanguage).split('-')[0] || 'en';

    // Available locales are en, fr, de, es and pt
    /** @see src/Ekyna/Bundle/CoreBundle/Resources/config/grunt/copy.js:101 */
    if (-1 === ['en', 'fr', 'de', 'es', 'pt'].indexOf(defaultLocale)) {
        defaultLocale = 'en';
    }

    // Array polyfills
    if (!Array.prototype.find) {
        // https://github.com/jsPolyfill/Array.prototype.find/blob/master/find.js
        Array.prototype.find = Array.prototype.find || function (callback) {
            if (this === null) {
                throw new TypeError('Array.prototype.find called on null or undefined');
            } else if (typeof callback !== 'function') {
                throw new TypeError('callback must be a function');
            }
            var list = Object(this);
            // Makes sures is always has an positive integer as length.
            var length = list.length >>> 0;
            var thisArg = arguments[1];
            for (var i = 0; i < length; i++) {
                var element = list[i];
                if (callback.call(thisArg, element, i, list)) {
                    return element;
                }
            }
        };
    }

    function buildPrototype() {
        function testSupportToLocaleString() {
            var number = 0;
            try {
                number.toLocaleString("i");
            } catch (e) {
                return e.name === "RangeError";
            }
            return false;
        }

        function toLocaleStringSupportsOptions() {
            return !!(window.Intl && typeof window.Intl === 'object' && typeof window.Intl.NumberFormat === 'function');
        }

        if (toLocaleStringSupportsOptions()) {
            Number.prototype.localizedCurrency = function (currency, locale) {
                return this.toLocaleString(locale || defaultLocale, {style: "currency", currency: currency || 'USD'});
            };
            Number.prototype.localizedNumber = function (style, locale) {
                return this.toLocaleString(locale || defaultLocale, {style: style || "decimal"});
            };
        } else if (testSupportToLocaleString()) {
            Number.prototype.localizedCurrency = function (currency, locale) {
                return this.toLocaleString(locale || defaultLocale) + '&nbsp;' + currency;
            };
            Number.prototype.localizedNumber = function (style, locale) {
                return this.toLocaleString(locale || defaultLocale);
            };
        } else if (Number.hasOwnProperty('toLocaleString')) {
            Number.prototype.localizedCurrency = function (currency) {
                return this.toLocaleString() + '&nbsp;' + currency;
            };
            Number.prototype.localizedNumber = function () {
                return this.toLocaleString();
            };
        } else {
            Number.prototype.localizedCurrency = function (currency) {
                return this.toFixed(2) + '&nbsp;' + currency;
            };
            Number.prototype.localizedNumber = function () {
                return this.toFixed(2);
            };
        }
    }

    // https://github.com/yahoo/intl-locales-supported/blob/master/index.js
    function areIntlLocalesSupported(locales) {
        if (typeof window.Intl === 'undefined') {
            return false;
        }

        if (!locales) {
            throw new Error('locales must be supplied.');
        }

        if (!Array.isArray(locales)) {
            locales = [locales];
        }

        var intlConstructors = [
            window.Intl.DateTimeFormat,
            window.Intl.NumberFormat
        ].filter(function (intlConstructor) {
            return intlConstructor;
        });

        if (intlConstructors.length === 0) {
            return false;
        }

        return intlConstructors.every(function (intlConstructor) {
            var supportedLocales = intlConstructor.supportedLocalesOf(locales);
            return supportedLocales.length === locales.length;
        });
    }

    // Determine if the built-in `Intl` has the locale data we need.
    if (!(window.Intl && areIntlLocalesSupported([defaultLocale]))) {
        require(['intl'], function(IntlPolyfill) {
            window.IntlPolyfill = IntlPolyfill;
            if (window.Intl) {
                Intl.NumberFormat   = IntlPolyfill.NumberFormat;
                Intl.DateTimeFormat = IntlPolyfill.DateTimeFormat;
            } else {
                window.Intl = IntlPolyfill;
            }
            require(['intl/locales/' + defaultLocale], function() {
                buildPrototype();
            });
        });
    } else {
        buildPrototype();
    }
});
