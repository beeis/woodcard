$('.burger').click(function() {
    $(this).toggleClass('open');
    $('body').toggleClass('block-skroll');
    $('.header-top').toggleClass('mobile-active');
});
// ------------------------
function handleChange(value) {
    var priceOne = document.querySelector('.price-one');
    var priceTwo = document.querySelector('.price-two');
    var priceThree = document.querySelector('.price-three');
    var fileOne = document.querySelector('.photo-one input[type="file"]');
    var fileTwo = document.querySelector('.photo-two input[type="file"]');
    var fileThree = document.querySelector('.photo-three input[type="file"]');
    if (value == 1) {
        priceThree.style.display = "none";
        priceTwo.style.display = "none";
        priceOne.style.display = "block";

        $('.photo-one').addClass('active');
        $('.photo-two').removeClass('active');
        $('.photo-three').removeClass('active');

        $(fileOne).attr('required', true);
        $(fileTwo).attr('required', false);
        $(fileThree).attr('required', false);
    } else if (value == 2) {
        priceOne.style.display = "none";
        priceThree.style.display = "none";
        priceTwo.style.display = "block";

        $('.photo-one').addClass('active');
        $('.photo-two').addClass('active');
        $('.photo-three').removeClass('active');

        $(fileOne).attr('required', true);
        $(fileTwo).attr('required', true);
        $(fileThree).attr('required', false);
    } else if (value == 3) {
        priceOne.style.display = "none";
        priceTwo.style.display = "none";
        priceThree.style.display = "block";

        $('.photo-one').addClass('active');
        $('.photo-two').addClass('active');
        $('.photo-three').addClass('active');

        $(fileOne).attr('required', true);
        $(fileTwo).attr('required', true);
        $(fileThree).attr('required', true);
    }
}

// Слайдери
var mySwiper = new Swiper('.slider-1', {
    loop: false,
    pagination: {
        el: '.swiper-pagination',
        dynamicBullets: true,
    }
});
var mySwiper2 = new Swiper('.product-slide', {
    loop: false,
    pagination: {
        el: '.swiper-pagination',
        dynamicBullets: false,
    }
});
var mySwiper3 = new Swiper('.comment-slide', {
    loop: false,
    pagination: {
        el: '.swiper-pagination',
        dynamicBullets: true,
    }
});
// -------------------------
// Інпути для загрузки фоток з прев`ю
function readURL(input, photo) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(photo).addClass('active-with-photo');
            $(photo).css('background-image', 'url(' + e.target.result + ')');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".user-image-one").change(function() {
    readURL(this, $('.photo-one'));
});

$(".user-image-two").change(function() {
    readURL(this, $('.photo-two'));
});

$(".user-image-three").change(function() {
    readURL(this, $('.photo-three'));
});
// -------------------------
// Плавний якорь
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
// ---------------------------
// Таймер
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
// -----------------------
// Contact Form Phone Mask
$('#user-phone').inputmask({ "mask": "+38(099) 9999999" });

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