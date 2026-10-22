import React,{useState,useRef,useEffect,useId} from 'react';
import {AreaChart,Area,ResponsiveContainer,XAxis,YAxis,CartesianGrid,Tooltip} from 'recharts';
import {kpis,productStats,periods,chartData,routeFor,can} from '../config';
import {Icon,Popover} from './UI';

const formatDate = value => new Date(value + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

export function DateRangeSelector({range, setRange}) {
  const [open, setOpen] = useState(false);
  const [draft, setDraft] = useState(range);
  const ref = useRef(null);

  useEffect(() => {
    const fn = e => {
      if (!ref.current?.contains(e.target) || e.key === 'Escape') setOpen(false);
    };
    document.addEventListener('pointerdown', fn);
    document.addEventListener('keydown', fn);
    return () => {
      document.removeEventListener('pointerdown', fn);
      document.removeEventListener('keydown', fn);
    };
  }, []);

  return (
    <div className="date-wrap" ref={ref}>
      <button className="date-selector" aria-expanded={open} onClick={() => { setDraft(range); setOpen(!open); }}>
        <Icon name="CalendarDays" size={17} />
        <span>{formatDate(range.start)} - {formatDate(range.end)}</span>
        <Icon name="ChevronDown" size={14} />
      </button>
      {open && (
        <Popover className="date-popover">
          <strong>Select date range</strong>
          <form onSubmit={e => { e.preventDefault(); if (draft.start <= draft.end) { setRange(draft); setOpen(false); } }}>
            <label>Start date
              <input required type="date" value={draft.start} max={draft.end} onChange={e => setDraft({ ...draft, start: e.target.value })} />
            </label>
            <label>End date
              <input required type="date" value={draft.end} min={draft.start} onChange={e => setDraft({ ...draft, end: e.target.value })} />
            </label>
            <div className="date-presets">
              <button type="button" onClick={() => setDraft({ start: '2025-01-01', end: '2025-01-31' })}>January</button>
              <button type="button" onClick={() => setDraft({ start: '2025-01-01', end: '2025-06-30' })}>First half</button>
            </div>
            <button className="primary-button" type="submit">Apply range</button>
          </form>
        </Popover>
      )}
    </div>
  );
}

export function KPICard({item, index}) {
  const id = useId().replace(/:/g, '');
  return (
    <article className="card kpi-card">
      <div className="kpi-card-inner">
        <div className="metric-icon" style={{ background: item.light, color: item.color }}>
          <Icon name={item.icon} size={22} />
        </div>
        <div className="metric-content">
          <p className="metric-label">{item.label}</p>
          <div className="metric-value">{item.value}</div>
          <span className="metric-growth"><Icon name="ArrowUp" size={13} />{item.growth}</span>
        </div>
      </div>
      <div className={'sparkline ' + (index ? 'sparkline-light' : '')} aria-hidden="true">
        <ResponsiveContainer width="100%" height="100%">
          <AreaChart data={item.points.map(value => ({ value }))} margin={{ top: 4, right: 0, left: 0, bottom: 0 }}>
            <defs>
              <linearGradient id={id} x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor={item.color} stopOpacity={index ? 0.10 : 0.20} />
                <stop offset="100%" stopColor={item.color} stopOpacity={0} />
              </linearGradient>
            </defs>
            <Area type="monotone" dataKey="value" stroke={item.color} strokeWidth={index ? 2 : 2.5} fill={`url(#${id})`} dot={false} isAnimationActive={false} />
          </AreaChart>
        </ResponsiveContainer>
      </div>
    </article>
  );
}

function ChartTooltip({active, payload, label}) {
  if (!active || !payload?.length) return null;
  return (
    <div className="chart-tooltip">
      <span>{label}</span>
      <strong>${payload[0].value.toLocaleString('en-US')}</strong>
      <small>Revenue</small>
    </div>
  );
}

export function RevenueChart() {
  const [period, setPeriod] = useState('This Month');
  const values = chartData(period);
  const quarterly = period === 'This Quarter', yearly = period === 'This Year', weekly = period === 'This Week';

  return (
    <section className="card revenue-card">
      <div className="card-header">
        <h2>Revenue Growth</h2>
        <div className="period-select">
          <select aria-label="Revenue period" value={period} onChange={e => setPeriod(e.target.value)}>
            {periods.map(p => <option key={p}>{p}</option>)}
          </select>
          <Icon name="ChevronDown" size={13} />
        </div>
      </div>
      <div className="main-chart" role="img" aria-label={`Revenue: ${values.map(v => v.name + ' ' + v.value).join(', ')}`}>
        <ResponsiveContainer width="100%" height="100%">
          <AreaChart data={values} margin={{ top: 18, right: 8, left: -17, bottom: 4 }}>
            <defs>
              <linearGradient id="revenue-gradient" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor="#005A36" stopOpacity={0.22} />
                <stop offset="95%" stopColor="#00A862" stopOpacity={0.005} />
              </linearGradient>
            </defs>
            <CartesianGrid vertical={false} stroke="var(--grid)" strokeDasharray="4 5" />
            <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{ fill: '#94A3B8', fontSize: 11, fontFamily: 'Plus Jakarta Sans' }} dy={12} padding={{ left: 10, right: 10 }} />
            <YAxis domain={[0, yearly ? 120000 : quarterly ? 80000 : weekly ? 6000 : 30000]} ticks={yearly ? [0, 40000, 80000, 120000] : quarterly ? [0, 20000, 40000, 60000, 80000] : weekly ? [0, 2000, 4000, 6000] : [0, 10000, 20000, 30000]} axisLine={false} tickLine={false} tick={{ fill: '#94A3B8', fontSize: 11, fontFamily: 'Plus Jakarta Sans' }} tickFormatter={v => v === 0 ? '0' : v / 1000 + 'K'} />
            <Tooltip content={<ChartTooltip />} cursor={{ stroke: '#D1E7DD', strokeDasharray: '4 4' }} />
            <Area type="monotone" dataKey="value" stroke="#005A36" strokeWidth={3} fill="url(#revenue-gradient)" dot={false} activeDot={{ r: 5, stroke: 'var(--surface)', strokeWidth: 3, fill: '#005A36' }} isAnimationActive={false} />
          </AreaChart>
        </ResponsiveContainer>
      </div>
    </section>
  );
}

