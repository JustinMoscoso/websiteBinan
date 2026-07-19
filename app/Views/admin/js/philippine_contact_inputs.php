<script>
(function (window, document) {
    'use strict';

    function formatMobile(value) {
        const raw = String(value || '').trim();
        if (!raw) return '';

        let digits = raw.replace(/\D/g, '');
        if (digits.startsWith('63')) digits = digits.slice(2);
        else if (digits.startsWith('0')) digits = digits.slice(1);

        digits = digits.slice(0, 10);
        if (!digits) return '+63';

        let formatted = '+63 ' + digits.slice(0, 3);
        if (digits.length > 3) formatted += ' ' + digits.slice(3, 6);
        if (digits.length > 6) formatted += ' ' + digits.slice(6, 10);
        return formatted;
    }

    function formatLandline(value) {
        const raw = String(value || '').trim();
        if (!raw) return '';

        let digits = raw.replace(/\D/g, '');
        if (!digits) return '';
        if (digits.startsWith('63') && digits.length > 2) digits = digits.slice(2);

        if (digits.startsWith('049')) {
            const subscriber = digits.slice(3, 10);
            if (digits.length <= 3) return '(049)';
            if (subscriber.length <= 3) return '(049) ' + subscriber;
            return '(049) ' + subscriber.slice(0, 3) + '-' + subscriber.slice(3);
        }

        if (digits.startsWith('02')) {
            const subscriber = digits.slice(2, 10);
            if (digits.length <= 2) return '(02)';
            if (subscriber.length <= 4) return '(02) ' + subscriber;
            return '(02) ' + subscriber.slice(0, 4) + '-' + subscriber.slice(4);
        }

        return digits.slice(0, 10);
    }

    function isMobilePrefixOnly(value) {
        return ['+63', '+63 9'].includes(String(value || '').trim());
    }

    function isLandlinePrefixOnly(value) {
        return ['(', '(0', '(02', '(049', '(02)', '(049)', '(02) ', '(049) '].includes(String(value || '').trim());
    }

    function bindMobile(input) {
        if (!input || input.dataset.phMobileBound === '1') return;
        input.dataset.phMobileBound = '1';
        input.addEventListener('focus', function () {
            if (!this.value.trim()) this.value = '+63 9';
        });
        input.addEventListener('input', function () { this.value = formatMobile(this.value); });
        input.addEventListener('blur', function () { this.value = formatMobile(this.value); });
        if (input.value.trim()) input.value = formatMobile(input.value);
        else if (!input.disabled) input.value = '+63 9';
    }

    function bindLandline(input) {
        if (!input || input.dataset.phLandlineBound === '1') return;
        input.dataset.phLandlineBound = '1';
        input.addEventListener('keydown', function (event) {
            const allowedKeys = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (allowedKeys.includes(event.key) || event.ctrlKey || event.metaKey || /^\d$/.test(event.key)) return;
            event.preventDefault();
        });
        input.addEventListener('input', function () { this.value = formatLandline(this.value); });
        input.addEventListener('blur', function () { this.value = formatLandline(this.value); });
        if (input.value.trim()) input.value = formatLandline(input.value);
    }

    function bind(mobileSelector, landlineSelector) {
        document.querySelectorAll(mobileSelector).forEach(bindMobile);
        document.querySelectorAll(landlineSelector).forEach(bindLandline);
    }

    function prepare(mobileSelector, landlineSelector) {
        const mobile = document.querySelector(mobileSelector);
        const landline = document.querySelector(landlineSelector);

        if (mobile) {
            mobile.value = formatMobile(mobile.value);
            if (isMobilePrefixOnly(mobile.value)) mobile.value = '';
        }
        if (landline) {
            landline.value = formatLandline(landline.value);
            if (isLandlinePrefixOnly(landline.value)) landline.value = '';
        }
    }

    window.PhilippineContactInputs = {
        bind: bind,
        prepare: prepare,
        formatMobile: formatMobile,
        formatLandline: formatLandline,
        isValidMobile: function (value) {
            return /^\+63\s9\d{2}\s\d{3}\s\d{4}$/.test(String(value || '').trim());
        },
        isValidLandline: function (value) {
            const normalized = String(value || '').trim();
            return /^\(049\)\s\d{3}-\d{4}$/.test(normalized) || /^\(02\)\s\d{4}-\d{4}$/.test(normalized);
        }
    };
}(window, document));
</script>
