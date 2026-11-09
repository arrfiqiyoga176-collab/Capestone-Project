/**
 * PESISIR-EDU | weather.js
 * Weather simulation, ticker, and widget rendering
 */

// Simulated real-time weather for the overall coastal area
const COASTAL_OVERALL_WEATHER = {
  station: 'BMKG Bengkulu',
  condition: 'Berawan Sebagian',
  temp_c: 28,
  humidity_pct: 82,
  wind_speed_knots: 9,
  wind_dir: 'SW',
  wave_height_m: 1.1,
  visibility_km: 8,
  pressure_hpa: 1011,
  risk: 'medium',
  updated: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
};

function initWeatherWidget() {
  renderWeatherWidget(COASTAL_OVERALL_WEATHER);
  renderWeatherTicker();
}

function renderWeatherWidget(data) {
  const riskLabel = { low: 'Risiko Rendah', medium: 'Risiko Sedang', high: 'Risiko Tinggi' };
  const badge = document.getElementById('overall-risk');
  badge.textContent = riskLabel[data.risk] || '—';
  badge.className = `weather-badge risk-${data.risk}`;

  const grid = document.getElementById('weather-grid');
  grid.innerHTML = `
    <div class="weather-item">
      <div class="weather-item-label">Kondisi</div>
      <div class="weather-item-value" style="font-size:0.78rem">${data.condition}</div>
    </div>
    <div class="weather-item">
      <div class="weather-item-label">Suhu</div>
      <div class="weather-item-value">${data.temp_c}<span class="weather-item-unit">°C</span></div>
    </div>
    <div class="weather-item">
      <div class="weather-item-label">Tinggi Gelombang</div>
      <div class="weather-item-value" style="color:${data.wave_height_m > 1.5 ? '#EF4444' : data.wave_height_m > 0.8 ? '#F59E0B' : '#10B981'}">${data.wave_height_m}<span class="weather-item-unit"> m</span></div>
    </div>
    <div class="weather-item">
      <div class="weather-item-label">Angin</div>
      <div class="weather-item-value">${data.wind_speed_knots}<span class="weather-item-unit"> kn</span></div>
      <div style="font-size:0.6rem;color:var(--clr-text-muted)">${data.wind_dir} &bull; ${(data.wind_speed_knots * 1.852).toFixed(1)} km/j</div>
    </div>
    <div class="weather-item">
      <div class="weather-item-label">Visibilitas</div>
      <div class="weather-item-value">${data.visibility_km}<span class="weather-item-unit"> km</span></div>
    </div>
    <div class="weather-item">
      <div class="weather-item-label">Kelembaban</div>
      <div class="weather-item-value">${data.humidity_pct}<span class="weather-item-unit">%</span></div>
    </div>
  `;
}

function renderWeatherTicker() {
  const riskColor = { low: '#10B981', medium: '#F59E0B', high: '#EF4444' };

  // Build ticker items from all locations
  const items = LOCATIONS.map(loc => {
    const color = riskColor[loc.weather.risk] || '#10B981';
    return `
      <span class="ticker-item">
        <span class="ticker-dot" style="background:${color};box-shadow:0 0 6px ${color}"></span>
        ${loc.shortName}: ${loc.weather.wave_height_m}m &bull; ${loc.weather.condition} &bull; Angin ${loc.weather.wind_speed_knots}kn ${loc.weather.wind_dir}
      </span>
    `;
  }).join('');

  const tickerEl = document.getElementById('ticker-items');
  // Duplicate for seamless loop
  tickerEl.innerHTML = items + items;
}

/**
 * Get risk color CSS variable
 */
function getRiskColor(risk) {
  return { low: '#10B981', medium: '#F59E0B', high: '#EF4444' }[risk] || '#10B981';
}

/**
 * Render weather badge for a single location
 */
function buildWeatherBadge(weather) {
  const riskLabel = { low: 'Rendah', medium: 'Sedang', high: 'Tinggi' };
  const color = getRiskColor(weather.risk);
  return `
    <div style="
      display:flex; gap:8px; flex-wrap:wrap; align-items:center;
      padding:8px 10px; border-radius:8px;
      background:rgba(${hexToRgb(color)},0.06);
      border:1px solid rgba(${hexToRgb(color)},0.2);
      margin-top:6px;
    ">
      <span style="font-size:0.65rem;color:var(--clr-text-muted)">
        🌤 ${weather.condition} &nbsp;|&nbsp;
        🌊 ${weather.wave_height_m}m &nbsp;|&nbsp;
        💨 ${weather.wind_speed_knots}kn ${weather.wind_dir}
      </span>
      <span class="risk-badge ${weather.risk}">
        <span class="risk-dot"></span>
        Risiko ${riskLabel[weather.risk]}
      </span>
    </div>
  `;
}

function hexToRgb(hex) {
  const res = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return res ? `${parseInt(res[1],16)},${parseInt(res[2],16)},${parseInt(res[3],16)}` : '0,240,255';
}

window.initWeatherWidget = initWeatherWidget;
window.renderWeatherTicker = renderWeatherTicker;
window.buildWeatherBadge = buildWeatherBadge;
window.getRiskColor = getRiskColor;
