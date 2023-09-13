document.addEventListener("DOMContentLoaded", function () {
    
    const giftItems = document.querySelectorAll('.gift-item');
    
    

    const displayFormGifts = document.querySelectorAll('.container-display');

    displayFormGifts.forEach(displayFormGift => {
        displayFormGift.addEventListener('click', (event) => {
            event.preventDefault();

            var formcontent = document.querySelector('.container-form-content.allFields');
            var formcontentUrl = document.querySelector('.container-form-content.onlyUrl');

            if (displayFormGift.classList.contains('displayOnlyUrl')) {
                formcontent.style.display = 'none';
                formcontentUrl.style.display = 'block';
                document.querySelector('.displayAllFields').style.display = 'block';
            }else{
                formcontent.style.display = 'block';
                formcontentUrl.style.display = 'none';
                document.querySelector('.displayOnlyUrl').style.display = 'block';
            }

            event.target.parentNode.style.display = 'none';

        });
    });

    var popInCancelGift = document.querySelector('.back-gift-popIn');

    var cancelCloseGifts = popInCancelGift.querySelectorAll('.close-popIn-cancel-gift');
    
    cancelCloseGifts.forEach(cancelCloseGift => {
        cancelCloseGift.addEventListener('click', (e) => {
            e.preventDefault();
            popInCancelGift.style.display = 'none';
        });
    });


    const displayCancelGift = document.querySelector('.display-cancel-gift');
    displayCancelGift.addEventListener('click', (event) => {

        event.preventDefault();

        popInCancelGift.style.display = 'block';

    });
});