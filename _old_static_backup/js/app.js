/**
 * PESISIR-EDU | app.js
 * Main application entry point & orchestration
 */

// ===== APP STATE =====
window.lastAnalysisResult = null;
window.customDepartureCoords = null;
window.isClickingMap = false;

// ===== LOADING SEQUENCE =====
async function bootApp() {
  const steps = [
    { msg: 'Memuat data lokasi pesisir Bengkulu...', pct: 20, delay: 400 },
    { msg: 'Menginisialisasi WebGIS & peta interaktif...', pct: 45, delay: 500 },
    { msg: 'Mengkonfigurasi mesin SAW & Haversine...', pct: 65, delay: 400 },
    { msg: 'Memuat data cuaca & kondisi gelombang...', pct: 80, delay: 350 },
    { msg: 'Membangun antarmuka PESISIR-EDU...', pct: 95, delay: 400 },
    { msg: 'Sistem siap digunakan! 🌊', pct: 100, delay: 300 },
  ];

  const bar = document.getElementById('loading-bar');
  const status = document.getElementById('loading-status');

  for (const step of steps) {
    await sleep(step.delay);
    bar.style.width = step.pct + '%';
    status.textContent = step.msg;
  }

  await sleep(500);

  // Show app
  document.getElementById('loading-screen').style.opacity = '0';
  document.getElementById('loading-screen').style.transition = 'opacity 0.6s ease';
  await sleep(600);
  document.getElementById('loading-screen').style.display = 'none';
  document.getElementById('app').classList.remove('hidden');
  document.getElementById('app').style.opacity = '0';
  document.getElementById('app').style.transition = 'opacity 0.5s ease';
  await sleep(50);
  document.getElementById('app').style.opacity = '1';

  // Initialize modules
  injectRouteCSSAnimation();
  initMap();
  initWeatherWidget();
  renderSubjectChips();
  onWeightChange();
  startLiveClock();

  // Auto select example subjects
  setTimeout(() => {
    // Default: select Geografi & Biologi as a demo
    // (user can change these)
  }, 100);
}

function sleep(ms) { return new Promise(resolve => setTimeout(resolve, ms)); }

// ===== MAIN SPK ANALYSIS =====
async function runSPKAnalysis() {
  const btn = document.getElementById('analyze-btn');
  const btnText = btn.querySelector('.btn-text');

  // Validate inputs
  const selectedSubjects = getSelectedSubjects();
  if (selectedSubjects.length === 0) {
    showNotification('⚠️ Pilih minimal 1 mata pelajaran terlebih dahulu!', 'warning');
    document.getElementById('subject-chips').style.animation = 'none';
    setTimeout(() => {
      document.getElementById('subject-chips').style.animation = '';
    }, 10);
    return;
  }

  const departureCoords = getDepartureCoords();
  const weights = getWeights();
  const totalWeight = weights.w1 + weights.w2 + weights.w3 + weights.w4;

  if (totalWeight !== 100) {
    showNotification(`⚠️ Total bobot harus 100% (saat ini ${totalWeight}%)`, 'warning');
    return;
  }

  // Loading state
  btn.classList.add('loading');
  btnText.textContent = 'Menganalisis';

  await sleep(600); // Simulate processing time

  const proximityRadius = parseInt(document.getElementById('radius-slider').value);

  try {
    // If no departure, use center of Bengkulu city
    const depCoords = departureCoords || [-3.8004, 102.2644];

    // Run SAW analysis
    const analysisResult = runSAWAnalysis(selectedSubjects, depCoords, weights, proximityRadius);

    // Store globally
    window.lastAnalysisResult = analysisResult;

    // Update map markers and routes
    highlightAnalysisResults(analysisResult, departureCoords || depCoords);

    // Update departure marker if not already set
    if (departureCoords) {
      setDepartureMarker(depCoords[0], depCoords[1]);
    }

    // Render results panel
    renderResults(analysisResult);

    // If analytics mode is active, update it too
    if (!document.getElementById('analytics-panel').classList.contains('hidden')) {
      renderAnalyticsPanel(analysisResult);
    }

    // Show success notification
    const scenarioMsg = analysisResult.scenario === 'scenario2'
      ? `🔀 Skenario 2: Ditemukan paket ${analysisResult.result.stops?.length || 2} lokasi berdekatan`
      : analysisResult.scenario === 'scenario1'
        ? `🎯 Skenario 1: Ditemukan lokasi terpadu optimal`
        : `📍 Analisis selesai: ${analysisResult.ranked.length} lokasi diranking`;
    showNotification(scenarioMsg, 'success');

  } catch (err) {
    console.error('SPK Analysis error:', err);
    showNotification('❌ Terjadi kesalahan dalam analisis. Coba lagi.', 'error');
  } finally {
    btn.classList.remove('loading');
    btnText.textContent = 'Jalankan Analisis SPK';
  }
}

// ===== NOTIFICATION SYSTEM =====
let notifTimeout = null;
function showNotification(message, type = 'info') {
  // Remove existing
  const existing = document.getElementById('app-notification');
  if (existing) existing.remove();
  if (notifTimeout) clearTimeout(notifTimeout);

  const colors = {
    success: { bg: 'rgba(16,185,129,0.15)', border: 'rgba(16,185,129,0.4)', text: '#10B981' },
    warning: { bg: 'rgba(245,158,11,0.15)', border: 'rgba(245,158,11,0.4)', text: '#F59E0B' },
    error: { bg: 'rgba(239,68,68,0.15)', border: 'rgba(239,68,68,0.4)', text: '#EF4444' },
    info: { bg: 'rgba(0,240,255,0.1)', border: 'rgba(0,240,255,0.3)', text: '#00F0FF' },
  };
  const c = colors[type] || colors.info;

  const el = document.createElement('div');
  el.id = 'app-notification';
  el.style.cssText = `
    position: fixed;
    top: ${60 + 12 + 12}px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9000;
    padding: 10px 20px;
    border-radius: 10px;
    background: ${c.bg};
    border: 1px solid ${c.border};
    color: ${c.text};
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    backdrop-filter: blur(16px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    animation: slide-in-up 0.3s ease;
    white-space: nowrap;
  `;
  el.textContent = message;
  document.body.appendChild(el);

  notifTimeout = setTimeout(() => {
    el.style.opacity = '0';
    el.style.transition = 'opacity 0.3s ease';
    setTimeout(() => el.remove(), 300);
  }, 3500);
}

// ===== INITIALIZE ON DOM READY =====
document.addEventListener('DOMContentLoaded', () => {
  bootApp();
});

window.runSPKAnalysis = runSPKAnalysis;
window.showNotification = showNotification;
window.bootApp = bootApp;