export function TopProducts({data, role}) {
  return (
    <section className="card products-card">
      <div className="card-header">
        <h2>Top Products</h2>
        {can(role, 'products') && (
          <a className="text-link view-all" href={routeFor('products', 'products')}>
            View all <Icon name="ChevronRight" size={14} />
          </a>
        )}
      </div>
      <div className="product-list">
        {productStats.map(stat => {
          const product = data.products.find(p => p.id === stat.id);
          if (!product) return null;
          const content = (
            <>
              <span className="product-icon"><Icon name={stat.icon} size={23} /></span>
              <span className="product-info">
                <strong>{product.name}</strong>
                <small>{stat.sold} sold</small>
              </span>
              <strong className="product-price">{stat.price}</strong>
            </>
          );
          return can(role, 'products') ? (
            <a className="product-row" key={stat.id} href={routeFor('products', 'products', stat.id)}>
              {content}
            </a>
          ) : (
            <div className="product-row" key={stat.id}>{content}</div>
          );
        })}
      </div>
    </section>
  );
}

export default function Dashboard({data, role, range, setRange}) {
  return (
    <>
      <div className="page-header">
        <div>
          <h1>Overview</h1>
          <p>Here's what's happening today.</p>
        </div>
        <DateRangeSelector range={range} setRange={setRange} />
      </div>
      <div className="kpi-grid">
        {kpis.map((item, i) => (
          <KPICard key={item.label} item={item} index={i} />
        ))}
      </div>
      <div className="analytics-grid">
        <RevenueChart />
        <TopProducts data={data} role={role} />
      </div>
    </>
  );
}
