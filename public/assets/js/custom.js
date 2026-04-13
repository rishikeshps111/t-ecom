function deleteRecord(url, tableId, confirmMessage = 'Are you sure?') {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: 'Are you sure?',
        text: confirmMessage,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: csrfToken
                },
                success: function (response) {
                    $('#' + tableId).DataTable().ajax.reload();

                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The record has been deleted.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || errorText,
                        icon: 'error'
                    });
                }
            });
        }
    });
}

function showToast(icon, message) {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Initialize all Select2 fields
$('.select2').select2({
    tags: true,
    tokenSeparators: [','], // only split when comma is used
    placeholder: "Type and press enter",
    allowClear: true
});

function initCmsForm({
    formSelector,
    ckeditorFields = [],
    submitBtnSelector = '.submit-btn',
    ajaxUrl = '',
    rules = {},
    messages = {},
    excludeValidation = [] // fields to skip validation
}) {
    const $form = document.querySelector(formSelector);
    const validation = new JustValidate(formSelector);

    // Add rules for input, textarea, and select
    $form.querySelectorAll('input, textarea, select').forEach(field => {
        const id = field.id;
        if (!id) return;

        const isCKEditor = ckeditorFields.includes(id);
        const skipValidation = excludeValidation.includes(id);
        const fieldRules = rules[id] || [];
        const fieldMessages = messages[id] || {};

        if (isCKEditor && !skipValidation) {
            validation.addField('#' + id, [
                {
                    validator: () => CKEDITOR.instances[id].getData().trim().length > 0,
                    errorMessage: fieldMessages.required || 'This field is required'
                },
                ...fieldRules
            ]);
        } else if (!isCKEditor && !skipValidation && fieldRules.length > 0) {
            validation.addField('#' + id, [...fieldRules]);
        }
    });

    validation.onSuccess((event) => {
        event.preventDefault();

        const formData = new FormData(event.target);

        ckeditorFields.forEach(id => {
            const el = document.getElementById(id);
            if (el && CKEDITOR.instances[id]) {
                formData.set(el.name, CKEDITOR.instances[id].getData());
            }
        });

        // AJAX submit
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            beforeSend: function () {
                $(submitBtnSelector).prop('disabled', true).text('Saving...');
                // Clear previous errors
                $form.querySelectorAll('.just-validate-error-message').forEach(el => el.remove());
            },
            success: function (response) {
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: response.message || 'Page saved successfully!',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                $(submitBtnSelector).prop('disabled', false).text('Save');
            },
            error: function (xhr) {
                $(submitBtnSelector).prop('disabled', false).text('Save');

                if (xhr.status === 422) { // Laravel validation errors
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        const fieldId = field.includes('.') ? field.split('.').join('_') : field;
                        const inputEl = document.getElementById(fieldId);
                        if (inputEl) {
                            // Add JustValidate error message dynamically
                            const errorEl = document.createElement('div');
                            errorEl.className = 'just-validate-error-message';
                            errorEl.style.color = 'red';
                            errorEl.innerText = errors[field][0];
                            inputEl.parentNode.appendChild(errorEl);
                        }
                    }

                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Please fix the errors in the form!',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: 'Something went wrong!',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }
        });
    });
}

function initIntlTelInput(inputId, hiddenCountryId, hiddenNumberId) {
    const input = document.getElementById(inputId);
    const hiddenCountry = document.getElementById(hiddenCountryId);
    const hiddenNumber = document.getElementById(hiddenNumberId);

    if (!input) return null;

    // Preselect old country from data attribute or fallback to auto
    let oldCountry = input.dataset.oldCountry || "auto";

    // if it's a dial code like '+62', convert to ISO
    if (oldCountry.startsWith("+")) {
        oldCountry = getCountryCodeFromDialCode(oldCountry);
    }


    const iti = window.intlTelInput(input, {
        initialCountry: oldCountry,
        separateDialCode: true,
        nationalMode: false,
        autoPlaceholder: "polite",
        geoIpLookup: function (callback) {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code))
                .catch(() => callback("us"));
        },
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
    });

    // Find the existing span for backend or JS validation
    let errorMsg = document.querySelector('span.phone-validate');
    if (!errorMsg) {
        errorMsg = document.createElement("span");
        errorMsg.classList.add("text-danger", "small");
        input.insertAdjacentElement("afterend", errorMsg);
    }

    // Function to validate input
    function validate() {
        // Only validate if value is not empty
        if (!input.value.trim()) {
            errorMsg.innerText = "";
            errorMsg.style.display = "none";
            input.classList.remove("is-invalid");
            return true;
        }

        // If Laravel backend message exists, skip JS validation until removed
        if (errorMsg.innerText.trim() !== "" && errorMsg.dataset.backend === "1") return false;

        if (!iti.isValidNumber()) {
            errorMsg.innerText = "Invalid phone number";
            errorMsg.style.display = "block";
            input.classList.add("is-invalid");
            return false;
        } else {
            errorMsg.innerText = "";
            errorMsg.style.display = "none";
            input.classList.remove("is-invalid");
            return true;
        }
    }

    // Mark backend validation message (if any)
    if (errorMsg.innerText.trim() !== "") {
        errorMsg.dataset.backend = "1"; // mark as backend error
    }

    // Remove backend mark when user types
    input.addEventListener("input", function () {
        if (errorMsg.dataset.backend === "1") {
            errorMsg.dataset.backend = "0"; // allow JS validation now
            errorMsg.innerText = "";
            errorMsg.style.display = "none";
            input.classList.remove("is-invalid");
        }
        validate();
    });

    input.addEventListener("blur", validate);

    // On form submit
    const form = input.closest("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            if (input.value.trim() && !validate()) {
                e.preventDefault();
                input.focus();
                return false;
            }

            // Save full number
            const countryCode = "+" + iti.getSelectedCountryData().dialCode;
            const nationalNumber = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL);

            if (hiddenCountry) hiddenCountry.value = countryCode;
            if (hiddenNumber) hiddenNumber.value = nationalNumber;
        });
    }

    return iti;
}

function getCountryCodeFromDialCode(dialCode) {
    const allCountries = window.intlTelInputGlobals.getCountryData();
    const country = allCountries.find(c => "+" + c.dialCode === dialCode);
    return country ? country.iso2 : "auto"; // fallback to auto
}

document.querySelectorAll(".toggle-password").forEach(function (btn) {
    btn.addEventListener("click", function () {
        const inputId = this.getAttribute("data-target");
        const input = document.getElementById(inputId);
        const icon = this.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
});

$('.search-select').select2()



