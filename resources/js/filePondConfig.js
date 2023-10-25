export default function filePondServer(csrfToken, avatar, extension) {
    return {
        process: route('uploads.process'),
        revert: (uniqueFileId, load, error) => {
            const payloadObj = {
                filename: avatar,
                extension: extension,
            };

            fetch(route('uploads.destroy'), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payloadObj),
            })
                .then((response) => {
                    if (response.status === 200) {
                        load();
                    } else {
                        error('An error occurred');
                    }
                })
                .catch(() => {
                    error('An error occurred');
                });
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
    };
}
