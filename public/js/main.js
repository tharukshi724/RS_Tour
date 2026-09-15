(function () {
    // Navbar solid-on-scroll
    var navbar = document.getElementById('navbar');
    function onScroll() {
        if (!navbar) return;
        if (window.scrollY > 24) navbar.classList.add('is-scrolled');
        else navbar.classList.remove('is-scrolled');
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    var siTagline = document.getElementById('siTagline');
    if (siTagline) {
        var startSiLoop = function () {
            var fullWidth = siTagline.scrollWidth;
            if (fullWidth > 0) {
                siTagline.style.setProperty('--si-width', fullWidth + 'px');
                siTagline.classList.add('is-looping');
            }
        };
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(startSiLoop);
        } else {
            setTimeout(startSiLoop, 300);
        }
    }

    // Mobile menu toggle
    var toggle = document.getElementById('navToggle');
    var links = document.getElementById('navLinks');
    if (toggle && links) {
        toggle.addEventListener('click', function () {
            var open = links.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        links.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () {
                links.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // Vehicle category filter (home page only)
    var categorySelect = document.getElementById('categoryFilter');
    var grid = document.getElementById('vehicleGrid');
    if (categorySelect && grid) {
        var cards = Array.prototype.slice.call(grid.querySelectorAll('.vcard'));
        categorySelect.addEventListener('change', function () {
            var filter = categorySelect.value;
            cards.forEach(function (card) {
                var show = filter === 'all' || card.dataset.category === filter;
                card.style.display = show ? '' : 'none';
            });
        });
    }

    // Hero "quick book" widget — local autocomplete on the location field,
    // then hands the date/time off to the vehicle page via localStorage so
    // the trip-planner there opens pre-filled. No fake availability search:
    // it scrolls to the real fleet, same list every time.
    var bookLocation = document.getElementById('bookLocation');
    var bookDate = document.getElementById('bookDate');
    var bookTime = document.getElementById('bookTime');
    var bookWidget = document.getElementById('bookWidget');
    var heroSuggestions = document.getElementById('heroLocationSuggestions');

    if (bookDate) {
        var todayStr = new Date().toISOString().slice(0, 10);
        bookDate.min = todayStr;
        bookDate.value = todayStr;
    }

    if (bookLocation && heroSuggestions) {
        var heroMatches = [];
        bookLocation.addEventListener('input', function () {
            var q = bookLocation.value.trim().toLowerCase();
            if (!q || !window.SRI_LANKA_LOCATIONS) { heroSuggestions.classList.remove('is-open'); return; }
            heroMatches = window.SRI_LANKA_LOCATIONS
                .filter(function (name) { return name.toLowerCase().indexOf(q) !== -1; })
                .slice(0, 6);
            if (!heroMatches.length) { heroSuggestions.classList.remove('is-open'); return; }
            heroSuggestions.innerHTML = heroMatches.map(function (name, i) {
                return '<button type="button" class="location-suggestion" data-index="' + i + '">' + name + '</button>';
            }).join('');
            heroSuggestions.classList.add('is-open');
        });
        heroSuggestions.addEventListener('click', function (e) {
            var btn = e.target.closest('.location-suggestion');
            if (!btn) return;
            bookLocation.value = heroMatches[parseInt(btn.dataset.index, 10)];
            heroSuggestions.classList.remove('is-open');
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.book-field-location')) heroSuggestions.classList.remove('is-open');
        });
    }

    if (bookWidget) {
        bookWidget.addEventListener('submit', function (e) {
            e.preventDefault();
            try {
                localStorage.setItem('rstoursPrefill', JSON.stringify({
                    location: bookLocation ? bookLocation.value.trim() : '',
                    date: bookDate ? bookDate.value : '',
                    time: bookTime ? bookTime.value : '',
                }));
            } catch (err) { /* localStorage unavailable — safe to ignore, just skip the prefill */ }
            var target = document.getElementById('vehicles');
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    }

    // Scroll-reveal: fade+rise cards, steps and section headers into view
    var revealEls = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
    if (revealEls.length) {
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
            revealEls.forEach(function (el, i) {
                el.style.transitionDelay = (i % 4 * 80) + 'ms';
                io.observe(el);
            });
        } else {
            revealEls.forEach(function (el) { el.classList.add('is-visible'); });
        }
    }

    // Quote forms — both just build a WhatsApp message from the fields typed
    // in. There's no backend on this site, so WhatsApp is the single place
    // every enquiry ends up, same as the "Hire" button on a vehicle page.
    var waNumber = document.body.getAttribute('data-whatsapp') || window.WHATSAPP_NUMBER;
    var siteName = document.body.getAttribute('data-sitename') || window.SITE_NAME || '';

    function openWhatsApp(message) {
        if (!waNumber) return;
        window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(message), '_blank');
    }

    var quickForm = document.getElementById('quoteQuickForm');
    if (quickForm) {
        quickForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var need = quickForm.elements.need.value.trim();
            if (!need) return;
            openWhatsApp('Hi ' + siteName + '! ' + need);
            quickForm.reset();
        });
    }

    var detailForm = document.getElementById('quoteDetailForm');
    if (detailForm) {
        detailForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var f = detailForm.elements;
            var lines = [
                'Hi ' + siteName + '! I\'d like a quote.',
                'Name: ' + f.name.value.trim(),
                'WhatsApp number: ' + f.phone.value.trim(),
            ];
            if (f.dates.value.trim()) lines.push('Dates: ' + f.dates.value.trim());
            if (f.message.value.trim()) lines.push('Details: ' + f.message.value.trim());
            openWhatsApp(lines.join('\n'));
            detailForm.reset();
        });
    }
})();


