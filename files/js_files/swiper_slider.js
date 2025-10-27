var swiper = new Swiper(".hero-swiper", {
    loop:true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
        clickable: true
    },
});

var swiper = new Swiper(".you-like-swiper", {
    spaceBetween: 30,
    navigation: {
        nextEl: ".you-like-btn-next",
        prevEl: ".you-like-btn-prev",
    },
    breakpoints:{
        0:{
            slidesPerView:1
        },
        610:{
            slidesPerView:2
        },
        930:{
            slidesPerView:3
        },
        1050:{
            slidesPerView:3
        },
        1220:{
            slidesPerView:4
        }
    }
});