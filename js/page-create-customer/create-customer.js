/**
 * Create Customer JavaScript Document.
 * 
 * @author James Plant <jamesplant@gmail.com>
 * @since  1.0.0
 */

(function () {
    'use strict';

    /**
     * Submits the form and attempts to create a new customer account
     */
    var create = function () {
        document.querySelector('.create-customer-notifications').innerHTML = '';
        document.querySelector('.button-create-customer').setAttribute('disabled', 'disabled');
        document.querySelector('.button-cancel').setAttribute('disabled', 'disabled');
        document.querySelector('.button-create-customer').innerHTML = '<div class="spinner-grow spinner-grow-sm me-3" ></div>' +
            '<span>Creating Customer Account...</span>';


        var postData = {
            status: 'Active',

            name: document.querySelector('[name=name]').value,
            mk: document.querySelector('[name=mk]').value,
            deal: document.querySelector('.deal-loaded').value,
            product: '',

            mortgage_type: document.querySelector('[name=mortgage-type]').value !== 'Other' ? document.querySelector('[name=mortgage-type]').value : document.querySelector('[name=mortgage-type-other]').value,
            mortgage_property: document.querySelector('[name=mortgage-property]').value,
            mortgage_property_price: isNaN(parseFloat(document.querySelector('[name=mortgage-property-price]').value)) ? 0 : parseFloat(document.querySelector('[name=mortgage-property-price]').value),
            mortgage_mortgage_required: isNaN(parseFloat(document.querySelector('[name=mortgage-mortgage-required]').value)) ? 0 : parseFloat(document.querySelector('[name=mortgage-mortgage-required]').value),
            mortgage_ltv: isNaN(parseFloat(document.querySelector('[name=mortgage-ltv]').value)) ? 0 : parseFloat(document.querySelector('[name=mortgage-ltv]').value),
            mortgage_term: isNaN(parseInt(document.querySelector('[name=mortgage-term]').value)) ? 0 : parseInt(document.querySelector('[name=mortgage-term]').value),
            mortgage_type_of_mortgage: document.querySelector('[name=mortgage-type-of-mortgage]').value,
            mortgage_lender: document.querySelector('[name=mortgage-lender]').value,
            mortgage_application_date: document.querySelector('[name=mortgage-application-date]').value !== '' ? document.querySelector('[name=mortgage-application-date]').value : '1970-01-01',
            mortgage_offer_date: document.querySelector('[name=mortgage-offer-date]').value !== '' ? document.querySelector('[name=mortgage-offer-date]').value : '1970-01-01',
            mortgage_completion_date: document.querySelector('[name=mortgage-completion-date]').value !== '' ? document.querySelector('[name=mortgage-completion-date]').value : '1970-01-01',
            mortgage_lg_recon_date: document.querySelector('[name=mortgage-lg-recon-date]').value !== '' ? document.querySelector('[name=mortgage-lg-recon-date]').value : '1970-01-01',
            mortgage_lg_reference: document.querySelector('[name=mortgage-lg-reference]').value,

            life_lender_product: document.querySelector('[name=life-lender-product]').value !== 'other' ? document.querySelector('[name=life-lender-product]').value : document.querySelector('[name=life-lender-other]').value,
            life_type: document.querySelector('[name=life-type]').value,
            life_single_joint: document.querySelector('[name=life-single-joint]').value,
            life_ci: document.querySelector('[name=life-ci]').value,
            life_waiver: document.querySelector('[name=life-waiver]').value,
            life_life_cover: document.querySelector('[name=life-cover]').value,
            life_ci_cover: document.querySelector('[name=life-ci-cover]').value,
            life_term: isNaN(parseInt(document.querySelector('[name=life-term]').value)) ? 0 : parseInt(document.querySelector('[name=life-term]').value),
            life_application_date: document.querySelector('[name=life-application-date]').value !== '' ? document.querySelector('[name=life-application-date]').value : '1970-01-01',
            life_start_date: document.querySelector('[name=life-start-date]').value !== '' ? document.querySelector('[name=life-start-date]').value : '1970-01-01',
            life_end_date: document.querySelector('[name=life-end-date]').value !== '' ? document.querySelector('[name=life-end-date]').value : '1970-01-01',
            life_premium: isNaN(parseFloat(document.querySelector('[name=life-premium]').value)) ? 0 : parseFloat(document.querySelector('[name=life-premium]').value),
            life_lapsed_date: document.querySelector('[name=life-lapsed-date]').value !== '' ? document.querySelector('[name=life-lapsed-date]').value : '1970-01-01',
            life_policy_number: document.querySelector('[name=life-policy-number]').value,
            life_indem_comm: document.querySelector('[name=life-indem-comm]').value,
            life_indem_paid_date: document.querySelector('[name=life-indem-paid-date]').value !== '' ? document.querySelector('[name=life-indem-paid-date]').value : '1970-01-01',

            bandc_provider: document.querySelector('[name=bandc-provider]').value !== 'other' ? document.querySelector('[name=bandc-provider]').value : document.querySelector('[name=bandc-provider-other]').value,
            bandc_property_postcode: document.querySelector('[name=bandc-postcode]').value,
            bandc_policy_number: document.querySelector('[name=bandc-policy-number]').value,
            bandc_resi_let: document.querySelector('[name=bandc-resi-let]').value,
            bandc_current: document.querySelector('[name=bandc-current]').value,
            bandc_dd_ann: document.querySelector('[name=bandc-dd-ann]').value,
            bandc_start_date: document.querySelector('[name=bandc-start-date]').value !== '' ? document.querySelector('[name=bandc-start-date]').value : '1970-01-01',
            bandc_taken_up: document.querySelector('[name=bandc-taken-up]').value,
        };

        document.querySelectorAll('[name=product]').forEach(function (element) {
            postData.product = element.checked ? element.value : postData.product;
        });

        API.request({
            path: '/json/customers',
            method: 'post',
            cors: true,
            data: postData,
            headers: {'X-CSRF-TOKEN': document.querySelector('[name=_token]').value},
            success: function (code, data) {
                if (code !== 200) {
                    document.querySelector('.create-customer-notifications').innerHTML = '<div class="col-12 my-3">' +
                        '<div class="alert alert-danger">' +
                            '<span class="fe fe-alert-triangle me-3"></span>' +
                            'Failed to create customer account: ' + data.message +
                            (typeof(data.errors) === 'object' ? (
                                '<ul class="my-0 my-3">' +
                                    Object.keys(data.errors).map(function (errorKey) {
                                        return '<li>' + data.errors[errorKey] + '</li>';
                                    }).join('') +
                                '</ul>'
                            ) : '') +
                        '</div>' +
                    '</div>';
                    return;
                }
                location.href = '/customers';
            },
            error: function () {
                document.querySelector('.create-customer-notifications').innerHTML = '<div class="col-12 my-3">' +
                    '<div class="alert alert-danger">' +
                        '<span class="fe fe-alert-triangle me-3"></span>' +
                        'Failed to create customer account, no response from server.' +
                    '</div>' +
                '</div>';
            },
            always: function () {
                document.querySelector('.button-create-customer').removeAttribute('disabled');
                document.querySelector('.button-cancel').removeAttribute('disabled', 'disabled');
                document.querySelector('.button-create-customer').innerHTML = 'Create Customer Account';
            },
        });
    };


    // Add event handlers unique to the create page
    if (document.querySelector('.button-create-customer')) {
        document.querySelector('.button-create-customer').addEventListener('click', create);
    }

})();