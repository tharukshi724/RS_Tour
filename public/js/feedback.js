(function () {
    var openBtn = document.getElementById('openFeedbackBtn');
    var modal = document.getElementById('feedbackModal');
    if (!openBtn || !modal) return;

    var closeBtn = document.getElementById('feedbackModalClose');
    var form = document.getElementById('feedbackForm');
    var errorEl = document.getElementById('feedbackFormError');
    var successEl = document.getElementById('feedbackSuccess');
    var doneBtn = document.getElementById('feedbackDoneBtn');
    var submitBtn = document.getElementById('feedbackSubmitBtn');

    var stars = Array.prototype.slice.call(document.querySelectorAll('.feedback-star'));
    var ratingInput = document.getElementById('feedbackRating');

    var endpoint = document.body.getAttribute('data-feedback-api') || 'api/feedback.php';

    function setStars(value) {
        stars.forEach(function (s) {
            var starValue = parseInt(s.getAttribute('data-value'), 10);
            var filled = starValue <= value;
            s.classList.toggle('is-filled', filled);
            s.setAttribute('aria-checked', filled ? 'true' : 'false');
        });
    }

    stars.forEach(function (s) {
        s.addEventListener('click', function () {
            var value = parseInt(s.getAttribute('data-value'), 10);
            ratingInput.value = String(value);
            setStars(value);
        });
        s.addEventListener('mouseenter', function () {
            setStars(parseInt(s.getAttribute('data-value'), 10));
        });
    });
    var starsWrap = document.getElementById('feedbackStars');
    if (starsWrap) {
        starsWrap.addEventListener('mouseleave', function () {
            setStars(parseInt(ratingInput.value, 10) || 0);
        });
    }

    function resetForm() {
        form.reset();
        ratingInput.value = '0';
        setStars(0);
        errorEl.hidden = true;
        errorEl.textContent = '';
        form.hidden = false;
        successEl.hidden = true;
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Feedback';
    }

    function openModal() {
        resetForm();
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        var nameField = document.getElementById('feedbackName');
        if (nameField) setTimeout(function () { nameField.focus(); }, 50);
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (doneBtn) doneBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        errorEl.hidden = true;

        var name = document.getElementById('feedbackName').value.trim();
        var comments = document.getElementById('feedbackComments').value.trim();
        var rating = parseInt(ratingInput.value, 10) || 0;

        if (!name) {
            errorEl.textContent = 'Please enter your name.';
            errorEl.hidden = false;
            return;
        }
        if (rating < 1) {
            errorEl.textContent = 'Please pick a star rating.';
            errorEl.hidden = false;
            return;
        }
        if (!comments) {
            errorEl.textContent = 'Please add a few words about your experience.';
            errorEl.hidden = false;
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting\u2026';

        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name, comments: comments, rating: rating })
        })
            .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, data: data }; }); })
            .then(function (res) {
                if (!res.ok || !res.data || res.data.ok !== true) {
                    var msg = (res.data && res.data.error) ? res.data.error : 'Could not submit your feedback. Please try again.';
                    throw new Error(msg);
                }
                form.hidden = true;
                successEl.hidden = false;
            })
            .catch(function (err) {
                errorEl.textContent = err.message || 'Could not submit your feedback. Please try again.';
                errorEl.hidden = false;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Feedback';
            });
    });
})();
