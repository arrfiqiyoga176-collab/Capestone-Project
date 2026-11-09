/**
 * PESISIR-EDU | map.js
 * Leaflet.js map initialization, custom markers, route polylines
 */

let mapInstance = null;
let departureMarker = null;
let locationMarkers = {};
let routePolylines = [];
let isClickingMap = false;
let currentAnalysisResult = null;

// ===== MAP INITIALIZATION =====
function initMap() {
  mapInstance = L.map('map', {
    center: [-3.82, 102.27],
    zoom: 13,
    zoomControl: false,
    attributionControl: true,
  });

  // Stadia Alidade Smooth Dark tile layer (free, no API key for small projects)
  // Fallback: OSM with dark filter via CSS
  const darkTile = L.tileLayer(
    'https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png',
    {
      attribution: '&copy; <a href="https://stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://www.stamen.com/" target="_blank">Stamen Design</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>',
      maxZoom: 20,
      minZoom: 1,
    }
  );

  // CartoDB Dark Matter (backup - no API key in basic usage)
  const cartoDark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 20,
  });

  // Try Stadia first, fallback to CartoDB if error
  darkTile.on('tileerror', () => {
    if (!mapInstance.hasLayer(cartoDark)) {
      mapInstance.removeLayer(darkTile);
      cartoDark.addTo(mapInstance);
    }
  });

  darkTile.addTo(mapInstance);

  // Custom zoom control position
  L.control.zoom({ position: 'bottomleft' }).addTo(mapInstance);

  // Render all location markers on initial load
  LOCATIONS.forEach(loc => placeLocationMarker(loc, null, false));

  // Map click handler for custom departure point
  mapInstance.on('click', (e) => {
    if (isClickingMap) {
      setCustomDeparture(e.latlng.lat, e.latlng.lng);
    }
  });

  return mapInstance;
}

// ===== CUSTOM LOCATION MARKER =====
function placeLocationMarker(loc, score = null, highlight = false) {
  // Remove existing marker if any
  if (locationMarkers[loc.id]) {
    mapInstance.removeLayer(locationMarkers[loc.id]);
  }

  const scoreLabel = score !== null ? score.toFixed(3) : '';
  const riskColor = { low: '#10B981', medium: '#F59E0B', high: '#EF4444' };
  const borderColor = highlight ? '#FF6B4A' : '#00F0FF';
  const riskC = riskColor[loc.weather.risk] || '#10B981';

  const iconHtml = `
    <div class="custom-marker marker-enter">
      <div class="marker-badge ${highlight ? 'highlight' : ''}" style="border-color:${borderColor}">
        <div class="marker-ripple" style="background:rgba(${highlight?'255,107,74':'0,240,255'},0.1)"></div>
        <span class="marker-icon">${loc.emoji}</span>
        ${scoreLabel ? `<span class="marker-score" style="color:${borderColor}">${scoreLabel}</span>` : ''}
        <div style="width:6px;height:6px;border-radius:50%;background:${riskC};margin-top:2px;"></div>
      </div>
    </div>
  `;

  const icon = L.divIcon({
    html: iconHtml,
    className: '',
    iconSize: [60, 64],
    iconAnchor: [30, 64],
    popupAnchor: [0, -68],
  });

  const marker = L.marker(loc.coords, { icon })
    .addTo(mapInstance)
    .bindPopup(buildPopupHtml(loc, score), {
      maxWidth: 260,
      className: 'custom-popup',
    });

  marker.on('click', () => {
    if (!isClickingMap) showLocationDetail(loc, score);
  });

  locationMarkers[loc.id] = marker;
  return marker;
}

