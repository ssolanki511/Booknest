// slidebar in mobile screen
const slidebar_btn = document.querySelector('.slidebar-icon i');
const slidebar = document.querySelector('.slidebar');
if(slidebar_btn){
  slidebar_btn.addEventListener('click', (e) => {
    e.preventDefault();
    if(slidebar.className.includes('-left-full')){
      slidebar.classList.replace('-left-full', 'left-0');
      slidebar_btn.classList.replace('fa-bars', 'fa-x');
    }else{
      slidebar.classList.replace('left-0', '-left-full');
      slidebar_btn.classList.replace('fa-x', 'fa-bars');
    }
  });
}

// book description less and more
const admin_book_des_btn = document.querySelector('.admin-book-more');
const admin_book_description = document.querySelector('.admin-book-description');
if(admin_book_des_btn){
  admin_book_des_btn.addEventListener('click', (e) => {
    e.preventDefault();
    if(admin_book_des_btn.innerHTML.includes('More')){
      admin_book_description.classList.remove('line-clamp-3');
      admin_book_des_btn.innerHTML = "Less";
    }else{
      admin_book_description.classList.add('line-clamp-3');
      admin_book_des_btn.innerHTML = "More";
    }
  });
}

// slidebar active and hover
const list_items = document.querySelectorAll('.s li a');
if(list_items){
  list_items.forEach((item)=>{
    if(window.location.href.includes(item.id)){
      item.classList.add('bg-temp');
    }
      
    if(!window.location.href.includes(item.id)){
      item.classList.add('hover:bg-slate-200', 'hover:bg-opacity-15');
    }
  });
}

const user_image = document.querySelector('.user-log');
const admin_detail = document.querySelector('.admin-detail');
const admin_detail_close = document.querySelector('.admin_detail_close');
if(user_image){
  user_image.addEventListener('click', (e) => {
    e.preventDefault();
    admin_detail.classList.replace('-top-full', 'top-0');
  });
}
if(admin_detail_close){
  admin_detail_close.addEventListener('click', (e) => {
    e.preventDefault();
    admin_detail.classList.replace('top-0', '-top-full');
  });
}

window.addEventListener('DOMContentLoaded', function() {
  const description = document.querySelector('.admin-book-description');
  const moreButton = document.querySelector('.admin-book-more');

  if(description && moreButton){
    const isMultiline = description.scrollHeight > description.clientHeight;

    if (!isMultiline) {
      moreButton.style.display = 'none';
    }
  }
});

const category_form = document.querySelector('.category-form');
const category_form_open = document.querySelector('.category-form-open');
const category_form_close = document.querySelector('.category-form-close');
if(category_form_open){
  category_form_open.addEventListener('click', (e) => {
    e.preventDefault();
    category_form.classList.replace('-top-full', 'top-0');
  });
}
if(category_form_close){
  category_form_close.addEventListener('click', (e) => {
    e.preventDefault();
    category_form.classList.replace('top-0', '-top-full');
  });
}