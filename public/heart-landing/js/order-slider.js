let carouselInner = document.querySelectorAll('.carousel-inner');

for (let i = 0; i < carouselInner.length; i++) {
    let span = document.createElement('span');
    span.classList.add('slide-number');
    carouselInner[i].appendChild(span);
}


let k4 = 1;
const slideNumbers = () => {

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