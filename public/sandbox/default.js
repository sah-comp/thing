// Base duration per character (in seconds)
const baseDuration = 0.104; // 
// Minimum duration for very short words
const minDuration = 0.3; //
// Emphasized words stay highlighted longer
const emphasizedFactor = 0.8;

const spans = document.querySelectorAll('.readme span');

function playAnimation() {
	let delay = 0;
	spans.forEach(span => {
		span.style.animation = 'none';
	});
	// Force reflow to restart animation
	void document.body.offsetWidth;
	spans.forEach(span => {
		const wordLength = span.textContent.length;
		let duration = Math.max(minDuration, wordLength * baseDuration);

		if (span.classList.contains('emphasized')) {
			duration *= emphasizedFactor;
		}

		span.style.animation = '';
		span.style.animationDuration = duration + 's';
		span.style.animationDelay = delay + 's';

		delay += duration;
	});
}

window.addEventListener('DOMContentLoaded', playAnimation);

