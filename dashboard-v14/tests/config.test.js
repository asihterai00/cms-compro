import test from 'node:test';
import assert from 'node:assert/strict';
import {navigation,modules,can,kpis,revenue,productStats,initialData,languages,routeFor,parseRoute,chartData} from '../src/config.js';

test('navigation labels aligned with public site', () => {
  assert.deepEqual(
    navigation.map(n => n.label),
    ['Dashboard', 'Beranda', 'Tentang Kami', 'Produk', 'Mutu & ISO', 'Fasilitas', 'Galeri', 'Berita', 'Karir', 'B2B RFQ', 'Pengaturan']
  );
});

test('superadmin reads every module', () => Object.keys(modules).forEach(k => assert.equal(can('superadmin', k), true)));

test('staff restrictions', () => {
  assert.equal(can('staff', 'users'), false);
  assert.equal(can('staff', 'products', 'delete'), false);
  assert.equal(can('staff', 'messages', 'update'), true);
});

test('exact KPI fixtures', () => assert.deepEqual(kpis.map(k => k.value), ['$24,580', '1,245', '892']));

test('exact monthly series', () => assert.deepEqual(revenue.map(r => r.value), [12000, 16000, 14000, 24000, 18000, 27000]));

test('four featured product pillars', () => {
  assert.equal(productStats.length, 4);
  assert.deepEqual(initialData.products.map(p => p.name), ['Steering Wheel', 'Airbag Module', 'Interior Parts', 'Resin Parts']);
});

test('product edit route roundtrip', () => assert.deepEqual(parseRoute(routeFor('products', 'products', 2)), { menu: 'products', module: 'products', id: '2' }));

test('every module route roundtrips', () => Object.keys(modules).forEach(k => assert.equal(parseRoute(routeFor(null, k)).module, k)));

test('supported languages', () => assert.deepEqual(languages.map(l => l.id), ['id', 'en', 'ja', 'zh', 'ko']));

test('alternate chart aggregations', () => {
  assert.equal(chartData('This Quarter').reduce((s, r) => s + r.value, 0), 111000);
  assert.equal(chartData('This Year')[0].value, 111000);
  assert.equal(chartData('This Week').reduce((s, r) => s + r.value, 0), 24580);
});
