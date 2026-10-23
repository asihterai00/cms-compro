import React, { useState, useEffect, useCallback } from 'react';
import { modules, navigation, can, labelOf, routeFor, languages, roles, translatableFields, moduleEntityMap } from '../config';
import { Icon, IconButton, Modal, Empty } from './UI';
import { DateRangeSelector } from './Dashboard';

const fieldLabel = s => s.replaceAll('_', ' ').replace(/^./, c => c.toUpperCase());
const sortableModules = ['products', 'site_sections', 'site_section_items', 'certifications', 'facility_machinery', 'gallery', 'news', 'careers'];
const moduleTableMap = {
  products: 'products',
  site_sections: 'site_sections',
  site_section_items: 'site_section_items',
  certifications: 'certifications',
  facility_machinery: 'facility_machinery',
  gallery: 'gallery',
  news: 'news',
  careers: 'careers'
};

function RecordEditor({ module, record, role, onClose, onSave }) {
  const definition = modules[module];
  const isSortable = sortableModules.includes(module);

  // Guarantee sort_order is always initialized with a number, never empty
  const [draft, setDraft] = useState(() => ({
    ...record,
    ...(isSortable ? { sort_order: (record.sort_order !== undefined && record.sort_order !== null && record.sort_order !== '') ? Number(record.sort_order) : 10 } : {})
  }));

  const [activeLangTab, setActiveLangTab] = useState('id');
  const [error, setError] = useState('');
  const action = record.id ? 'update' : 'create';
  const editable = can(role, module, action);

  const entityType = moduleEntityMap[module];
  const transFields = translatableFields[module] || [];
  const nonTransFields = definition.fields.filter(f => !transFields.includes(f));

  // Initialize translations from window.__CMS_ALL_TRANSLATIONS__
  const [translations, setTranslations] = useState(() => {
    const initial = { en: {}, ja: {}, zh: {}, ko: {} };
    if (record.id && typeof window !== 'undefined' && window.__CMS_ALL_TRANSLATIONS__?.[entityType]?.[record.id]) {
      const existing = window.__CMS_ALL_TRANSLATIONS__[entityType][record.id];
      ['en', 'ja', 'zh', 'ko'].forEach(lang => {
        initial[lang] = { ...(existing[lang] || {}) };
      });
    }
    return initial;
  });

  const choices = {
    role: roles,
    status: module === 'messages' ? ['unread', 'read'] : module === 'certifications' ? ['active', 'archived'] : ['active', 'inactive'],
    lang_code: languages.filter(l => !l.default).map(l => l.id)
  };

  function updateTranslation(lang, field, value) {
    setTranslations(prev => ({
      ...prev,
      [lang]: {
        ...(prev[lang] || {}),
        [field]: value
      }
    }));
  }

  function submit(e) {
    e.preventDefault();
    if (!editable) return;
    const tonnage = draft.tonnage ? Number(String(draft.tonnage).replace(/T$/i, '')) : null;
    if (module === 'facility_machinery' && (!tonnage || tonnage < 150 || tonnage > 2500)) {
      setError('Tonnage must be between 150T and 2500T.');
      return;
    }
    const finalRecord = {
      ...draft,
      ...(isSortable ? { sort_order: Number(draft.sort_order ?? 10) } : {}),
      ...(module === 'facility_machinery' ? { tonnage: tonnage + 'T' } : {})
    };
    onSave(module, finalRecord, translations);
  }

  function renderField(field, isTranslationMode = false, currentLang = 'id') {
    const value = isTranslationMode
      ? (translations[currentLang]?.[field] ?? '')
      : (draft[field] ?? (field === 'sort_order' ? 10 : ''));

    const baseVal = draft[field] ?? '';
    const multiline = ['description', 'content', 'message', 'about_text', 'vision', 'mission', 'values_text', 'translation_value'].includes(field);
    const boolean = ['is_active'].includes(field);

    return (
      <label key={field + (isTranslationMode ? '-' + currentLang : '')} style={{ display: 'flex', flexDirection: 'column', gap: '4px', marginBottom: '12px' }}>
        <span style={{ fontSize: '0.8rem', fontWeight: '600', color: 'var(--text-heading, #0F172A)' }}>
          {fieldLabel(field)} {isTranslationMode && <span style={{ color: '#005A36', fontSize: '0.72rem' }}>({currentLang.toUpperCase()})</span>}
        </span>

        {field === 'sort_order' ? (
          <input
            readOnly={!editable}
            type="number"
            step="5"
            min="1"
            value={value}
            onChange={e => setDraft({ ...draft, sort_order: Number(e.target.value) || 10 })}
          />
        ) : boolean ? (
          <select disabled={!editable} value={String(value === true || value === 1 || value === '1')} onChange={e => setDraft({ ...draft, [field]: e.target.value === 'true' })}>
            <option value="true">Active (Aktif)</option>
            <option value="false">Inactive (Non-aktif)</option>
          </select>
        ) : choices[field] ? (
          <select disabled={!editable} value={value} required onChange={e => setDraft({ ...draft, [field]: e.target.value })}>
            <option value="" disabled>Pilih {fieldLabel(field).toLowerCase()}</option>
            {choices[field].map(v => <option key={v} value={v}>{v}</option>)}
          </select>
        ) : multiline ? (
          <textarea
            rows={4}
            readOnly={!editable}
            placeholder={isTranslationMode ? (baseVal ? `Terjemahan untuk: "${baseVal.slice(0, 50)}..."` : `Terjemahan ${currentLang.toUpperCase()}`) : ''}
            value={value}
            onChange={e => isTranslationMode ? updateTranslation(currentLang, field, e.target.value) : setDraft({ ...draft, [field]: e.target.value })}
          />
        ) : (
          <input
            readOnly={!editable}
            type={field === 'email' ? 'email' : field === 'valid_until' || field === 'published_at' ? 'date' : field === 'created_at' ? 'datetime-local' : 'text'}
            required={!['image', 'certificate_number', 'issued_by', 'subtitle', 'badge_text', 'badge', 'button_text', 'button_link', 'icon', 'bg_image', 'application', 'automation', 'tagline', 'location'].includes(field)}
            placeholder={isTranslationMode ? (baseVal ? `Terjemahan: ${baseVal}` : `Terjemahan ${currentLang.toUpperCase()}`) : ''}
            value={value}
            onChange={e => isTranslationMode ? updateTranslation(currentLang, field, e.target.value) : setDraft({ ...draft, [field]: e.target.value })}
          />
        )}
      </label>
    );
  }

  return (
    <Modal title={(record.id ? (editable ? 'Edit ' : 'Lihat ') : 'Tambah ') + definition.label} onClose={onClose}>
      <form onSubmit={submit} className="record-form">
        {transFields.length > 0 && (
          <div style={{ marginBottom: '16px' }}>
            <div style={{ fontSize: '0.75rem', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '0.04em', color: 'var(--text-muted, #64748B)', marginBottom: '8px' }}>
              Pusat Konten Multibahasa (5 Bahasa)
            </div>
            <div style={{ display: 'flex', gap: '6px', flexWrap: 'wrap', borderBottom: '1px solid var(--border-color, #EAEFF5)', paddingBottom: '10px' }}>
              {languages.map(l => (
                <button
                  type="button"
                  key={l.id}
                  onClick={() => setActiveLangTab(l.id)}
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '6px',
                    padding: '6px 12px',
                    borderRadius: '8px',
                    border: '1px solid ' + (activeLangTab === l.id ? '#005A36' : 'var(--border-color, #EAEFF5)'),
                    background: activeLangTab === l.id ? '#ECFDF5' : 'var(--surface, #FFFFFF)',
                    color: activeLangTab === l.id ? '#005A36' : 'var(--text-heading, #0F172A)',
                    fontWeight: activeLangTab === l.id ? '700' : '500',
                    cursor: 'pointer',
                    fontSize: '0.82rem'
                  }}
                >
                  <img src={l.flag} alt={l.short} style={{ width: '16px', height: '11px', objectFit: 'cover', borderRadius: '2px' }} />
                  <span>{l.short} - {l.name} {l.id === 'id' ? '(Utama)' : ''}</span>
                </button>
              ))}
            </div>

            <div style={{ paddingTop: '12px' }}>
              {activeLangTab === 'id' ? (
                <>
                  <p style={{ fontSize: '0.78rem', color: 'var(--text-muted, #64748B)', marginBottom: '10px' }}>
                    Mengedit data utama (Bahasa Indonesia). Data ini juga menjadi acuan untuk bahasa lainnya.
                  </p>
                  {transFields.map(f => renderField(f, false, 'id'))}
                </>
              ) : (
                <>
                  <p style={{ fontSize: '0.78rem', color: '#005A36', background: '#ECFDF5', padding: '6px 10px', borderRadius: '6px', marginBottom: '12px' }}>
                    Mengedit terjemahan <strong>{languages.find(l => l.id === activeLangTab)?.name} ({activeLangTab.toUpperCase()})</strong>. Kosongkan jika ingin menggunakan teks Bahasa Indonesia sebagai fallback.
                  </p>
                  {transFields.map(f => renderField(f, true, activeLangTab))}
                </>
              )}
            </div>
          </div>
        )}

        {nonTransFields.length > 0 && (
          <div style={{ marginTop: transFields.length > 0 ? '16px' : '0', paddingTop: transFields.length > 0 ? '16px' : '0', borderTop: transFields.length > 0 ? '1px dashed var(--border-color, #EAEFF5)' : 'none' }}>
            {transFields.length > 0 && (
              <div style={{ fontSize: '0.75rem', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '0.04em', color: 'var(--text-muted, #64748B)', marginBottom: '10px' }}>
                Pengaturan Umum & Posisi Urutan
              </div>
            )}
            {nonTransFields.map(f => renderField(f, false, 'id'))}
          </div>
        )}

        {error && <p className="form-error" role="alert">{error}</p>}
        <div className="form-actions">
          <button type="button" className="secondary-button" onClick={onClose}>{editable ? 'Batal' : 'Tutup'}</button>
          {editable && (
            <button type="submit" className="primary-button">
              <Icon name="Check" size={16} />Simpan Perubahan
            </button>
          )}
        </div>
      </form>
    </Modal>
  );
}