function buildPopupHtml(loc, score) {
  const riskLabel = { low: 'Rendah', medium: 'Sedang', high: 'Tinggi' };
  const riskClass = loc.weather.risk;
  const subjectNames = loc.subjects.map(sid => {
    const s = SUBJECTS.find(s => s.id === sid);
    return s ? `${s.emoji} ${s.label}` : sid;
  });

  return `
    <div class="popup-content">
      <div class="popup-header">
        <div class="popup-name">${loc.emoji} ${loc.name}</div>
        ${score !== null ? `<div><div class="popup-score">${score.toFixed(3)}</div><div class="popup-score-label">Skor SAW</div></div>` : ''}
      </div>
      <div class="popup-tags">
        ${subjectNames.map(n => `<span class="popup-tag">${n}</span>`).join('')}
      </div>
      <div class="popup-meta">
        📍 ${loc.coords[0].toFixed(5)}, ${loc.coords[1].toFixed(5)}<br>
        🌊 Gelombang: ${loc.weather.wave_height_m}m &nbsp;|&nbsp;
        <span class="risk-badge ${riskClass}" style="font-size:0.6rem">⚠️ ${riskLabel[riskClass]}</span>
      </div>
      <button class="popup-btn" onclick="showLocationDetail(window.LOCATIONS.find(l=>l.id==='${loc.id}'), ${score})">
        🔍 Lihat Detail Lokasi
      </button>
    </div>
  `;
}

// ===== DEPARTURE MARKER =====
function setDepartureMarker(lat, lng) {
  if (departureMarker) mapInstance.removeLayer(departureMarker);

  const icon = L.divIcon({
    html: `<div class="departure-marker">🏫</div>`,
    className: '',
    iconSize: [36, 36],
    iconAnchor: [18, 18],
  });

  departureMarker = L.marker([lat, lng], { icon })
    .addTo(mapInstance)
    .bindPopup('<div class="popup-content"><div class="popup-name">🏫 Titik Keberangkatan</div></div>');
}

function removeDepartureMarker() {
  if (departureMarker) {
    mapInstance.removeLayer(departureMarker);
    departureMarker = null;
  }
}

// ===== ROUTE POLYLINES (Animated Dashed) =====
function clearRoutePolylines() {
  routePolylines.forEach(pl => mapInstance.removeLayer(pl));
  routePolylines = [];
}

function drawRouteBetween(locA, locB, color = '#FF6B4A') {
  // Shadow line
  const shadow = L.polyline([locA.coords, locB.coords], {
    color: color,
    weight: 4,
    opacity: 0.15,
    lineCap: 'round',
  }).addTo(mapInstance);

  // Main dashed animated line
  const line = L.polyline([locA.coords, locB.coords], {
    color: color,
    weight: 2.5,
    opacity: 0.85,
    dashArray: '10, 8',
    lineCap: 'round',
    className: 'animated-route',
  }).addTo(mapInstance);

  routePolylines.push(shadow, line);

  // Add distance label at midpoint
  const midLat = (locA.coords[0] + locB.coords[0]) / 2;
  const midLon = (locA.coords[1] + locB.coords[1]) / 2;
  const distM = haversineDistance(locA.coords[0], locA.coords[1], locB.coords[0], locB.coords[1]);
  const travel = estimateTravelTime(distM);

  const distMarker = L.marker([midLat, midLon], {
    icon: L.divIcon({
      html: `
        <div style="
          background:rgba(7,19,32,0.9);
          border:1px solid ${color};
          border-radius:8px;
          padding:4px 8px;
          font-family:JetBrains Mono,monospace;
          font-size:0.6rem;
          color:${color};
          white-space:nowrap;
          box-shadow:0 2px 8px rgba(0,0,0,0.4);
          backdrop-filter:blur(8px);
        ">
          📏 ${formatDistance(distM)}<br>
          🚗 ${travel.drive} · 🚶 ${travel.walk}
        </div>
      `,
      className: '',
      iconAnchor: [60, 20],
    }),
    interactive: false,
  }).addTo(mapInstance);

  routePolylines.push(distMarker);
}

function drawMultiStopRoutes(stops) {
  clearRoutePolylines();
  const colors = ['#FF6B4A', '#F59E0B', '#A78BFA'];
  for (let i = 0; i < stops.length - 1; i++) {
    drawRouteBetween(stops[i].locData.loc, stops[i + 1].locData.loc, colors[i % colors.length]);
  }
}

function drawDepartureRoutes(depCoords, stops) {
  if (!depCoords) return;
  const depLoc = { coords: depCoords };
  // Draw dashed line from departure to first stop
  const firstStop = stops[0].locData.loc;
  const depLine = L.polyline([depCoords, firstStop.coords], {
    color: '#10B981',
    weight: 2,
    opacity: 0.5,
    dashArray: '5, 10',
    lineCap: 'round',
  }).addTo(mapInstance);
  routePolylines.push(depLine);
}

