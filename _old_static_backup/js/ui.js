/**
 * PESISIR-EDU | ui.js
 * UI rendering: chips, result cards, SAW modals, radar charts, analytics mode
 */

// ===== SUBJECT CHIPS =====
function renderSubjectChips() {
  const container = document.getElementById('subject-chips');
  container.innerHTML = SUBJECTS.map(s => `
    <div class="subject-chip" id="chip-${s.id}"
      style="--chip-color:${s.color}"
      onclick="toggleSubject('${s.id}')"
      title="${s.desc}"
    >
      <span class="chip-emoji">${s.emoji}</span>
      <span>${s.label}</span>
    </div>
  `).join('');
}

function toggleSubject(id) {
  const chip = document.getElementById(`chip-${id}`);
  chip.classList.toggle('active');
  updateSubjectCount();
}

function selectAllSubjects() {
  SUBJECTS.forEach(s => document.getElementById(`chip-${s.id}`).classList.add('active'));
  updateSubjectCount();
}

function clearAllSubjects() {
  SUBJECTS.forEach(s => document.getElementById(`chip-${s.id}`).classList.remove('active'));
  updateSubjectCount();
}

function getSelectedSubjects() {
  return SUBJECTS.filter(s => document.getElementById(`chip-${s.id}`)?.classList.contains('active')).map(s => s.id);
}

function updateSubjectCount() {
  const count = getSelectedSubjects().length;
  document.getElementById('subject-count').textContent = count;
}

// ===== DEPARTURE =====
function onDepartureChange() {
  const select = document.getElementById('departure-select');
  const hint = document.getElementById('click-map-hint');
  const coordDisplay = document.getElementById('custom-coord-display');

  if (select.value === 'custom') {
    hint.style.display = 'flex';
    window.isClickingMap = true;
    coordDisplay.classList.add('hidden');
  } else if (select.value) {
    hint.style.display = 'none';
    window.isClickingMap = false;
    coordDisplay.classList.add('hidden');
    const [lat, lng] = select.value.split(',').map(Number);
    setDepartureMarker(lat, lng);
    window.customDepartureCoords = null;
    mapInstance.flyTo([lat, lng], 14, { duration: 1 });
  } else {
    hint.style.display = 'none';
    window.isClickingMap = false;
    removeDepartureMarker();
    window.customDepartureCoords = null;
  }
}

function getDepartureCoords() {
  if (window.customDepartureCoords) return window.customDepartureCoords;
  const val = document.getElementById('departure-select').value;
  if (val && val !== 'custom') {
    return val.split(',').map(Number);
  }
  return null;
}

// ===== RADIUS =====
function onRadiusChange(val) {
  const km = (val / 1000).toFixed(1);
  document.getElementById('radius-value').textContent = `${km} km`;
}

// ===== SAW WEIGHTS =====
function onWeightChange() {
  const w1 = parseInt(document.getElementById('w1').value);
  const w2 = parseInt(document.getElementById('w2').value);
  const w3 = parseInt(document.getElementById('w3').value);
  const w4 = parseInt(document.getElementById('w4').value);
  const total = w1 + w2 + w3 + w4;

  document.getElementById('w1-val').textContent = `${w1}%`;
  document.getElementById('w2-val').textContent = `${w2}%`;
  document.getElementById('w3-val').textContent = `${w3}%`;
  document.getElementById('w4-val').textContent = `${w4}%`;

  const totalEl = document.getElementById('weight-total');
  totalEl.textContent = `${total}%`;
  totalEl.className = `weight-total-value${total !== 100 ? ' error' : ''}`;

  // Update visual segments
  const segs = document.getElementById('weight-segments');
  segs.innerHTML = `
    <div class="weight-seg" style="width:${w1}%;background:#00F0FF"></div>
    <div class="weight-seg" style="width:${w2}%;background:#FF6B4A"></div>
    <div class="weight-seg" style="width:${w3}%;background:#10B981"></div>
    <div class="weight-seg" style="width:${w4}%;background:#F59E0B"></div>
  `;
}

function getWeights() {
  return {
    w1: parseInt(document.getElementById('w1').value),
    w2: parseInt(document.getElementById('w2').value),
    w3: parseInt(document.getElementById('w3').value),
    w4: parseInt(document.getElementById('w4').value),
  };
}

