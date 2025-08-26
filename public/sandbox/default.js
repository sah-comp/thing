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
function isReadmeVisible() {
	const readme = document.querySelector('.readme');
	if (!readme) return false;
	const rect = readme.getBoundingClientRect();
	return (
		rect.top < window.innerHeight &&
		rect.bottom > 0
	);
}

let animationStarted = false;

function onScrollOrResize() {
	if (!animationStarted && isReadmeVisible()) {
		animationStarted = true;
		playAnimation();
	}
}

window.addEventListener('scroll', onScrollOrResize);
window.addEventListener('resize', onScrollOrResize);
window.addEventListener('DOMContentLoaded', playAnimation);

