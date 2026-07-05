/* =========================================================================
   NDIGITMARKET — Admin frontend (ES6)
   ========================================================================= */
(() => {
  'use strict';
  const BASE_URL = (window.NDIGIT_BASE_URL || '/ndigitmarket').replace(/\/$/, '');

  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  const routeUrl = (action, id) => {
    const path = (id !== undefined && id !== null && id !== '') ? `${action}/${id}` : action;
    return `${BASE_URL}/index.php?route=${path}`;
  };

  const hasSwal = () => typeof window.Swal !== 'undefined';
  const toast = (icon, title) => {
    if (!hasSwal()) { alert(title); return; }
    Swal.fire({
      toast: true, position: 'top-end', icon, title,
      showConfirmButton: false, timer: 2500, timerProgressBar: true
    });
  };
  const confirmDialog = (message, confirmText = 'Confirmer') => {
    if (!hasSwal()) return Promise.resolve(confirm(message));
    return Swal.fire({
      title: 'Confirmation', text: message, icon: 'warning',
      showCancelButton: true, confirmButtonText: confirmText, cancelButtonText: 'Annuler',
      confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b'
    }).then(r => r.isConfirmed);
  };

  const ajax = async (url, options = {}) => {
    const opts = {
      method: options.method || 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      credentials: 'same-origin'
    };
    if (options.body instanceof FormData) {
      opts.body = options.body;
    } else if (options.body) {
      opts.body = new URLSearchParams(options.body).toString();
      opts.headers['Content-Type'] = 'application/x-www-form-urlencoded';
    }
    const res = await fetch(url, opts);
    const text = await res.text();
    let data = null;
    try { data = JSON.parse(text); } catch (_) { data = { success: res.ok, raw: text }; }
    if (!res.ok && !data.message) data.message = `Erreur HTTP ${res.status}`;
    return data;
  };

  /* ---------- 1. Actions boutons via data-action ---------- */
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    e.preventDefault();
    const action  = btn.getAttribute('data-action');
    const id      = btn.getAttribute('data-id');
    const message = btn.getAttribute('data-confirm');
    const success = btn.getAttribute('data-success') || 'Action effectuée';
    const reload  = btn.getAttribute('data-reload') !== 'false';

    if (message) {
      const ok = await confirmDialog(message, btn.getAttribute('data-confirm-text') || 'Confirmer');
      if (!ok) return;
    }
    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    try {
      const data = await ajax(routeUrl(action, id), { method: 'POST' });
      if (data.success) {
        toast('success', data.message || success);
        if (reload) setTimeout(() => window.location.reload(), 700);
      } else {
        toast('error', data.message || 'Une erreur est survenue');
      }
    } catch (err) {
      toast('error', err.message || 'Erreur réseau');
    } finally {
      btn.disabled = false;
      btn.innerHTML = originalHtml;
    }
  });

  /* ---------- 2. Formulaires AJAX ---------- */
  document.addEventListener('submit', async (e) => {
    const form = e.target.closest('form[data-ajax]');
    if (!form) return;
    e.preventDefault();
    const action  = form.getAttribute('data-action') || form.getAttribute('action') || '';
    const success = form.getAttribute('data-success') || 'Enregistré';
    const submit  = form.querySelector('[type="submit"]');
    const originalHtml = submit ? submit.innerHTML : '';
    if (submit) { submit.disabled = true; submit.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }
    try {
      const url  = action.startsWith('http') ? action : (action.startsWith('/') ? action : routeUrl(action));
      const data = await ajax(url, { method: 'POST', body: new FormData(form) });
      if (data.success) {
        toast('success', data.message || success);
        form.reset();
        // CORRECTION : fermer la modale parente (par classe CSS)
        const modal = form.closest('.fixed.inset-0');
        if (modal) {
          modal.classList.add('hidden');
          modal.classList.remove('flex');
          document.body.style.overflow = '';
        }
        setTimeout(() => window.location.reload(), 700);
      } else {
        toast('error', data.message || 'Erreur');
      }
    } catch (err) {
      toast('error', err.message || 'Erreur réseau');
    } finally {
      if (submit) { submit.disabled = false; submit.innerHTML = originalHtml; }
    }
  });

  /* ---------- 3. Tri de colonnes ---------- */
  const params = new URLSearchParams(window.location.search);
  const currentSort  = params.get('sort');
  const currentOrder = (params.get('order') || 'DESC').toUpperCase();
  $$('[data-sort-col]').forEach(th => {
    th.classList.add('cursor-pointer', 'select-none');
    const col = th.getAttribute('data-sort-col');
    if (col === currentSort) {
      const icon = th.querySelector('i');
      if (icon) icon.className = currentOrder === 'ASC' ? 'fas fa-sort-up ml-1' : 'fas fa-sort-down ml-1';
    }
    th.addEventListener('click', () => {
      const next = new URLSearchParams(window.location.search);
      const isSame = next.get('sort') === col;
      next.set('sort', col);
      next.set('order', isSame && next.get('order') === 'ASC' ? 'DESC' : 'ASC');
      window.location.search = next.toString();
    });
  });

  /* ---------- 4. Recherche avec debounce ---------- */
  const debounce = (fn, delay = 400) => {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
  };
  $$('[data-search-input]').forEach(input => {
    const paramName = input.getAttribute('data-search-input') || 'search';
    input.addEventListener('input', debounce(() => {
      const p = new URLSearchParams(window.location.search);
      const v = input.value.trim();
      if (v) p.set(paramName, v); else p.delete(paramName);
      window.location.search = p.toString();
    }));
  });

  /* ---------- 5. Filtres : auto-submit ---------- */
  $$('form[data-filter]').forEach(form => {
    form.addEventListener('change', () => form.submit());
  });

  /* ---------- 6. Bulk actions ---------- */
  const masterCbx = $('[data-bulk-master]');
  const rowCbxs   = $$('[data-bulk-checkbox]');
  const bulkBar   = $('[data-bulk-bar]');
  const bulkCount = $('[data-bulk-count]');
  const refreshBulk = () => {
    const checked = rowCbxs.filter(c => c.checked);
    if (bulkBar) bulkBar.classList.toggle('hidden', checked.length === 0);
    if (bulkCount) bulkCount.textContent = checked.length;
  };
  if (masterCbx) {
    masterCbx.addEventListener('change', () => {
      rowCbxs.forEach(c => c.checked = masterCbx.checked); refreshBulk();
    });
  }
  rowCbxs.forEach(c => c.addEventListener('change', refreshBulk));
  $$('[data-bulk-action]').forEach(btn => {
    btn.addEventListener('click', async () => {
      const ids = rowCbxs.filter(c => c.checked).map(c => c.value);
      if (!ids.length) return toast('warning', 'Aucun élément sélectionné');
      const action  = btn.getAttribute('data-bulk-action');
      const message = btn.getAttribute('data-confirm') || `Appliquer à ${ids.length} élément(s) ?`;
      if (!await confirmDialog(message)) return;
      const fd = new FormData();
      ids.forEach(id => fd.append('ids[]', id));
      const data = await ajax(routeUrl(action), { method: 'POST', body: fd });
      toast(data.success ? 'success' : 'error', data.message || (data.success ? 'OK' : 'Erreur'));
      if (data.success) setTimeout(() => window.location.reload(), 700);
    });
  });

  /* ---------- 7. Modales génériques ---------- */
  $$('[data-open-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      const modalId = btn.getAttribute('data-open-modal');
      const modal = document.getElementById(modalId);
      if (!modal) return;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      document.body.style.overflow = 'hidden';

      // Pré-remplissage optionnel (data-edit avec JSON)
      const payload = btn.getAttribute('data-edit');
      if (payload) {
        try {
          const obj = JSON.parse(payload);
          Object.entries(obj).forEach(([k, v]) => {
            const field = modal.querySelector(`[name="${k}"]`);
            if (field) field.value = v ?? '';
          });
        } catch (_) {}
      }
    });
  });

  $$('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const modal = btn.closest('.fixed.inset-0');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
      }
    });
  });

  $$('.fixed.inset-0').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
      }
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      $$('.fixed.inset-0:not(.hidden)').forEach(modal => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      });
      document.body.style.overflow = '';
    }
  });

  /* ---------- 8. Pagination client-side ---------- */
  $$('[data-paginate]').forEach(container => {
    const size = parseInt(container.getAttribute('data-page-size') || '10', 10);
    const rows = $$('tbody tr', container);
    if (rows.length <= size) return;
    let current = 1;
    const totalPages = Math.ceil(rows.length / size);
    const nav = document.createElement('div');
    nav.className = 'flex items-center justify-center gap-2 py-3 text-xs';
    container.appendChild(nav);
    const render = () => {
      rows.forEach((r, i) => {
        r.style.display = (i >= (current - 1) * size && i < current * size) ? '' : 'none';
      });
      nav.innerHTML = '';
      for (let p = 1; p <= totalPages; p++) {
        const b = document.createElement('button');
        b.textContent = p;
        b.className = 'px-3 py-1 rounded-lg border ' +
          (p === current ? 'bg-[#0EA486] text-white border-[#0EA486]' : 'bg-white border-gray-200');
        b.addEventListener('click', () => { current = p; render(); });
        nav.appendChild(b);
      }
    };
    render();
  });

  window.NDigitAdmin = { ajax, routeUrl, toast, confirmDialog };
})();