// ===== RESULTS RENDERING =====
function renderResults(analysisResult) {
  const { scenario, result, subjects, ranked } = analysisResult;
  const body = document.getElementById('results-body');
  const meta = document.getElementById('results-meta');

  const subjectNames = subjects.map(sid => {
    const s = SUBJECTS.find(x => x.id === sid);
    return s ? `${s.emoji} ${s.label}` : sid;
  });

  meta.innerHTML = `
    <div>${subjectNames.join(', ') || '—'}</div>
    <div>${new Date().toLocaleTimeString('id-ID')}</div>
  `;

  if (result.type === 'single' || result.type === 'multi' && result.stops) {
    body.innerHTML = '';

    // Scenario badge
    const isScenario2 = scenario === 'scenario2';
    const badgeHtml = `
      <div class="scenario-badge ${isScenario2 ? 'multi' : 'single'}">
        ${isScenario2
          ? '🔀 Skenario 2: Paket Lokasi Berdekatan'
          : '🎯 Skenario 1: Lokasi Terpadu Optimal'}
      </div>
    `;
    const badgeDiv = document.createElement('div');
    badgeDiv.innerHTML = badgeHtml;
    body.appendChild(badgeDiv);

    if (result.type === 'multi') {
      // Multi-location itinerary card
      body.appendChild(buildItineraryCard(result, analysisResult));

      // Show full ranking below
      const rankTitle = document.createElement('div');
      rankTitle.style.cssText = 'font-size:0.7rem;color:var(--clr-text-muted);margin-top:8px;padding:0 2px';
      rankTitle.textContent = 'Semua Lokasi Relevan (Peringkat SAW):';
      body.appendChild(rankTitle);

      result.relevantRanked.forEach((row, i) => {
        body.appendChild(buildResultCard(row, i, subjects, analysisResult));
      });
    } else {
      // Single scenario — ranked cards
      if (result.fullyMatched && result.fullyMatched.length > 0) {
        const matchLabel = document.createElement('div');
        matchLabel.style.cssText = 'font-size:0.7rem;color:var(--clr-cyan);margin-bottom:2px;padding:0 2px';
        matchLabel.innerHTML = `✅ Ditemukan <strong>${result.fullyMatched.length}</strong> lokasi mencakup semua mata pelajaran`;
        body.appendChild(matchLabel);
      }

      result.topResults.forEach((row, i) => {
        body.appendChild(buildResultCard(row, i, subjects, analysisResult));
      });
    }

    // SAW detail button
    const sawBtn = document.createElement('button');
    sawBtn.className = 'card-action-btn secondary';
    sawBtn.style.cssText = 'width:100%;margin-top:4px;padding:8px';
    sawBtn.innerHTML = '📊 Lihat Detail Perhitungan SAW';
    sawBtn.onclick = () => showSAWDetail(analysisResult);
    body.appendChild(sawBtn);

  } else {
    body.innerHTML = `<div class="results-placeholder"><p class="placeholder-title">Tidak ada hasil</p><p class="placeholder-desc">Tidak ditemukan lokasi yang sesuai dengan filter yang dipilih.</p></div>`;
  }
}

