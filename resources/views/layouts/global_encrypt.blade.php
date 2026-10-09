{{-- <script>
    (async function() {
        const originalFetch = window.fetch;
        const encryptRoute = "{{ route('encrypt-request') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        async function encryptPayload(data) {
            const response = await originalFetch(encryptRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    payload: data
                })
            });
            const res = await response.json();
            return res.encrypted;
        }
        document.addEventListener('submit', async function(e) {
            const form = e.target;
            if (form.tagName !== 'FORM' || form.method.toLowerCase() !== 'post') return;

            e.preventDefault();

            const formData = new FormData(form);
            const payload = {};
            for (const [key, value] of formData.entries()) {
                if (key.endsWith('[]')) {
                    const cleanKey = key.slice(0, -2);
                    if (!Array.isArray(payload[cleanKey])) {
                        payload[cleanKey] = [];
                    }
                    payload[cleanKey].push(value);
                } else {
                    payload[key] = value;
                }
            }

            const encrypted = await encryptPayload(payload);
            [...form.elements].forEach(input => {
                if (!['submit', 'button', 'file'].includes(input.type)) {
                    input.disabled = true;
                }
            });
            const encryptedInput = document.createElement('input');
            encryptedInput.type = 'hidden';
            encryptedInput.name = 'payload';
            encryptedInput.value = encrypted;
            form.appendChild(encryptedInput);

            form.submit();
        });

        window.fetch = async function(input, init = {}) {
            if (init?.method?.toLowerCase() === 'post' && init?.body) {
                try {
                    const originalBody = JSON.parse(init.body);
                    const encrypted = await encryptPayload(originalBody);
                    init.body = JSON.stringify({
                        payload: encrypted
                    });
                } catch (e) {
                    console.warn('Could not encrypt fetch body', e);
                }
            }
            return originalFetch(input, init);
        };
        if (window.jQuery) {
            const originalAjax = jQuery.ajax;
            jQuery.ajax = function(options) {
                const originalSuccess = options.success;
                if ((options.type || options.method || 'GET').toLowerCase() === 'post' && options.data) {
                    encryptPayload(options.data).then(encrypted => {
                        options.data = {
                            payload: encrypted
                        };
                        originalAjax.call(jQuery, options).done(originalSuccess);
                    });
                    return;
                }
                return originalAjax.call(jQuery, options);
            };
        }
    })();
</script> --}}

{{-- <script>
    (async function() {
        const originalFetch = window.fetch;
        const encryptRoute = "{{ route('encrypt-request') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        async function encryptPayload(data) {
            const response = await originalFetch(encryptRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    payload: data
                })
            });
            const res = await response.json();
            return res.encrypted;
        }

        // 👇 Intercept jQuery.ajax globally
        if (window.jQuery) {
            const originalAjax = jQuery.ajax;

            jQuery.ajax = function(options) {
                const method = (options.type || options.method || 'GET').toLowerCase();
                if (method === 'post' && options.data instanceof FormData) {
                    // Extract files and non-files
                    const formData = options.data;
                    const fileFields = [];
                    const textFields = {};

                    for (const [key, value] of formData.entries()) {
                        if (value instanceof File) {
                            fileFields.push({
                                key,
                                value
                            });
                        } else {
                            if (key.endsWith('[]')) {
                                const cleanKey = key.slice(0, -2);
                                if (!Array.isArray(textFields[cleanKey])) {
                                    textFields[cleanKey] = [];
                                }
                                textFields[cleanKey].push(value);
                            } else {
                                textFields[key] = value;
                            }
                        }
                    }

                    encryptPayload(textFields).then(encrypted => {
                        const newFormData = new FormData();
                        newFormData.append('payload', encrypted);
                        newFormData.append('_token', csrfToken); // Add token explicitly

                        // Re-add files
                        fileFields.forEach(({
                            key,
                            value
                        }) => {
                            newFormData.append(key, value);
                        });

                        options.data = newFormData;
                        options.processData = false;
                        options.contentType = false;

                        originalAjax.call(jQuery, options);
                    });

                    return;
                }

                // Handle regular object data
                if (method === 'post' && typeof options.data === 'object') {
                    encryptPayload(options.data).then(encrypted => {
                        options.data = {
                            payload: encrypted,
                            _token: csrfToken
                        };
                        originalAjax.call(jQuery, options);
                    });
                    return;
                }

                return originalAjax.call(jQuery, options);
            };
        }

        // Optional: auto-encrypt native fetch POSTs
        window.fetch = async function(input, init = {}) {
            if (init?.method?.toLowerCase() === 'post' && init?.body && typeof init.body === 'string') {
                try {
                    const originalBody = JSON.parse(init.body);
                    const encrypted = await encryptPayload(originalBody);
                    init.body = JSON.stringify({
                        payload: encrypted
                    });
                } catch (e) {
                    console.warn('Could not encrypt fetch body', e);
                }
            }
            return originalFetch(input, init);
        };
    })();
</script> --}}