/* =========================================================================
   Carousel — drives every [data-carousel] on the page (services, reviews).
   The track is a CSS scroll-snap strip, so swipe and trackpad scrolling
   already work without this file; what follows adds arrows, page dots,
   autoplay and keyboard support, and keeps all three in sync with whatever
   the user does by hand.
   ========================================================================= */
(function () {
    var carousels = Array.prototype.slice.call(document.querySelectorAll('[data-carousel]'));
    if (!carousels.length) return;

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    carousels.forEach(function (root) {
        var viewport = root.querySelector('.tcar-viewport');
        var dotsWrap = root.querySelector('.tcar-dots');
        var prevBtn = root.querySelector('[data-dir="prev"]');
        var nextBtn = root.querySelector('[data-dir="next"]');
        if (!viewport) return;

        var pages = 1;
        var page = 0;
        var timer = null;
        var paused = false;
        var autoplayMs = parseInt(root.getAttribute('data-autoplay') || '0', 10);

        function pageWidth() { return viewport.clientWidth; }

        function countPages() {
            var w = pageWidth();
            if (!w) return 1;
            // 2px slack so sub-pixel widths don't invent a phantom last page
            return Math.max(1, Math.ceil((viewport.scrollWidth - 2) / w));
        }

        function goTo(index, instant) {
            page = Math.max(0, Math.min(index, pages - 1));
            viewport.scrollTo({
                left: page * pageWidth(),
                behavior: (instant || reduceMotion) ? 'auto' : 'smooth'
            });
            paint();
        }

        function paint() {
            if (prevBtn) prevBtn.disabled = page <= 0;
            if (nextBtn) nextBtn.disabled = page >= pages - 1;
            if (!dotsWrap) return;
            Array.prototype.forEach.call(dotsWrap.children, function (dot, i) {
                var active = i === page;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-current', active ? 'true' : 'false');
            });
        }

        function buildDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            for (var i = 0; i < pages; i++) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'tcar-dot';
                dot.setAttribute('aria-label', 'Go to page ' + (i + 1) + ' of ' + pages);
                (function (index) {
                    dot.addEventListener('click', function () { stop(); goTo(index); start(); });
                })(i);
                dotsWrap.appendChild(dot);
            }
        }

        function measure() {
            var next = countPages();
            if (next !== pages) {
                pages = next;
                buildDots();
            }
            root.classList.toggle('is-static', pages <= 1);
            page = Math.min(page, pages - 1);
            paint();
        }

        // Keep state honest when the user swipes or trackpad-scrolls by hand
        var scrollTick = null;
        viewport.addEventListener('scroll', function () {
            if (scrollTick) return;
            scrollTick = window.requestAnimationFrame(function () {
                scrollTick = null;
                var w = pageWidth();
                if (w) page = Math.round(viewport.scrollLeft / w);
                paint();
            });
        }, { passive: true });

        if (prevBtn) prevBtn.addEventListener('click', function () { stop(); goTo(page - 1); start(); });
        if (nextBtn) nextBtn.addEventListener('click', function () { stop(); goTo(page + 1); start(); });

        viewport.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { e.preventDefault(); stop(); goTo(page + 1); start(); }
            if (e.key === 'ArrowLeft') { e.preventDefault(); stop(); goTo(page - 1); start(); }
        });

        function tick() {
            if (paused || pages <= 1 || document.hidden) return;
            goTo(page >= pages - 1 ? 0 : page + 1);
        }

        function start() {
            if (!autoplayMs || reduceMotion) return;
            stop();
            timer = window.setInterval(tick, autoplayMs);
        }
        function stop() {
            if (timer) { window.clearInterval(timer); timer = null; }
        }

        ['mouseenter', 'focusin', 'touchstart', 'pointerdown'].forEach(function (evt) {
            root.addEventListener(evt, function () { paused = true; }, { passive: true });
        });
        ['mouseleave', 'focusout'].forEach(function (evt) {
            root.addEventListener(evt, function () { paused = false; }, { passive: true });
        });
        root.addEventListener('touchend', function () {
            window.setTimeout(function () { paused = false; }, 4000);
        }, { passive: true });

        var resizeTick = null;
        window.addEventListener('resize', function () {
            window.clearTimeout(resizeTick);
            resizeTick = window.setTimeout(function () { measure(); goTo(page, true); }, 150);
        });

        measure();
        start();
    });
})();