function buildResultCard(row, index, selectedSubjects, analysisResult) {
  const loc = row.loc;
  const score = row.V;
  const rankClass = ['r1','r2','r3'][index] || '';
  const isTop = index === 0;

  const matchedSubjects = loc.subjects.filter(sid => selectedSubjects.includes(sid));
  const unmatchedSubjects = loc.subjects.filter(sid => !selectedSubjects.includes(sid));

  const distM = row.c2 * 1000;
  const travel = distM > 0 ? estimateTravelTime(distM) : null;

  const card = document.createElement('div');
  card.className = `result-card${isTop ? ' top-result' : ''}`;
  card.style.animationDelay = `${index * 0.06}s`;
  card.innerHTML = `
    <div class="card-header">
      <div class="card-rank ${rankClass}">#${index + 1}</div>
      <div style="flex:1">
        <div class="card-name">${loc.emoji} ${loc.name}</div>
        <div style="font-size:0.65rem;color:var(--clr-text-muted)">${loc.zone}</div>
      </div>
      <div class="card-score">
        <div class="score-label">Skor SAW</div>
        <div class="score-value">${score.toFixed(4)}</div>
      </div>
    </div>
    <div class="score-bar-container">
      <div class="score-bar-bg">
        <div class="score-bar-fill" style="width:${(score * 100).toFixed(1)}%"></div>
      </div>
    </div>
    <div class="card-subjects">
      ${matchedSubjects.map(sid => {
        const s = SUBJECTS.find(x => x.id === sid);
        return s ? `<span class="card-subject-tag matched">${s.emoji} ${s.label}</span>` : '';
      }).join('')}
      ${unmatchedSubjects.map(sid => {
        const s = SUBJECTS.find(x => x.id === sid);
        return s ? `<span class="card-subject-tag">${s.emoji} ${s.label}</span>` : '';
      }).join('')}
    </div>
    <div class="card-meta">
      <span class="card-meta-item">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
        ${row.c2 > 0 ? formatDistance(distM) : 'N/A'}
      </span>
      ${travel ? `<span class="card-meta-item">🚗 ${travel.drive} · 🚶 ${travel.walk}</span>` : ''}
      <span class="card-meta-item">
        <span class="risk-badge ${loc.weather.risk}">
          <span class="risk-dot"></span>
          Risiko ${{'low':'Rendah','medium':'Sedang','high':'Tinggi'}[loc.weather.risk]}
        </span>
      </span>
    </div>
    ${buildWeatherBadge(loc.weather)}
    <div class="card-actions">
      <button class="card-action-btn primary" onclick="flyToLocation('${loc.id}')">
        🗺️ Fokus Peta
      </button>
      <button class="card-action-btn secondary" onclick="showLocationDetail(window.LOCATIONS.find(l=>l.id==='${loc.id}'), ${score})">
        🔍 Detail
      </button>
    </div>
  `;
  return card;
}

function buildItineraryCard(result, analysisResult) {
  const stopLetters = ['A', 'B', 'C'];
  const stopClasses = ['a', 'b', 'c'];

  let stopsHtml = '';
  result.stops.forEach((stop, i) => {
    const loc = stop.locData.loc;
    const subjectNames = stop.coveredSubjects.map(sid => {
      const s = SUBJECTS.find(x => x.id === sid);
      return s ? `${s.emoji} ${s.label}` : sid;
    }).join(', ');

    stopsHtml += `
      <div class="itinerary-stop">
        <div class="stop-indicator">
          <div class="stop-dot ${stopClasses[i]}">${stopLetters[i]}</div>
        </div>
        <div class="stop-content">
          <div class="stop-name">${loc.emoji} ${loc.name}</div>
          <div class="stop-subjects">${subjectNames}</div>
          <div class="stop-score">Skor SAW: <span style="font-family:var(--font-mono)">${stop.locData.V.toFixed(4)}</span></div>
        </div>
        <button class="card-action-btn primary" style="width:auto;padding:4px 8px;font-size:0.6rem"
          onclick="flyToLocation('${loc.id}')">🗺️</button>
      </div>
    `;

    // Distance connector between stops
    if (i < result.stopDistances.length) {
      const dist = result.stopDistances[i];
      stopsHtml += `
        <div class="distance-connector">
          <span class="dist-icon">📏</span>
          <span class="dist-value">${formatDistance(dist.distM)}</span>
          <span style="color:var(--clr-text-muted)">· 🚗 ${dist.travel.drive} · 🚶 ${dist.travel.walk}</span>
        </div>
      `;
    }
  });

  const card = document.createElement('div');
  card.className = 'itinerary-card';
  card.innerHTML = `
    <div class="itinerary-header">
      <div>
        <div class="itinerary-title">🔀 Paket Lokasi Belajar Multi-Stop</div>
        <div class="itinerary-subtitle">
          ${result.stops.length} lokasi · Total jarak ${formatDistance(result.totalDist)}
          ${result.withinRadius ? '&nbsp;✅ Dalam radius' : '&nbsp;⚠️ Di luar radius'}
          · Skor gabungan: <span style="font-family:var(--font-mono);color:var(--clr-coral)">${result.combinedScore.toFixed(4)}</span>
        </div>
      </div>
    </div>
    <div class="itinerary-body">${stopsHtml}</div>
    <div class="itinerary-actions">
      <button class="card-action-btn primary" style="flex:1" onclick="flyToBoundsOfStops(window.lastAnalysisResult?.result?.stops)">
        🗺️ Tampilkan Semua di Peta
      </button>
      <button class="card-action-btn secondary" style="flex:1" onclick="showSAWDetail(window.lastAnalysisResult)">
        📊 Detail SAW
      </button>
    </div>
  `;
  return card;
}

