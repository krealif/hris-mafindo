function modifyUrlQueryParams(currentUrl, params) {
    const url = new URL(currentUrl);
    // add/update query params
    Object.keys(params).forEach(key => {
        if (url.searchParams.has(key)) {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        }
        else {
            url.searchParams.append(key, params[key]);
        }
    });

    return url.toString();
}

function applyFilter(forms) {
    const queryParams = {};

    forms.forEach(form => {
        Array.from(form.elements).forEach(input => {
            if (input.name && input.value) {
                const key = `filter[${input.name}]`;
                queryParams[key] = input.value;
            }
        });
    });

    const url = new URL(window.location.href);
    window.location.href = modifyUrlQueryParams(url, queryParams);
}

function clearFilter(form) {
    const queryParams = {};

    Array.from(form.elements).forEach(input => {
        if (input.name && input.value) {
            const key = `filter[${input.name}]`;
            queryParams[key] = '';
        }
    });

    const url = new URL(window.location.href);
    window.location.href = modifyUrlQueryParams(url, queryParams);
}

const datatable = document.querySelector('#dt-datatable');
const forms = datatable.querySelectorAll('form[id^="dt"]');

forms.forEach(form => {
    const applyFilterBtn = form.querySelector('button[type=submit]')
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function(event) {
            event.preventDefault()
            applyFilter(forms);
        });
    }


    const clearBtn = form.querySelector('button#dt-btn-clear');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(event) {
            event.preventDefault()
            clearFilter(form);
        });
    }
});
