let radioOne = document.querySelector('.radio-one');
let radioTwo = document.querySelector('.radio-two');
let radioThree = document.querySelector('.radio-three');

let priceOne = document.querySelector('.price-one');
let priceTwo = document.querySelector('.price-two');
let priceThree = document.querySelector('.price-three');

function handleChangeOne(checkbox) {
    if (radioOne.checked == true) {
        priceThree.style.display = "none";
        priceTwo.style.display = "none";
        priceOne.style.display = "block";
    }
}

function handleChangeTwo(checkbox) {
    if (radioTwo.checked == true) {
        priceOne.style.display = "none";
        priceThree.style.display = "none";
        priceTwo.style.display = "block";
    }
}

function handleChangeThree(checkbox) {
    if (radioThree.checked == true) {
        priceOne.style.display = "none";
        priceTwo.style.display = "none";
        priceThree.style.display = "block";
    }
}

$(function() {
    $('button[data-target^="anchor"]').bind('click.smoothscroll', function() {
        var target = $(this).attr('href'),
            bl_top = $(target).offset().top;

        $('body, html').animate({
            scrollTop: bl_top
        }, 700);
        return false;
    });
});

let carouselInner = document.querySelectorAll('.carousel-inner');




for (let i = 0; i < carouselInner.length; i++) {
    let span = document.createElement('span');
    span.classList.add('slide-number');
    carouselInner[i].appendChild(span);
}

let k = 1;
let k1 = 1;
let k2 = 1;
let k3 = 1;
const slideNumbers = () => {
    // 1й слайдер
    let allSlidesOne = document.querySelectorAll('.slider .item');
    let maxSlidesOne = allSlidesOne.length;
    if (k1 > maxSlidesOne) {
        k1 = 1;
    } else if (k1 < 1) {
        k1 = maxSlidesOne;
    }
    let sliderOneNumbers = document.querySelector('.slider .slide-number');
    sliderOneNumbers.textContent = `${k1}/${maxSlidesOne}`;
    // 2й слайдер
    let allSlidesTwo = document.querySelectorAll('.product .item');
    let maxSlidesTwo = allSlidesTwo.length;
    if (k2 > maxSlidesTwo) {
        k2 = 1;
    } else if (k2 < 1) {
        k2 = maxSlidesTwo;
    }
    let sliderTwoNumbers = document.querySelector('.product .slide-number');
    sliderTwoNumbers.textContent = `${k2}/${maxSlidesTwo}`;
    // 3й слайдер
    let allSlidesThree = document.querySelectorAll('.comment .item');
    let maxSlidesThree = allSlidesThree.length;
    if (k3 > maxSlidesThree) {
        k3 = 1;
    } else if (k3 < 1) {
        k3 = maxSlidesThree;
    }
    let sliderThreeNumbers = document.querySelector('.comment .slide-number');
    sliderThreeNumbers.textContent = `${k3}/${maxSlidesThree}`;

}
slideNumbers()
    // 1й слайдер
$(".slider .carousel").swipe({
    swipe: function(event, direction, distance, duration, fingerCount, fingerData) {


        if (direction == 'left') {
            $(this).carousel('next');
            k1++;
            slideNumbers();
        }
        if (direction == 'right') {
            $(this).carousel('prev');
            k1--;
            slideNumbers();
        }
    },
    allowPageScroll: "vertical",

});
// 2й слайдер
$(".product .carousel").swipe({
    swipe: function(event, direction, distance, duration, fingerCount, fingerData) {


        if (direction == 'left') {
            $(this).carousel('next');
            k2++;
            slideNumbers(k2);
        }
        if (direction == 'right') {
            $(this).carousel('prev');
            k2--;
            slideNumbers(k2);
        }
    },
    allowPageScroll: "vertical",

});
// 3й слайдер
$(".comment .carousel").swipe({
    swipe: function(event, direction, distance, duration, fingerCount, fingerData) {


        if (direction == 'left') {
            $(this).carousel('next');
            k3++;
            slideNumbers(k3);
        }
        if (direction == 'right') {
            $(this).carousel('prev');
            k3--;
            slideNumbers(k3);
        }
    },
    allowPageScroll: "vertical",

});


var timer;

var compareDate = new Date();
compareDate.setDate(compareDate.getDate() + 7);

timer = setInterval(function() {
    timeBetweenDates(compareDate);
}, 1000);

function timeBetweenDates(toDate) {
    var dateEntered = toDate;
    var now = new Date();
    var difference = dateEntered.getTime() - now.getTime();

    if (difference <= 0) {

        // Timer done
        clearInterval(timer);

    } else {

        var seconds = Math.floor(difference / 1000);
        var minutes = Math.floor(seconds / 60);
        var hours = Math.floor(minutes / 60);

        hours %= 24;
        minutes %= 60;
        seconds %= 60;

        $(".product-timer-time-hours").text(hours);
        $(".product-timer-time-minutes").text(minutes);
        $(".product-timer-time-seconds").text(seconds);
    }
}

// Contact Form Phone Mask
$('#user-phone').inputmask({"mask": "+38(999) 99-99-999"});

// Count loaded images
$('.user-images').on("change", function() {
    const files = $('.user-files-loaded');
  $(this).get(0).files.length ? files.html($(this).get(0).files.length + ' файл(ів) завантажено').show() :
    files.hide();
});