// ===== SAW DETAIL MODAL =====
function showSAWDetail(analysisResult) {
  const { sawDetail, subjects } = analysisResult;
  const { rawMatrix, normMatrix, ranked, weights, maxC1, minC2, maxC3, maxC4 } = sawDetail;

  const subNames = (sids) => sids.map(sid => SUBJECTS.find(s => s.id === sid)?.label || sid).join(' + ');

  const w1p = (weights.w1 * 100).toFixed(0);
  const w2p = (weights.w2 * 100).toFixed(0);
  const w3p = (weights.w3 * 100).toFixed(0);
  const w4p = (weights.w4 * 100).toFixed(0);

  // Build decision matrix rows
  const rawRows = rawMatrix.map(row => `
    <tr>
      <td>${row.loc.emoji} ${row.loc.shortName}</td>
      <td>${row.c1.toFixed(2)}</td>
      <td>${row.c2.toFixed(3)}</td>
      <td>${row.c3.toFixed(2)}</td>
      <td>${row.c4.toFixed(2)}</td>
    </tr>
  `).join('');

  // Build normalization matrix rows
  const normRows = normMatrix.map(row => `
    <tr class="${ranked[0]?.loc.id === row.loc.id ? 'top-row' : ''}">
      <td>${row.loc.emoji} ${row.loc.shortName}</td>
      <td class="highlight">${row.r1.toFixed(4)}</td>
      <td class="highlight">${row.r2.toFixed(4)}</td>
      <td class="highlight">${row.r3.toFixed(4)}</td>
      <td class="highlight">${row.r4.toFixed(4)}</td>
    </tr>
  `).join('');

  // Preference score rows
  const prefRows = ranked.map((row, i) => `
    <div class="rank-row" style="${i === 0 ? 'background:rgba(0,240,255,0.05);border-color:rgba(0,240,255,0.2)' : ''}">
      <div class="rank-num">${i + 1}</div>
      <div class="rank-name">${row.loc.emoji} ${row.loc.shortName}</div>
      <div class="rank-score-bar"><div class="rank-score-fill" style="width:${(row.V * 100).toFixed(1)}%"></div></div>
      <div class="rank-val">${row.V.toFixed(4)}</div>
    </div>
  `).join('');

  const body = document.getElementById('saw-modal-body');
  body.innerHTML = `
    <div class="saw-section">
      <div class="saw-section-title">📋 Informasi Analisis</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:0.75rem;color:var(--clr-text-secondary)">
        <div><strong style="color:var(--clr-cyan)">Mata Pelajaran:</strong><br>${subNames(subjects) || 'Semua'}</div>
        <div><strong style="color:var(--clr-cyan)">Bobot (W1-W4):</strong><br><span class="mono-text">${w1p}% / ${w2p}% / ${w3p}% / ${w4p}%</span></div>
        <div><strong style="color:var(--clr-cyan)">Skenario:</strong><br>${analysisResult.scenario === 'scenario2' ? '🔀 Skenario 2 (Multi-Lokasi)' : '🎯 Skenario 1 (Lokasi Tunggal)'}</div>
        <div><strong style="color:var(--clr-cyan)">Jumlah Alternatif:</strong><br>${rawMatrix.length} lokasi</div>
      </div>
    </div>

    <div class="saw-section">
      <div class="saw-section-title">1️⃣ Matriks Keputusan Awal (X)</div>
      <div style="font-size:0.65rem;color:var(--clr-text-muted);margin-bottom:8px">
        C1: Relevansi Mapel (Benefit) | C2: Jarak km (Cost) | C3: Daya Tarik Edukasi (Benefit) | C4: Keamanan Pesisir (Benefit)
      </div>
      <table class="matrix-table">
        <thead>
          <tr><th>Alternatif</th><th>C1</th><th>C2 (km)</th><th>C3</th><th>C4</th></tr>
        </thead>
        <tbody>${rawRows}</tbody>
      </table>
    </div>

    <div class="saw-section">
      <div class="saw-section-title">2️⃣ Matriks Normalisasi (R)</div>
      <div style="font-size:0.65rem;color:var(--clr-text-muted);margin-bottom:8px">
        r<sub>ij</sub> = x<sub>ij</sub>/max(x<sub>j</sub>) [Benefit] &nbsp;|&nbsp; r<sub>ij</sub> = min(x<sub>j</sub>)/x<sub>ij</sub> [Cost]<br>
        max(C1)=${maxC1.toFixed(2)}, min(C2)=${minC2.toFixed(3)} km, max(C3)=${maxC3.toFixed(2)}, max(C4)=${maxC4.toFixed(2)}
      </div>
      <table class="matrix-table">
        <thead>
          <tr><th>Alternatif</th><th>r1</th><th>r2</th><th>r3</th><th>r4</th></tr>
        </thead>
        <tbody>${normRows}</tbody>
      </table>
    </div>

    <div class="saw-section">
      <div class="saw-section-title">3️⃣ Nilai Preferensi (V) & Perankingan</div>
      <div style="font-size:0.65rem;color:var(--clr-text-muted);margin-bottom:10px">
        V<sub>i</sub> = ${w1p}%×r1 + ${w2p}%×r2 + ${w3p}%×r3 + ${w4p}%×r4
      </div>
      <div>${prefRows}</div>
    </div>
  `;

  document.getElementById('saw-modal-overlay').classList.remove('hidden');
}

