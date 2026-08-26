document.addEventListener('DOMContentLoaded', function () {
    const applyTo = document.getElementById('sdboge_apply_to');
    const categoryField = document.getElementById('sdboge-apply-to-categories-section');
    const tagField = document.getElementById('sdboge-apply-to-tags-section');
    const productField = document.getElementById('sdboge-apply-to-products-section');
    const hr = document.getElementById('sdboge-apply-to-hr');

    if (!applyTo || !categoryField || !tagField || !productField) return;

    function updateApplyToFields() {
        const value = applyTo.value;

        categoryField.style.display = value === 'category' ? '' : 'none';
        tagField.style.display = value === 'tag' ? '' : 'none';
        productField.style.display = value === 'product' ? '' : 'none';
        hr.style.display = value === 'all' ? 'none' : '';
    }

    updateApplyToFields();

    applyTo.addEventListener('change', updateApplyToFields);

    // Select2 change.
    if (window.jQuery) {
        jQuery(applyTo).on('change', updateApplyToFields);
    }
});