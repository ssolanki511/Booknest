//link update form open and close
const link_form = document.querySelector('.link-form');
const link_form_close = document.querySelector('.link-form-close');
const link_form_opens = document.querySelectorAll('.link-form-open');
if(link_form_opens){
    link_form_opens.forEach((link_open) => {
        link_open.addEventListener('click', (e) => {
            e.preventDefault();
            const link_id = link_open.getAttribute('data-id');

            $.ajax({
                url: 'fetch_link_data.php',
                type: 'POST',

                data: { id: link_id },
                success: function (response) {
                    // Split the response by the delimiter
                    const data = response.split('|');
                    if (data.length === 2) {
                        document.querySelector('#link_title').value = data[0];
                        document.querySelector('#link_url').value = data[1];
                        link_form.classList.replace('-top-full', 'top-0');
                    } else {
                        alert('Failed to fetch data.');
                    }
                },
                error: function () {
                    alert('An error occurred while fetching the data.');
                }
            });
        })
    })
}
if(link_form_close){
    link_form_close.addEventListener('click', (e) => {
        e.preventDefault();
        link_form.classList.replace('top-0','-top-full');
    })
}

// banner form open and close
const banner_form = document.querySelector('.banner-form');
const banner_form_open = document.querySelector('.banner-form-open');
const banner_form_close = document.querySelector('.banner-form-close');
banner_form_open.addEventListener('click', (e) => {
    e.preventDefault();
    banner_form.classList.replace('-top-full', 'top-0');
});
banner_form_close.addEventListener('click', (e) => {
    e.preventDefault();
    banner_form.classList.replace('top-0', '-top-full');
});

// banner status is active or inactive
const statuses = document.querySelectorAll('.status');
function updateStatusColor(status) {
    if (status.value === 'inactive') {
        status.classList.remove('text-green-500', 'border-green-500');
        status.classList.add('text-red-600', 'border-red-600');
    } else {
        status.classList.remove('text-red-600', 'border-red-600');
        status.classList.add('text-green-500', 'border-green-500');
    }
}

statuses.forEach((status) => {
    updateStatusColor(status);
    status.addEventListener('change', (e) => {
        e.preventDefault();
        updateStatusColor(status);

        const Type = status.getAttribute('data-type');
        const Id = status.getAttribute('data-id');
        const newStatus = status.value;

        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: {
                type: Type,
                id : Id,
                status: newStatus
            },
            success: function (response) {
                window.location.href="admin_advertisement.php";
            },
            error: function () {
                alert('An error occurred while updating the status.');
            }
        });
    });
});