function closeSAWModal(event) {
  if (!event || event.target === document.getElementById('saw-modal-overlay')) {
    document.getElementById('saw-modal-overlay').classList.add('hidden');
  }
}

// ===== LOCATION DETAIL MODAL =====
function showLocationDetail(loc, score) {
  if (!loc) return;
  document.getElementById('loc-modal-title').textContent = `${loc.emoji} ${loc.name}`;

  const riskLabel = { low: 'Rendah', medium: 'Sedang', high: 'Tinggi' };
  const subjectTags = loc.subjects.map(sid => {
    const s = SUBJECTS.find(x => x.id === sid);
    return s ? `<span class="loc-detail-tag">${s.emoji} ${s.label}</span>` : '';
  }).join('');

  const body = document.getElementById('loc-modal-body');
  body.innerHTML = `
    <div class="loc-detail-header">
      <div class="loc-detail-img">${loc.emoji}</div>
      <div class="loc-detail-info">
        <div class="loc-detail-name">${loc.name}</div>
        <div class="loc-detail-zone">📍 ${loc.zone}</div>
        <div class="loc-detail-coords">${loc.coords[0].toFixed(5)}°, ${loc.coords[1].toFixed(5)}°</div>
        <div class="loc-detail-tags">${subjectTags}</div>
      </div>
    </div>

    <div class="loc-detail-grid">
      ${score !== null ? `
      <div class="loc-detail-item">
        <div class="loc-detail-item-label">Skor SAW</div>
        <div class="loc-detail-item-value" style="color:var(--clr-cyan);font-family:var(--font-mono)">${score.toFixed(4)}</div>
      </div>` : ''}
      <div class="loc-detail-item">
        <div class="loc-detail-item-label">Tinggi Gelombang</div>
        <div class="loc-detail-item-value" style="color:${loc.weather.wave_height_m > 1.5 ? '#EF4444' : loc.weather.wave_height_m > 0.8 ? '#F59E0B' : '#10B981'};font-family:var(--font-mono)">${loc.weather.wave_height_m} m</div>
      </div>
      <div class="loc-detail-item">
        <div class="loc-detail-item-label">Kondisi Cuaca</div>
        <div class="loc-detail-item-value" style="font-size:0.78rem">${loc.weather.condition}</div>
      </div>
      <div class="loc-detail-item">
        <div class="loc-detail-item-label">Angin</div>
        <div class="loc-detail-item-value" style="font-family:var(--font-mono)">${loc.weather.wind_speed_knots} kn ${loc.weather.wind_dir}</div>
      </div>
      <div class="loc-detail-item">
        <div class="loc-detail-item-label">Tingkat Risiko</div>
        <div class="loc-detail-item-value"><span class="risk-badge ${loc.weather.risk}"><span class="risk-dot"></span>${riskLabel[loc.weather.risk]}</span></div>
      </div>
      <div class="loc-detail-item">
        <div class="loc-detail-item-label">Daya Tarik Edukasi</div>
        <div class="loc-detail-item-value" style="font-family:var(--font-mono)">${loc.c3_eduAttract}/5.0</div>
      </div>
    </div>

    <div class="loc-detail-desc">${loc.description}</div>

    <div style="font-size:0.72rem;font-weight:600;color:var(--clr-cyan);margin-bottom:8px">🏗️ Fasilitas Tersedia</div>
    <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:14px">
      ${loc.facilities.map(f => `<span style="padding:3px 8px;border-radius:8px;font-size:0.62rem;background:rgba(16,185,129,0.08);color:var(--clr-emerald);border:1px solid rgba(16,185,129,0.2)">${f}</span>`).join('')}
    </div>

    <div style="font-size:0.72rem;font-weight:600;color:var(--clr-cyan);margin-bottom:8px">📊 Profil Kriteria SAW</div>
    <div class="radar-container" id="radar-chart-container">
      <canvas id="radar-chart"></canvas>
    </div>
  `;

  document.getElementById('loc-modal-overlay').classList.remove('hidden');

  // Render radar chart
  setTimeout(() => renderRadarChart(loc, score), 100);
}

