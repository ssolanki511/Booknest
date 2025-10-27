// navbar sliding in small screen
const slider = document.querySelector('.slider');
const bottom_nav = document.querySelector('.bottom');
const profile_box = document.querySelector('.profile-box');
const body = document.querySelector('body');
const slider_icon = document.querySelector('.mobile-scroll');

if(slider_icon){
    slider_icon.addEventListener('click', () => {
        if(slider_icon.className.includes('fa-bars')){
            slider.classList.replace('-translate-x-full', '-translate-x-0');
            bottom_nav.classList.replace('-translate-y-0', '-translate-y-20');
            profile_box.classList.replace('translate-x-0', 'translate-x-32');
            body.classList.add('overflow-hidden');
            slider_icon.classList.replace('fa-bars', 'fa-x');
        }else{
            slider.classList.replace('-translate-x-0','-translate-x-full');
            bottom_nav.classList.replace('-translate-y-20', '-translate-y-0');
            profile_box.classList.replace('translate-x-32', 'translate-x-0');
            body.classList.remove('overflow-hidden');
            slider_icon.classList.replace('fa-x', 'fa-bars');
        }
    });
}

// genre drow down
const drop_down_icon = document.querySelector('.m-genre-box');
const drop_down = document.querySelector('.m-drop-down');
const m_genre_rotate_icon = document.querySelector('.m-genre-rotate-icon');
if(drop_down_icon){
    drop_down_icon.addEventListener('click', () => {
        if(drop_down.className.includes('hidden')){
            drop_down.classList.replace('hidden','block');
            m_genre_rotate_icon.classList.add('rotate-180');
        }else{
            drop_down.classList.replace('block', 'hidden');
            m_genre_rotate_icon.classList.remove('rotate-180');
        }
    });
}

// display user details and register/login box in big screen
const user_display = document.querySelector('.user-semi-box');
const user_icon_container = document.querySelector('.user-icon-container');
if(user_icon_container){
    user_icon_container.addEventListener('click', () => {
        if(user_display.className.includes('hidden')){
            user_display.classList.replace('hidden','block');
        }else{
            user_display.classList.replace('block','hidden');
        }
    });
}


//product page share button
const share_icon = document.querySelector('.share-icon');
const share_box = document.querySelector('.share-box');
if(share_icon){
    share_icon.addEventListener('click', (e) => {
        e.preventDefault();
        if(share_box.className.includes('hidden')){
            share_box.classList.remove('hidden');
        }else{
            share_box.classList.add('hidden');
        }
    });
}

const otp = document.querySelectorAll('.otp_field');
if(otp.length > 0){
    otp[0].focus();
    otp.forEach((field, index) => {
        field.addEventListener('keydown',(e) => {
            if(e.key >= 0 && e.key <= 9){
                otp[index].value = "";
                setTimeout(() => {
                    otp[index+1].focus();
                }, 4)
            }
            else if(e.key === 'Backspace'){
                setTimeout(() => {
                    otp[index-1].focus();
                }, 4)
            }
        });
    });
}

const otpForm = document.querySelector('#otpForm');
const reset_password_email = document.querySelector('#reset_password_email');
if(otpForm){
    otpForm.addEventListener('submit', () => {
        let isValid = true;
        const otpValues = [];

        otp.forEach((field) => {
            const value = field.value.trim();
            if (value === '' || isNaN(value) || value.length !== 1) {
                isValid = false;
            } else {
                otpValues.push(value);
            }
        });

        if (!isValid) {
            alert('Please fill all OTP fields correctly.');
        }
        const Email = reset_password_email.value.trim();
        const otpCode = otpValues.join('');

        // Submit the form via AJAX or proceed with form submission
        $.ajax({
            url: 'check_otp.php', // Replace with your server-side endpoint
            type: 'POST',
            data: { 
                otp: otpCode,
                email : Email
             },
            success: function (response) {
                const data = response.split('|');
                if (data[0].trim() == 'Success') {
                    window.location.href = "reset-password.php?email="+data[1].trim();
                }else{
                    window.location.href = "forgot-password.php";
                }
            },
            error: function () {
                alert('An error occurred while verifying the OTP.');
            }
        });
    });
}

function toggleEditProfile() {
    const editProfileSection = document.getElementById('edit-profile-section');
    editProfileSection.classList.toggle('hidden');
}
function toggleChangePassword() {
    const changePasswordSection = document.getElementById('change-password-section');
    changePasswordSection.classList.toggle('hidden');
}
function logout() {
    window.location.href = 'logout.php';
}

const read_more = document.querySelector('.read-more');
const read_container = document.querySelector('.read-container');
if(read_more){
    read_more.addEventListener('click', (e) => {
        e.preventDefault()
        if(read_more.innerHTML.includes('Read More'))
        {
            read_container.classList.remove('line-clamp-3');
            read_more.innerHTML= "Read Less";
        }else{
            read_container.classList.add('line-clamp-3');
            read_more.innerHTML= "Read More";
        }
    });
}

window.addEventListener('DOMContentLoaded', function() {
    const description = document.querySelector('.read-container');
    const moreButton = document.querySelector('.read-more');

    if(description){
        const isMultiline = description.scrollHeight > description.clientHeight;

        if (!isMultiline) {
            moreButton.style.display = 'none';
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const copyLinkBtn = document.getElementById('copy-link-btn');
    const currentUrl = window.location.href;

    if(copyLinkBtn){
        copyLinkBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(currentUrl).then(() => {
                share_box.classList.add('hidden');
                alert('Link copied successfully!');
            }).catch((err) => {
                alert('Faild to copied link!');
            });
        });
    }
});