import './bootstrap';

import Alpine from 'alpinejs';

// Theme persistence and toggle
(function applyStoredTheme() {
	const stored = localStorage.getItem('theme');
	const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
	if (stored === 'dark' || (!stored && prefersDark)) {
		document.documentElement.classList.add('dark');
	} else {
		document.documentElement.classList.remove('dark');
	}
})();

window.toggleTheme = function toggleTheme() {
	const isDark = document.documentElement.classList.toggle('dark');
	localStorage.setItem('theme', isDark ? 'dark' : 'light');
};

window.Alpine = Alpine;

Alpine.start();
