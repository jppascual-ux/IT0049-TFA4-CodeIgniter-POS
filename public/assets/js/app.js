/*
 * IT0049 POS — interface behaviour.
 * Progressive enhancement only: every page and form still works without JavaScript,
 * and all validation is still done by CodeIgniter on the server.
 */
(function () {
    'use strict';

    var doc = document;
    var body = doc.body;
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Navigation: frosted border on scroll ---------- */
    var nav = doc.querySelector('.nav');
    if (nav) {
        var onScroll = function () {
            nav.classList.toggle('is-scrolled', window.scrollY > 8);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* ---------- Mobile menu ---------- */
    var toggle = doc.querySelector('[data-menu-toggle]');
    if (toggle) {
        var setMenu = function (open) {
            body.classList.toggle('menu-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        };
        toggle.addEventListener('click', function () {
            setMenu(!body.classList.contains('menu-open'));
        });
        doc.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { setMenu(false); }
        });
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 835) { setMenu(false); }
        });
    }

    /* ---------- Scroll reveal + illustration playback ---------- */
    var watched = doc.querySelectorAll('[data-reveal], [data-animate]');
    if ('IntersectionObserver' in window && !reduceMotion) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.18, rootMargin: '0px 0px -40px 0px' });
        watched.forEach(function (el) { io.observe(el); });
    } else {
        watched.forEach(function (el) { el.classList.add('is-in'); });
    }

    /* ---------- Toast ("island") ---------- */
    var island = doc.querySelector('[data-island]');
    if (island) {
        var dismiss = function () {
            if (island.classList.contains('is-out')) { return; }
            island.classList.add('is-out');
            setTimeout(function () { island.remove(); }, 550);
        };
        island.addEventListener('click', dismiss);
        setTimeout(dismiss, 4200);
    }

    /* ---------- Show / hide password ---------- */
    doc.querySelectorAll('[data-reveal-password]').forEach(function (btn) {
        var input = doc.getElementById(btn.getAttribute('data-reveal-password'));
        if (!input) { return; }
        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            input.focus();
        });
    });

    /* ---------- Fill demo account on the login page ---------- */
    var demo = doc.querySelector('[data-demo-fill]');
    if (demo) {
        demo.addEventListener('click', function () {
            var u = doc.getElementById('username');
            var p = doc.getElementById('password');
            if (u && p) {
                u.value = demo.getAttribute('data-user');
                p.value = demo.getAttribute('data-pass');
                p.focus();
            }
        });
    }

    /* ---------- Loading state on submit (prevents double submits) ---------- */
    doc.querySelectorAll('form[data-loading]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (btn) {
                setTimeout(function () { btn.classList.add('is-loading'); }, 0);
            }
        });
    });

    /* ---------- Live search on list pages ---------- */
    doc.querySelectorAll('[data-search]').forEach(function (input) {
        var table = doc.querySelector(input.getAttribute('data-search'));
        if (!table) { return; }
        var rows = table.querySelectorAll('tbody tr[data-text]');
        var empty = doc.querySelector(input.getAttribute('data-empty'));
        var count = doc.querySelector(input.getAttribute('data-count'));
        var total = rows.length;

        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase();
            var shown = 0;
            rows.forEach(function (row) {
                var hit = q === '' || row.getAttribute('data-text').indexOf(q) !== -1;
                row.hidden = !hit;
                if (hit) { shown++; }
            });
            if (empty) {
                empty.hidden = shown !== 0;
                var term = empty.querySelector('[data-term]');
                if (term) { term.textContent = input.value.trim(); }
            }
            if (count) {
                count.textContent = q === '' ? count.getAttribute('data-default') : shown + ' of ' + total + ' shown';
            }
        });
    });

    /* ---------- Avatar picker: preview, drag & drop, early warning ---------- */
    var fileInput = doc.querySelector('[data-avatar-input]');
    if (fileInput) {
        var zone = doc.querySelector('[data-avatar-zone]');
        var preview = doc.querySelector('[data-avatar-preview]');
        var nameEl = doc.querySelector('[data-avatar-name]');
        var note = doc.querySelector('[data-avatar-note]');
        var maxBytes = 2 * 1024 * 1024;
        var allowed = ['image/jpeg', 'image/png'];

        var handle = function () {
            var file = fileInput.files && fileInput.files[0];
            note.textContent = '';
            zone.classList.remove('is-invalid');
            nameEl.textContent = file ? file.name : '';
            if (!file) { return; }

            // Early warning only; the server still validates and is the final word.
            if (allowed.indexOf(file.type) === -1) {
                note.textContent = 'This file will be rejected: choose a JPG or PNG image.';
                zone.classList.add('is-invalid');
                return;
            }
            if (file.size > maxBytes) {
                note.textContent = 'This file will be rejected: it is larger than 2 MB.';
                zone.classList.add('is-invalid');
                return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('is-swapped');
                void preview.offsetWidth;
                preview.classList.add('is-swapped');
            };
            reader.readAsDataURL(file);
        };

        fileInput.addEventListener('change', handle);

        ['dragenter', 'dragover'].forEach(function (type) {
            zone.addEventListener(type, function (e) {
                e.preventDefault();
                zone.classList.add('is-over');
            });
        });
        ['dragleave', 'drop'].forEach(function (type) {
            zone.addEventListener(type, function (e) {
                e.preventDefault();
                zone.classList.remove('is-over');
            });
        });
        zone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handle();
            }
        });
    }
})();