// ===== HIGHLIGHT RESULT MARKERS =====
function highlightAnalysisResults(analysisResult, departureCoords) {
  // Reset all markers
  LOCATIONS.forEach(loc => placeLocationMarker(loc, null, false));
  clearRoutePolylines();

  if (!analysisResult) return;
  currentAnalysisResult = analysisResult;

  const { result, ranked } = analysisResult;

  if (result.type === 'single') {
    // Scenario 1 or single subject: highlight ranked locations
    result.topResults.forEach((row, i) => {
      placeLocationMarker(row.loc, row.V, i === 0);
    });
    // Fly to top result
    if (result.topResults.length > 0) {
      mapInstance.flyTo(result.topResults[0].loc.coords, 15, { duration: 1.5 });
    }
  } else if (result.type === 'multi') {
    // Scenario 2: highlight stop locations & draw routes
    result.stops.forEach((stop, i) => {
      placeLocationMarker(stop.locData.loc, stop.locData.V, true);
    });
    drawMultiStopRoutes(result.stops);
    if (departureCoords) drawDepartureRoutes(departureCoords, result.stops);

    // Fly to bounds of all stops
    const bounds = result.stops.map(s => s.locData.loc.coords);
    if (bounds.length > 1) {
      mapInstance.flyToBounds(bounds, { padding: [80, 80], duration: 1.5 });
    } else if (bounds.length === 1) {
      mapInstance.flyTo(bounds[0], 15, { duration: 1.5 });
    }
  }
}

// ===== FLY TO LOCATION =====
function flyToLocation(locId) {
  const loc = LOCATIONS.find(l => l.id === locId);
  if (loc && mapInstance) {
    mapInstance.flyTo(loc.coords, 16, { duration: 1.2 });
    setTimeout(() => {
      if (locationMarkers[locId]) locationMarkers[locId].openPopup();
    }, 1300);
  }
}

function flyToBoundsOfStops(stops) {
  if (!stops || stops.length === 0) return;
  const bounds = stops.map(s => s.locData.loc.coords);
  mapInstance.flyToBounds(bounds, { padding: [80, 80], duration: 1.2 });
}

// ===== CUSTOM DEPARTURE =====
function setCustomDeparture(lat, lng) {
  isClickingMap = false;
  document.getElementById('click-map-hint').style.display = 'none';
  document.getElementById('custom-coord-display').classList.remove('hidden');
  document.getElementById('custom-coord-text').textContent = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
  setDepartureMarker(lat, lng);
  window.customDepartureCoords = [lat, lng];
}

function clearCustomCoord() {
  document.getElementById('departure-select').value = '';
  document.getElementById('custom-coord-display').classList.add('hidden');
  document.getElementById('click-map-hint').style.display = 'none';
  removeDepartureMarker();
  window.customDepartureCoords = null;
  isClickingMap = false;
}

// Inject animated SVG route style
function injectRouteCSSAnimation() {
  const style = document.createElement('style');
  style.textContent = `
    .animated-route {
      animation: dash-flow 0.5s linear infinite;
    }
    @keyframes dash-flow {
      0% { stroke-dashoffset: 18; }
      100% { stroke-dashoffset: 0; }
    }
  `;
  document.head.appendChild(style);
}

window.initMap = initMap;
window.placeLocationMarker = placeLocationMarker;
window.setDepartureMarker = setDepartureMarker;
window.removeDepartureMarker = removeDepartureMarker;
window.clearRoutePolylines = clearRoutePolylines;
window.drawRouteBetween = drawRouteBetween;
window.drawMultiStopRoutes = drawMultiStopRoutes;
window.highlightAnalysisResults = highlightAnalysisResults;
window.flyToLocation = flyToLocation;
window.flyToBoundsOfStops = flyToBoundsOfStops;
window.setCustomDeparture = setCustomDeparture;
window.clearCustomCoord = clearCustomCoord;
window.injectRouteCSSAnimation = injectRouteCSSAnimation;