<script>
    (async function() {
        const originalFetch = window.fetch;
        const encryptRoute = "{{ route('encrypt-request') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        async function encryptPayload(data) {
            const response = await originalFetch(encryptRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    payload: data
                })
            });
            const res = await response.json();
            return res.encrypted;
        }

        function extractTextFields(formData) {
            const textFields = {};
            for (const [key, value] of formData.entries()) {
                if (!(value instanceof File)) {
                    if (key.endsWith('[]')) {
                        const cleanKey = key.slice(0, -2);
                        if (!Array.isArray(textFields[cleanKey])) {
                            textFields[cleanKey] = [];
                        }
                        textFields[cleanKey].push(value);
                    } else {
                        textFields[key] = value;
                    }
                }
            }
            return textFields;
        }
        // document.addEventListener('submit', async function(e) {
        //     const form = e.target;
        //     if (form.tagName !== 'FORM') return;

        //     const method = (form.method || 'GET').toUpperCase();
        //     if (method !== 'POST') return;

        //     e.preventDefault();

        //     const action = form.action;
        //     const formData = new FormData(form);
        //     const textFields = extractTextFields(formData);

        //     try {
        //         const encrypted = await encryptPayload(textFields);
        //         const newFormData = new FormData();

        //         newFormData.append('payload', encrypted);
        //         newFormData.append('_token', csrfToken);

        //         // Re-append files
        //         for (const [key, value] of formData.entries()) {
        //             if (value instanceof File) {
        //                 newFormData.append(key, value);
        //             }
        //         }
        //         const response = await fetch(action, {
        //             method: 'POST',
        //             body: newFormData
        //         });

        //         const redirectUrl = response.url;
        //         if (response.redirected) {
        //             window.location.href = redirectUrl;
        //         } else {
        //             const result = await response.text();
        //             document.body.innerHTML = result; // or show success/error
        //         }

        //     } catch (error) {
        //         console.error('Form submission error:', error);
        //         toastr.error("Failed to submit the form. Please try again.");
        //     }
        // });
        document.addEventListener('submit', async function (e) {
    const form = e.target;
    if (form.tagName !== 'FORM') return;

    const method = (form.method || 'GET').toUpperCase();
    if (method !== 'POST') return;

    e.preventDefault();

    const formData = new FormData(form);
    const textFields = {};
    for (const [key, value] of formData.entries()) {
        if (!(value instanceof File)) {
            if (key.endsWith('[]')) {
                const cleanKey = key.slice(0, -2);
                if (!Array.isArray(textFields[cleanKey])) {
                    textFields[cleanKey] = [];
                }
                textFields[cleanKey].push(value);
            } else {
                textFields[key] = value;
            }
        }
    }

    try {
        const encrypted = await encryptPayload(textFields);

        // Remove all existing non-file inputs (optional)
        [...form.elements].forEach(el => {
            if (!['submit', 'button', 'file'].includes(el.type)) {
                el.disabled = true;
            }
        });

        // Append the encrypted payload
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'payload';
        hidden.value = encrypted;
        form.appendChild(hidden);

        form.submit(); // 🔁 Native submission to preserve flash/toastr
    } catch (err) {
        console.error('Encryption or submission error:', err);
        toastr.error("Failed to submit the form.");
    }
});



        if (window.jQuery) {
            const originalAjax = jQuery.ajax;

            jQuery.ajax = function(options) {
                const method = (options.type || options.method || 'GET').toLowerCase();

                if (method === 'post' && options.data instanceof FormData) {
                    const formData = options.data;
                    const fileFields = [];
                    const textFields = {};

                    for (const [key, value] of formData.entries()) {
                        if (value instanceof File) {
                            fileFields.push({
                                key,
                                value
                            });
                        } else {
                            if (key.endsWith('[]')) {
                                const cleanKey = key.slice(0, -2);
                                if (!Array.isArray(textFields[cleanKey])) {
                                    textFields[cleanKey] = [];
                                }
                                textFields[cleanKey].push(value);
                            } else {
                                textFields[key] = value;
                            }
                        }
                    }

                    encryptPayload(textFields).then(encrypted => {
                        const newFormData = new FormData();
                        newFormData.append('payload', encrypted);
                        newFormData.append('_token', csrfToken);
                        fileFields.forEach(({
                            key,
                            value
                        }) => newFormData.append(key, value));

                        options.data = newFormData;
                        options.processData = false;
                        options.contentType = false;
                        originalAjax.call(jQuery, options);
                    });

                    return;
                }

                // Object data case
                if (method === 'post' && typeof options.data === 'object') {
                    encryptPayload(options.data).then(encrypted => {
                        options.data = {
                            payload: encrypted,
                            _token: csrfToken
                        };
                        originalAjax.call(jQuery, options);
                    });
                    return;
                }

                return originalAjax.call(jQuery, options);
            };
        }

        // Optional: wrap native fetch to encrypt body
        window.fetch = async function(input, init = {}) {
            if (init?.method?.toLowerCase() === 'post' && init?.body && typeof init.body === 'string') {
                try {
                    const originalBody = JSON.parse(init.body);
                    const encrypted = await encryptPayload(originalBody);
                    init.body = JSON.stringify({
                        payload: encrypted,
                        _token: csrfToken
                    });
                } catch (e) {
                    console.warn('Could not encrypt fetch body', e);
                }
            }
            return originalFetch(input, init);
        };
    })();
</script>
