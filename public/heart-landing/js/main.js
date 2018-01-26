let radioOne = document.querySelector('.radio-one');
let radioTwo = document.querySelector('.radio-two');
let radioThree = document.querySelector('.radio-three');

let priceOne = document.querySelector('.price-one');
let priceTwo = document.querySelector('.price-two');
let priceThree = document.querySelector('.price-three');

let photoOne = document.querySelector('.photo-one');
let photoTwo = document.querySelector('.photo-two');
let photoThree = document.querySelector('.photo-three');

let fileOne = document.querySelector('.photo-one input[type="file"]');
let fileTwo = document.querySelector('.photo-two input[type="file"]');
let fileThree = document.querySelector('.photo-three input[type="file"]');

function handleChangeOne(checkbox) {
    if (radioOne.checked == true) {
        priceThree.style.display = "none";
        priceTwo.style.display = "none";
        priceOne.style.display = "block";

        $(photoOne).addClass('active');
        $(photoTwo).removeClass('active');
        $(photoThree).removeClass('active');

        $(priceOne).addClass('active');
        $(priceTwo).removeClass('active');
        $(priceThree).removeClass('active');

        $(fileOne).attr('required', true);
        $(fileTwo).attr('required', false);
        $(fileThree).attr('required', false);
    }
}

function handleChangeTwo(checkbox) {
    if (radioTwo.checked == true) {
        priceOne.style.display = "none";
        priceThree.style.display = "none";
        priceTwo.style.display = "block";

        $(photoOne).addClass('active');
        $(photoTwo).addClass('active');
        $(photoThree).removeClass('active');

        $(priceTwo).addClass('active');
        $(priceOne).removeClass('active');
        $(priceThree).removeClass('active');

        $(fileOne).attr('required', true);
        $(fileTwo).attr('required', true);
        $(fileThree).attr('required', false);
    }
}

function handleChangeThree(checkbox) {
    if (radioThree.checked == true) {
        priceOne.style.display = "none";
        priceTwo.style.display = "none";
        priceThree.style.display = "block";

        $(photoOne).addClass('active');
        $(photoTwo).addClass('active');
        $(photoThree).addClass('active');

        $(priceThree).addClass('active');
        $(priceTwo).removeClass('active');
        $(priceOne).removeClass('active');

        $(fileOne).attr('required', true);
        $(fileTwo).attr('required', true);
        $(fileThree).attr('required', true);
    }
}
// 1й інпут
function readURLOne(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(photoOne).addClass('active-with-photo');
            $(photoOne).css('background-image', 'url(' + e.target.result + ')');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".user-image-one").change(function() {
    readURLOne(this);
});
// 2й інпут
function readURLTwo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(photoTwo).addClass('active-with-photo');
            $(photoTwo).css('background-image', 'url(' + e.target.result + ')');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".user-image-two").change(function() {
    readURLTwo(this);
});
// 3й інпут
function readURLThree(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(photoThree).addClass('active-with-photo');
            $(photoThree).css('background-image', 'url(' + e.target.result + ')');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".user-image-three").change(function() {
    readURLThree(this);
});

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
let k4 = 1;
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
    if (null !== sliderOneNumbers) {
        sliderOneNumbers.textContent = `${k1}/${maxSlidesOne}`;
    }
    // 2й слайдер
    let allSlidesTwo = document.querySelectorAll('.product .item');
    let maxSlidesTwo = allSlidesTwo.length;
    if (k2 > maxSlidesTwo) {
        k2 = 1;
    } else if (k2 < 1) {
        k2 = maxSlidesTwo;
    }
    let sliderTwoNumbers = document.querySelector('.product .slide-number');
    if (null !== sliderTwoNumbers) {
        sliderTwoNumbers.textContent = `${k2}/${maxSlidesTwo}`;
    }
    // 3й слайдер
    let allSlidesThree = document.querySelectorAll('.comment .item');
    let maxSlidesThree = allSlidesThree.length;
    if (k3 > maxSlidesThree) {
        k3 = 1;
    } else if (k3 < 1) {
        k3 = maxSlidesThree;
    }
    let sliderThreeNumbers = document.querySelector('.comment .slide-number');
    if (null !== sliderThreeNumbers) {
        sliderThreeNumbers.textContent = `${k3}/${maxSlidesThree}`;
    }

    let allSlidesFor = document.querySelectorAll('.slider-2 .item');
    let maxSlidesFor = allSlidesFor.length;
    if (k4 > maxSlidesFor) {
        k4 = 1;
    } else if (k4 < 1) {
        k4 = maxSlidesFor;
    }
    let sliderForNumbers = document.querySelector('.slider-2 .slide-number');
    if (null !== sliderForNumbers) {
        sliderForNumbers.textContent = `${k4}/${maxSlidesFor}`;
    }
}
slideNumbers();
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

$(".slider-2 .carousel").swipe({
    swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
        if (direction == 'left') {
            $(this).carousel('next');
            k4++;
            slideNumbers(k4);
        }
        if (direction == 'right') {
            $(this).carousel('prev');
            k4--;
            slideNumbers(k4);
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
$('#user-phone').inputmask({ "mask": "+38(099) 9999999" });

// Count loaded images
$('.user-images').on("change", function() {
    const files = $('.user-files-loaded');
    $(this).get(0).files ? files.html($(this).get(0).files.length + ' файл(ів) вибрано').show() : files.hide();
});

// Handle form submit
$('.user-form').on('submit', function(e) {
    $('.heart-spinner-wrap').show();
    e.preventDefault();
    const form = $('form.user-form'),
        quantity = $('input[name="quantity"]:checked').val(),
        price = $('.price.active').data("price"),
        name = form.find('[name="name"]')[0].value,
        phone = form.find('[name="phone"]')[0].value;
    var utm_source = form.find('[name="utm_source"]')[0].value;
    var utm_medium = form.find('[name="utm_medium"]')[0].value;
    var utm_term = form.find('[name="utm_term"]')[0].value;
    var utm_content = form.find('[name="utm_content"]')[0].value;
    var utm_campaign = form.find('[name="utm_campaign"]')[0].value;

    $.ajax({
        url: '/order',
        method: 'POST',
        data: {
            name: name,
            products: {
                "1": {
                    product_id: 3,
                    price: price / quantity,
                    count: quantity
                }
            },
            phone: phone,
            utm_source: utm_source,
            utm_medium: utm_medium,
            utm_term: utm_term,
            utm_content: utm_content,
            utm_campaign: utm_campaign
        }
    }).done(function(response) {
        console.log(response);
        if (response.status === "error" && response.message[0] === "Дублирующая заявка") {
            alert('Дані вже були відправлені');
            $('.heart-spinner-wrap').hide();
        } else if (response.status === "ok") {
            const id = response.data[0].order_id;

            let formData = new FormData();
            for (let i = 0; i < $('.active .user-images').length; i++) {
                formData.append('files[]', $('.user-images').get(i).files[0]);
            }
            $.ajax({
                url: '/order/' + id + '/items',
                method: 'POST',
                contentType: false,
                data: formData,
                processData: false
            }).done(function() {
                $('.heart-spinner-wrap').hide();
                alert('Дані були успішно відправлені!');
                window.location.href = '/thankyoupage';
            }).fail(function() {
                $('.heart-spinner-wrap').hide();
                alert('Сталась помилка під час відправки даних');
            });
        }
    }).fail(function() {
        $('.heart-spinner-wrap').hide();
        alert('Сталась помилка під час відправки даних');
    });
});
