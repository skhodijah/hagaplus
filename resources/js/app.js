import './bootstrap';

import Alpine from 'alpinejs';

// Theme persistence and initial apply
(function applyStoredTheme() {
	const stored = localStorage.getItem('theme');
	const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
	if (stored === 'dark' || (!stored && prefersDark)) {
		document.documentElement.classList.add('dark');
	} else {
		document.documentElement.classList.remove('dark');
	}
})();

function bindThemeToggle() {
	const toggles = document.querySelectorAll('[data-theme-toggle]');
	toggles.forEach(btn => {
		btn.addEventListener('click', () => {
			const isDark = document.documentElement.classList.toggle('dark');
			localStorage.setItem('theme', isDark ? 'dark' : 'light');
		});
	});
}

function bindNotifications() {
	const btn = document.querySelector('[data-notification-btn]');
	const panel = document.querySelector('[data-notification-panel]');
	if (!btn || !panel) return;
	btn.addEventListener('click', (e) => {
		e.stopPropagation();
		panel.classList.toggle('hidden');
	});
	document.addEventListener('click', (e) => {
		if (!panel.contains(e.target) && !btn.contains(e.target)) {
			panel.classList.add('hidden');
		}
	});
}

function bindAccordion() {
	const toggles = document.querySelectorAll('.accordion-toggle');
	toggles.forEach(toggle => {
		toggle.addEventListener('click', () => {
			const targetId = toggle.getAttribute('data-target');
			const content = document.getElementById(targetId);
			const chevron = toggle.querySelector('.accordion-chevron');
			const isOpen = content && !content.classList.contains('hidden');

			// close others, but keep open if they contain an active child
			toggles.forEach(other => {
				const otherId = other.getAttribute('data-target');
				if (!otherId) return;
				const otherContent = document.getElementById(otherId);
				const otherChevron = other.querySelector('.accordion-chevron');
				if (otherId !== targetId && otherContent) {
					const hasActiveChild = !!otherContent.querySelector('.bg-haga-1.text-white');
					if (!hasActiveChild) {
						otherContent.classList.add('hidden');
						if (otherChevron) otherChevron.style.transform = 'rotate(0deg)';
						other.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-haga-1');
					} else {
						otherContent.classList.remove('hidden');
						if (otherChevron) otherChevron.style.transform = 'rotate(180deg)';
						other.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-haga-1');
					}
				}
			});

			if (!content) return;
			if (isOpen) {
				// Only close if it does not have an active child
				const hasActiveChild = !!content.querySelector('.bg-haga-1.text-white');
				if (!hasActiveChild) {
					content.classList.add('hidden');
					if (chevron) chevron.style.transform = 'rotate(0deg)';
					toggle.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-haga-1');
				}
			} else {
				content.classList.remove('hidden');
				if (chevron) chevron.style.transform = 'rotate(180deg)';
				toggle.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-haga-1');
			}
		});
	});

	const subItems = document.querySelectorAll('.accordion-content a');
	subItems.forEach(item => {
		item.addEventListener('click', () => {
			subItems.forEach(other => {
				other.classList.remove('bg-haga-1', 'text-white', 'dark:bg-haga-1');
				other.classList.add('text-gray-600', 'dark:text-gray-400');
			});
			item.classList.add('bg-haga-1', 'text-white', 'dark:bg-haga-1');
			item.classList.remove('text-gray-600', 'dark:text-gray-400');
			// Ensure its parent accordion remains open
			const content = item.closest('.accordion-content');
			const toggle = document.querySelector(`[data-target="${content.id}"]`);
			const chevron = toggle ? toggle.querySelector('.accordion-chevron') : null;
			if (content) content.classList.remove('hidden');
			if (toggle) toggle.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-haga-1');
			if (chevron) chevron.style.transform = 'rotate(180deg)';
		});
	});
}

function bindSidebarToggle() {
	const sidebar = document.getElementById('sidebar');
	const overlay = document.getElementById('sidebar-overlay');
	const openBtn = document.getElementById('sidebar-toggle');
	const closeBtn = document.getElementById('sidebar-close');
	if (!sidebar || !overlay) return;
	const open = () => { sidebar.classList.remove('sidebar-closed'); overlay.classList.remove('hidden'); };
	const close = () => { sidebar.classList.add('sidebar-closed'); overlay.classList.add('hidden'); };
	openBtn && openBtn.addEventListener('click', open);
	closeBtn && closeBtn.addEventListener('click', close);
	overlay.addEventListener('click', close);
	// close on LG+ reset
	const mq = window.matchMedia('(min-width: 1024px)');
	const handle = (e) => { if (e.matches) { overlay.classList.add('hidden'); sidebar.classList.remove('sidebar-closed'); } else { sidebar.classList.add('sidebar-closed'); } };
	handle(mq);
	mq.addEventListener ? mq.addEventListener('change', handle) : mq.addListener(handle);
}

function bindLogout() {
	const trigger = document.querySelector('[data-logout]');
	const form = document.getElementById('logout-form');
	if (trigger && form) {
		trigger.addEventListener('click', (e) => {
			e.preventDefault();
			form.submit();
		});
	}
}

window.addEventListener('DOMContentLoaded', () => {
	bindThemeToggle();
	bindNotifications();
	bindAccordion();
	bindSidebarToggle();
	bindLogout();
});

window.Alpine = Alpine;
Alpine.start();
