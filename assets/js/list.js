document.addEventListener("DOMContentLoaded", function () {
    const showGiftAdd = document.querySelector('.showGiftAdd');
    showGiftAdd.addEventListener('click', (e) => {
        e.preventDefault();
        const divGiftAdd = document.querySelector('.addGift');
        divGiftAdd.style.display = 'block';
    });

})