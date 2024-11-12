function applySearchOrFilter(forms) {
    const formData = new URLSearchParams();

    forms.forEach(form => {
        Array.from(form.elements).forEach(input => {
            if (input.name && input.value) {
                const filterQuery = `filter[${input.name}]`
                formData.append(filterQuery, input.value);
            }
        });
    })

    const currentUrl = new URL(window.location.href);
    currentUrl.search = formData.toString();
    window.location.href = currentUrl.toString();
}

function clearSearchOrFilter(thisForm) {
    const currentUrl = new URL(window.location.href);
    const formInputs = Array.from(thisForm.querySelectorAll('input[name], select[name]'))
        .map(input => input.name);

    formInputs.forEach(inputName => {
        const filterQuery = `filter[${inputName}]`
        currentUrl.searchParams.delete(filterQuery);
    });

    window.location.href = currentUrl;
}

const forms = document.querySelectorAll('form[id^="dtb"]');

forms.forEach(form => {
    const applyBtn = form.querySelector('button[type=submit]')
    if (applyBtn) {
        applyBtn.addEventListener('click', function(event) {
            event.preventDefault()
            applySearchOrFilter(forms);
        });
    }

    const clearBtn = form.querySelector('button#dtb-form-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(event) {
            event.preventDefault()
            clearSearchOrFilter(form);
        });
    }
});