export default function ModuleView({ route, data, role, save, remove, range, setRange }) {
  const menu = navigation.find(n => n.key === route.menu);
  const module = route.module || menu.modules[0];
  const definition = modules[module];
  const [filter, setFilter] = useState('');
  const [creating, setCreating] = useState(false);
  const [deleting, setDeleting] = useState(null);

  useEffect(() => {
    setFilter('');
    setCreating(false);
    setDeleting(null);
  }, [module]);

  const closeEditor = useCallback(() => {
    setCreating(false);
    location.hash = routeFor(route.menu, module);
  }, [route.menu, module]);

  const closeDelete = useCallback(() => setDeleting(null), []);

  if (!can(role, route.menu) || !can(role, module)) {
    return <Empty title="403 · Access denied" description="Your role does not have permission to read this module." icon="ShieldAlert" />;
  }

  const isSortable = sortableModules.includes(module);
  const rawRows = data[module] || [];
  const rows = isSortable ? rawRows.slice().sort((a, b) => ((a.sort_order ?? 10) - (b.sort_order ?? 10) || (a.id - b.id))) : rawRows;
  const shown = rows.filter(row => Object.values(row).join(' ').toLowerCase().includes(filter.toLowerCase())).filter(row => module !== 'messages' || (row.created_at?.slice(0, 10) >= range.start && row.created_at?.slice(0, 10) <= range.end));
  const record = route.id ? rows.find(r => String(r.id) === route.id) : null;

  function reorderItem(row, direction) {
    const table = moduleTableMap[module];
    if (!table) return;
    const idx = rows.findIndex(r => r.id === row.id);
    const targetIdx = direction === 'up' ? idx - 1 : idx + 1;
    if (targetIdx < 0 || targetIdx >= rows.length) return;
    const targetRow = rows[targetIdx];
    const curOrder = row.sort_order ?? ((idx + 1) * 10);
    const tgtOrder = targetRow.sort_order ?? ((targetIdx + 1) * 10);

    save(module, { ...row, sort_order: tgtOrder });
    save(module, { ...targetRow, sort_order: curOrder });

    const csrfToken = (typeof window !== 'undefined' && window.__CSRF_TOKEN__) || '';
    fetch('ajax_reorder.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams({
        action: direction === 'up' ? 'move_up' : 'move_down',
        table: table,
        id: String(row.id),
        csrf_token: csrfToken,
        ajax: '1'
      })
    }).catch(() => {});
  }

  return (
    <>
      <div className="page-header">
        <div>
          <h1>{menu.label}</h1>
          <p>{menu.context} / {definition.label}</p>
        </div>
        {module === 'messages' ? (
          <DateRangeSelector range={range} setRange={setRange} />
        ) : can(role, module, 'create') && (
          <button className="primary-button" onClick={() => setCreating(true)}>
            <Icon name="Plus" size={17} />Tambah Baru
          </button>
        )}
      </div>

      {menu.modules.length > 1 && (
        <div className="module-tabs" aria-label={menu.context}>
          {menu.modules.filter(k => can(role, k)).map(k => (
            <a key={k} className={k === module ? 'active' : ''} href={routeFor(route.menu, k)}>
              {modules[k].label}
            </a>
          ))}
        </div>
      )}

      <section className="card module-card">
        <div className="module-toolbar">
          <div>
            <h2>{definition.label}</h2>
            <p>{shown.length} {shown.length === 1 ? 'record' : 'records'}</p>
          </div>
          <div className="list-search">
            <Icon name="Search" size={16} />
            <input aria-label="Filter records" placeholder="Cari data..." value={filter} onChange={e => setFilter(e.target.value)} />
          </div>
        </div>

        {shown.length ? (
          <div className="records">
            {shown.map(row => (
              <div className="record-row" key={row.id}>
                <span className="record-icon"><Icon name={definition.icon} size={20} /></span>
                <div className="record-info">
                  <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                    {row.sort_order !== undefined && (
                      <span title="Urutan Posisi (Sort Order)" style={{ display: 'inline-flex', alignItems: 'center', fontSize: '0.72rem', fontWeight: '700', color: '#005A36', background: '#ECFDF5', padding: '2px 7px', borderRadius: '6px', border: '1px solid #A7F3D0' }}>
                        #{row.sort_order}
                      </span>
                    )}
                    <a href={routeFor(route.menu, module, row.id)}>{labelOf(row)}</a>
                  </div>
                  <p>{module === 'messages' ? row.message : row.description || row.department || row.category || row.content || row.subtitle || row.email || row.path || row.lang_code}</p>
                </div>
                {row.status && <span className={'status ' + (row.status === 'unread' ? 'unread' : '')}>{row.status}</span>}
                <div className="record-actions">
                  {isSortable && can(role, module, 'update') && (
                    <>
                      <IconButton label="Geser Naik (Move Up)" onClick={() => reorderItem(row, 'up')}>
                        <Icon name="ArrowUp" size={15} />
                      </IconButton>
                      <IconButton label="Geser Turun (Move Down)" onClick={() => reorderItem(row, 'down')}>
                        <Icon name="ArrowDown" size={15} />
                      </IconButton>
                    </>
                  )}
                  <IconButton label={(can(role, module, 'update') ? 'Edit ' : 'Lihat ') + labelOf(row)} onClick={() => { location.hash = routeFor(route.menu, module, row.id); }}>
                    <Icon name={can(role, module, 'update') ? 'Pencil' : 'Eye'} size={16} />
                  </IconButton>
                  {module === 'messages' && can(role, module, 'update') && row.status === 'unread' && (
                    <IconButton label="Tandai sudah dibaca" onClick={() => save(module, { ...row, status: 'read' })}>
                      <Icon name="MailCheck" size={17} />
                    </IconButton>
                  )}
                  {can(role, module, 'delete') && (
                    <IconButton label={'Hapus ' + labelOf(row)} onClick={() => setDeleting(row)}>
                      <Icon name="Trash2" size={16} />
                    </IconButton>
                  )}
                </div>
              </div>
            ))}
          </div>
        ) : (
          <Empty title="Tidak ada data ditemukan" description="Coba kata kunci pencarian atau rentang tanggal lain." />
        )}
      </section>

      {(record || creating) && (
        <RecordEditor
          key={module + '-' + (record?.id || 'new')}
          module={module}
          record={record || {}}
          role={role}
          onClose={closeEditor}
          onSave={(key, draft, trans) => {
            save(key, draft, trans);
            closeEditor();
          }}
        />
      )}

      {route.id && !record && (
        <Empty title="Record tidak ditemukan" description="Data ini mungkin telah dihapus." />
      )}

      {deleting && (
        <Modal
          title="Hapus data ini?"
          onClose={closeDelete}
          footer={
            <>
              <button className="secondary-button" onClick={closeDelete}>Batal</button>
              <button className="danger-button" onClick={() => { remove(module, deleting.id); setDeleting(null); }}>Hapus Permanen</button>
            </>
          }
        >
          <div className="confirm-content">
            <span className="delete-icon"><Icon name="Trash2" size={24} /></span>
            <p><strong>{labelOf(deleting)}</strong> akan dihapus secara permanen dari database. Tindakan ini tidak dapat dibatalkan.</p>
          </div>
        </Modal>
      )}
    </>
  );
}
