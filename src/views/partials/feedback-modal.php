<?php
/**
 * "Add Feedback" modal - opened by #openFeedbackBtn, handled by
 * public/js/feedback.js. Submits to public/api/feedback.php, which saves
 * into data/feedback.json (see FeedbackStore.php).
 */
?>
<div class="feedback-modal" id="feedbackModal" aria-hidden="true">
    <div class="feedback-modal-box" role="dialog" aria-modal="true" aria-labelledby="feedbackModalTitle">
        <button type="button" class="feedback-modal-close" id="feedbackModalClose" aria-label="Close"><?= icon_svg('close') ?></button>

        <div class="feedback-modal-head">
            <span class="feedback-modal-eyebrow"><?= icon_svg('star') ?> Feedback</span>
            <h3 id="feedbackModalTitle">Add your feedback</h3>
            <p>Takes less than a minute — thank you for helping us improve.</p>
        </div>

        <form id="feedbackForm" novalidate>
            <div class="booking-field">
                <label for="feedbackName"><?= icon_svg('user') ?> Name</label>
                <input type="text" id="feedbackName" name="name" placeholder="Your name" maxlength="80" required>
            </div>

            <div class="booking-field">
                <label><?= icon_svg('star') ?> Rating</label>
                <div class="feedback-stars" id="feedbackStars" role="radiogroup" aria-label="Rating out of 5">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" class="feedback-star" data-value="<?= $i ?>" role="radio" aria-checked="false" aria-label="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"><?= icon_svg('star') ?></button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" id="feedbackRating" name="rating" value="0" required>
            </div>

            <div class="booking-field">
                <label for="feedbackComments"><?= icon_svg('note') ?> Comments</label>
                <textarea id="feedbackComments" name="comments" rows="4" maxlength="1200" placeholder="Tell us about your experience..." required></textarea>
            </div>

            <p class="feedback-form-error" id="feedbackFormError" hidden></p>

            <div class="feedback-modal-foot">
                <button type="submit" class="btn btn-primary" id="feedbackSubmitBtn">Submit Feedback</button>
            </div>
        </form>

        <div class="feedback-success" id="feedbackSuccess" hidden>
            <span class="feedback-success-icon"><?= icon_svg('check') ?></span>
            <h3>Thank you!</h3>
            <p>Your feedback has been received.</p>
            <button type="button" class="btn btn-primary" id="feedbackDoneBtn">Done</button>
        </div>
    </div>
</div>