function closeLocModal(event) {
  if (!event || event.target === document.getElementById('loc-modal-overlay')) {
    document.getElementById('loc-modal-overlay').classList.add('hidden');
    // Destroy chart
    const canvas = document.getElementById('radar-chart');
    if (canvas && canvas._chart) { canvas._chart.destroy(); canvas._chart = null; }
  }
}

// ===== RADAR CHART =====
let radarChartInstance = null;
function renderRadarChart(loc, sawScore) {
  const canvas = document.getElementById('radar-chart');
  if (!canvas) return;
  if (radarChartInstance) { radarChartInstance.destroy(); radarChartInstance = null; }

  const ctx = canvas.getContext('2d');
  radarChartInstance = new Chart(ctx, {
    type: 'radar',
    data: {
      labels: ['C1 Relevansi', 'C2 Jarak/Akses', 'C3 Daya Tarik', 'C4 Keamanan'],
      datasets: [{
        label: loc.shortName,
        data: [
          loc.c1_relevance,
          loc.c4_safety,    // Use safety as general accessibility proxy
          loc.c3_eduAttract,
          adjustSafetyForWeather(loc.c4_safety, loc.weather),
        ],
        borderColor: '#00F0FF',
        backgroundColor: 'rgba(0, 240, 255, 0.12)',
        borderWidth: 2,
        pointBackgroundColor: '#00F0FF',
        pointBorderColor: '#050D14',
        pointBorderWidth: 2,
        pointRadius: 5,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      scales: {
        r: {
          beginAtZero: true,
          min: 0,
          max: 5,
          ticks: {
            color: 'rgba(232,244,253,0.3)',
            font: { family: 'JetBrains Mono', size: 9 },
            stepSize: 1,
            backdropColor: 'transparent',
          },
          grid: { color: 'rgba(0,240,255,0.1)' },
          angleLines: { color: 'rgba(0,240,255,0.1)' },
          pointLabels: {
            color: 'rgba(232,244,253,0.7)',
            font: { family: 'Space Grotesk', size: 10, weight: '600' },
          },
        },
      },
    },
  });
}

// ===== VIEW MODE TOGGLE =====
function setViewMode(mode) {
  const btnSpatial = document.getElementById('btn-spatial');
  const btnAnalytics = document.getElementById('btn-analytics');
  const analyticsPanel = document.getElementById('analytics-panel');

  if (mode === 'spatial') {
    btnSpatial.classList.add('active');
    btnAnalytics.classList.remove('active');
    analyticsPanel.classList.add('hidden');
  } else {
    btnSpatial.classList.remove('active');
    btnAnalytics.classList.add('active');
    analyticsPanel.classList.remove('hidden');
    if (window.lastAnalysisResult) renderAnalyticsPanel(window.lastAnalysisResult);
  }
}

// ===== ANALYTICS PANEL =====
function renderAnalyticsPanel(analysisResult) {
  const body = document.getElementById('analytics-body');
  const { sawDetail } = analysisResult;
  const { rawMatrix, normMatrix, ranked, weights } = sawDetail;

  const w = weights;
  const w1p = (w.w1 * 100).toFixed(0);
  const w2p = (w.w2 * 100).toFixed(0);
  const w3p = (w.w3 * 100).toFixed(0);
  const w4p = (w.w4 * 100).toFixed(0);

  const prefRows = ranked.slice(0, 5).map((row, i) => `
    <div class="rank-row" style="${i === 0 ? 'border-color:rgba(0,240,255,0.2);background:rgba(0,240,255,0.04)' : ''}">
      <div class="rank-num">${i + 1}</div>
      <div class="rank-name" style="font-size:0.7rem">${row.loc.emoji} ${row.loc.shortName}</div>
      <div class="rank-score-bar"><div class="rank-score-fill" style="width:${(row.V * 100).toFixed(1)}%"></div></div>
      <div class="rank-val">${row.V.toFixed(3)}</div>
    </div>
  `).join('');

  body.innerHTML = `
    <div class="analytics-content">
      <div class="analytics-section">
        <div class="analytics-section-title">📊 Peringkat Nilai Preferensi SAW</div>
        <div style="font-size:0.65rem;color:var(--clr-text-muted);margin-bottom:8px">V = ${w1p}%×r1 + ${w2p}%×r2 + ${w3p}%×r3 + ${w4p}%×r4</div>
        ${prefRows}
      </div>
      <div class="analytics-section">
        <div class="analytics-section-title">⚖️ Distribusi Bobot Kriteria</div>
        <canvas id="analytics-bar-chart" style="max-height:180px"></canvas>
      </div>
    </div>
  `;

  // Render bar chart
  setTimeout(() => {
    const canvas = document.getElementById('analytics-bar-chart');
    if (!canvas) return;
    if (window.analyticsChartInstance) { window.analyticsChartInstance.destroy(); }

    window.analyticsChartInstance = new Chart(canvas.getContext('2d'), {
      type: 'bar',
      data: {
        labels: ['C1: Relevansi', 'C2: Jarak', 'C3: Edukasi', 'C4: Keamanan'],
        datasets: [{
          label: 'Bobot (%)',
          data: [w.w1 * 100, w.w2 * 100, w.w3 * 100, w.w4 * 100],
          backgroundColor: ['rgba(0,240,255,0.5)', 'rgba(255,107,74,0.5)', 'rgba(16,185,129,0.5)', 'rgba(245,158,11,0.5)'],
          borderColor: ['#00F0FF', '#FF6B4A', '#10B981', '#F59E0B'],
          borderWidth: 1.5,
          borderRadius: 4,
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        },
        scales: {
          x: { ticks: { color: 'rgba(232,244,253,0.5)', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
          y: { ticks: { color: 'rgba(232,244,253,0.5)', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' }, max: 100 },
        },
      },
    });
  }, 100);
}

// ===== CONTROL PANEL TOGGLE =====
function toggleControlPanel() {
  const panel = document.getElementById('control-panel');
  const btn = document.getElementById('panel-collapse-btn');
  panel.classList.toggle('collapsed');
  btn.innerHTML = panel.classList.contains('collapsed')
    ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>`
    : `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>`;
}

// ===== SAW INFO MODAL =====
function showSAWInfo() {
  document.getElementById('info-modal-overlay').classList.remove('hidden');
}

// ===== LIVE CLOCK =====
function startLiveClock() {
  const update = () => {
    const now = new Date();
    document.getElementById('live-time').textContent = now.toLocaleTimeString('id-ID', {
      hour: '2-digit', minute: '2-digit', second: '2-digit',
    });
  };
  update();
  setInterval(update, 1000);
}

window.renderSubjectChips = renderSubjectChips;
window.toggleSubject = toggleSubject;
window.selectAllSubjects = selectAllSubjects;
window.clearAllSubjects = clearAllSubjects;
window.getSelectedSubjects = getSelectedSubjects;
window.updateSubjectCount = updateSubjectCount;
window.onDepartureChange = onDepartureChange;
window.getDepartureCoords = getDepartureCoords;
window.onRadiusChange = onRadiusChange;
window.onWeightChange = onWeightChange;
window.getWeights = getWeights;
window.renderResults = renderResults;
window.showSAWDetail = showSAWDetail;
window.closeSAWModal = closeSAWModal;
window.showLocationDetail = showLocationDetail;
window.closeLocModal = closeLocModal;
window.renderRadarChart = renderRadarChart;
window.setViewMode = setViewMode;
window.renderAnalyticsPanel = renderAnalyticsPanel;
window.toggleControlPanel = toggleControlPanel;
window.showSAWInfo = showSAWInfo;
window.startLiveClock = startLiveClock;
