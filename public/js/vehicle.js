(function () {
    /* ---------------------------------------------------------------------
       Photo carousel + thumbnail strip
    --------------------------------------------------------------------- */
    var slides = Array.prototype.slice.call(document.querySelectorAll('.carousel-slide'));
    var dots = Array.prototype.slice.call(document.querySelectorAll('.carousel-dot'));
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.thumb'));
    var slideCountEl = document.getElementById('slideCount');
    var current = 0;

    function goToSlide(index) {
        if (!slides.length) return;
        current = (index + slides.length) % slides.length;
        slides.forEach(function (s, i) { s.classList.toggle('is-active', i === current); });
        dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });
        thumbs.forEach(function (t, i) { t.classList.toggle('is-active', i === current); });
        if (slideCountEl) slideCountEl.textContent = current + 1;
    }

    var prevBtn = document.getElementById('prevSlide');
    var nextBtn = document.getElementById('nextSlide');
    if (prevBtn) prevBtn.addEventListener('click', function () { goToSlide(current - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { goToSlide(current + 1); });
    dots.forEach(function (d) { d.addEventListener('click', function () { goToSlide(parseInt(d.dataset.slide, 10)); }); });
    thumbs.forEach(function (t) { t.addEventListener('click', function () { goToSlide(parseInt(t.dataset.slide, 10)); }); });

    var track = document.querySelector('.carousel-track');
    if (track) {
        var touchStartX = null;
        track.addEventListener('touchstart', function (e) { touchStartX = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', function (e) {
            if (touchStartX === null) return;
            var dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 40) goToSlide(current + (dx < 0 ? 1 : -1));
            touchStartX = null;
        });
    }
})();
