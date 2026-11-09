/**
 * PESISIR-EDU | haversine.js
 * Haversine formula for geodetic distance calculation
 */

const EARTH_RADIUS_M = 6371000; // meters

/**
 * Calculate Haversine distance between two points
 * @param {number} lat1 - Latitude of point A (degrees)
 * @param {number} lon1 - Longitude of point A (degrees)
 * @param {number} lat2 - Latitude of point B (degrees)
 * @param {number} lon2 - Longitude of point B (degrees)
 * @returns {number} Distance in meters
 */
function haversineDistance(lat1, lon1, lat2, lon2) {
  const toRad = (deg) => (deg * Math.PI) / 180;
  const dLat = toRad(lat2 - lat1);
  const dLon = toRad(lon2 - lon1);
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return EARTH_RADIUS_M * c;
}

/**
 * Format distance for display
 * @param {number} meters
 * @returns {string}
 */
function formatDistance(meters) {
  if (meters < 1000) {
    return `${Math.round(meters)} m`;
  }
  return `${(meters / 1000).toFixed(2)} km`;
}

/**
 * Estimate travel time (walking & driving)
 * @param {number} meters
 * @returns {{ walk: string, drive: string }}
 */
function estimateTravelTime(meters) {
  const walkSpeedMps = 1.2;  // ~4.3 km/h
  const driveSpeedMps = 6.9; // ~25 km/h (urban coastal road)
  const walkSec = meters / walkSpeedMps;
  const driveSec = meters / driveSpeedMps;

  const fmt = (sec) => {
    const min = Math.round(sec / 60);
    if (min < 1) return '<1 menit';
    if (min >= 60) return `${Math.floor(min / 60)} jam ${min % 60} menit`;
    return `${min} menit`;
  };

  return {
    walk: fmt(walkSec),
    drive: fmt(driveSec),
  };
}

/**
 * Find all pairs of locations and compute inter-distances
 * Used for Scenario 2 multi-location proximity
 * @param {Array} locations - array of location objects
 * @returns {Array} sorted array of {locA, locB, distance}
 */
function computeAllPairDistances(locations) {
  const pairs = [];
  for (let i = 0; i < locations.length; i++) {
    for (let j = i + 1; j < locations.length; j++) {
      const locA = locations[i];
      const locB = locations[j];
      const dist = haversineDistance(
        locA.coords[0], locA.coords[1],
        locB.coords[0], locB.coords[1]
      );
      pairs.push({ locA, locB, distance: dist });
    }
  }
  return pairs.sort((a, b) => a.distance - b.distance);
}

/**
 * Centroid of multiple lat/lon points
 * @param {Array<[number,number]>} coords
 * @returns {[number, number]}
 */
function centroid(coords) {
  const sum = coords.reduce((acc, c) => [acc[0] + c[0], acc[1] + c[1]], [0, 0]);
  return [sum[0] / coords.length, sum[1] / coords.length];
}

window.haversineDistance = haversineDistance;
window.formatDistance = formatDistance;
window.estimateTravelTime = estimateTravelTime;
window.computeAllPairDistances = computeAllPairDistances;
window.centroid = centroid;
