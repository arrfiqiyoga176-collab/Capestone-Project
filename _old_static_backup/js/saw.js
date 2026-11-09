/**
 * PESISIR-EDU | saw.js
 * Simple Additive Weighting (SAW) engine
 * Supports Scenario 1 (single location) & Scenario 2 (multi-location proximity)
 */

/**
 * Run complete SAW analysis
 * @param {Array<string>} selectedSubjects - IDs of selected subjects
 * @param {[number,number]} departureCoords - [lat, lon] of departure point
 * @param {Object} weights - { w1, w2, w3, w4 } sum must equal 100
 * @param {number} proximityRadiusM - maximum inter-location distance for Scenario 2 (meters)
 * @returns {Object} analysis result
 */
function runSAWAnalysis(selectedSubjects, departureCoords, weights, proximityRadiusM) {
  // Normalize weights to fractions
  const totalW = weights.w1 + weights.w2 + weights.w3 + weights.w4;
  const W = {
    w1: weights.w1 / totalW,
    w2: weights.w2 / totalW,
    w3: weights.w3 / totalW,
    w4: weights.w4 / totalW,
  };

  // ----- STEP 1: Compute C2 (distance) for all locations -----
  const locsWithDist = LOCATIONS.map(loc => {
    const distM = departureCoords
      ? haversineDistance(departureCoords[0], departureCoords[1], loc.coords[0], loc.coords[1])
      : 0;
    return { ...loc, distM };
  });

  // ----- STEP 2: Build decision matrix -----
  // C1: relevance score based on subject match (0-5)
  // C2: distance in km (cost — lower is better)
  // C3: edu attraction (0-5, given)
  // C4: safety score (0-5, given; adjusted for weather risk)
  const decisionMatrix = locsWithDist.map(loc => {
    const c1 = computeRelevanceScore(loc, selectedSubjects);
    const c2 = loc.distM / 1000; // km
    const c3 = loc.c3_eduAttract;
    const c4 = adjustSafetyForWeather(loc.c4_safety, loc.weather);
    return { loc, c1, c2, c3, c4 };
  });

  // ----- STEP 3: Normalize matrix -----
  const maxC1 = Math.max(...decisionMatrix.map(r => r.c1));
  const minC2 = Math.min(...decisionMatrix.map(r => r.c2));
  const maxC3 = Math.max(...decisionMatrix.map(r => r.c3));
  const maxC4 = Math.max(...decisionMatrix.map(r => r.c4));

  const normalizedMatrix = decisionMatrix.map(row => {
    const r1 = maxC1 > 0 ? row.c1 / maxC1 : 0;                          // benefit
    const r2 = row.c2 > 0 ? minC2 / row.c2 : 1;                         // cost
    const r3 = maxC3 > 0 ? row.c3 / maxC3 : 0;                          // benefit
    const r4 = maxC4 > 0 ? row.c4 / maxC4 : 0;                          // benefit
    return { ...row, r1, r2, r3, r4 };
  });

  // ----- STEP 4: Compute preference scores -----
  const scored = normalizedMatrix.map(row => {
    const V = W.w1 * row.r1 + W.w2 * row.r2 + W.w3 * row.r3 + W.w4 * row.r4;
    return { ...row, V };
  });

  // Sort by score descending
  const ranked = [...scored].sort((a, b) => b.V - a.V);

  // ----- STEP 5: Scenario determination -----
  let scenario, result;

  if (selectedSubjects.length <= 1) {
    // Single subject: just return ranked list
    scenario = 'single';
    result = buildSingleResult(ranked, selectedSubjects, W, decisionMatrix, normalizedMatrix);
  } else {
    // Check Scenario 1: any location covering ALL subjects?
    const fullyMatchedLocs = ranked.filter(row =>
      selectedSubjects.every(sid => row.loc.subjects.includes(sid))
    );

    if (fullyMatchedLocs.length > 0) {
      // Scenario 1: single optimal location found
      scenario = 'scenario1';
      result = buildSingleResult(ranked, selectedSubjects, W, decisionMatrix, normalizedMatrix, fullyMatchedLocs);
    } else {
      // Scenario 2: multi-location proximity fallback
      scenario = 'scenario2';
      result = buildMultiResult(ranked, scored, selectedSubjects, W, proximityRadiusM, decisionMatrix, normalizedMatrix);
    }
  }

  return {
    scenario,
    subjects: selectedSubjects,
    weights: W,
    decisionMatrix,
    normalizedMatrix,
    ranked,
    result,
    // For SAW detail modal
    sawDetail: {
      weights: W,
      rawMatrix: decisionMatrix,
      normMatrix: normalizedMatrix,
      ranked,
      maxC1, minC2, maxC3, maxC4,
    }
  };
}

/**
 * Compute relevance score for a location given selected subjects
 */
function computeRelevanceScore(loc, selectedSubjects) {
  if (!selectedSubjects || selectedSubjects.length === 0) return loc.c1_relevance;

  // Count how many selected subjects this location covers
  const matchCount = selectedSubjects.filter(sid => loc.subjects.includes(sid)).length;
  const matchRatio = matchCount / selectedSubjects.length;

  // Weighted combination: base relevance * match ratio, boosted if perfect match
  const base = loc.c1_relevance;
  if (matchCount === 0) return 0;
  if (matchCount === selectedSubjects.length) return base; // perfect match
  return base * matchRatio * 0.8; // partial match penalty
}

/**
 * Adjust safety score based on weather risk
 */
function adjustSafetyForWeather(baseSafety, weather) {
  const riskPenalty = { low: 0, medium: -0.5, high: -1.5 };
  return Math.max(0, baseSafety + (riskPenalty[weather.risk] || 0));
}

/**
 * Build result for single-location scenario
 */
function buildSingleResult(ranked, selectedSubjects, W, decisionMatrix, normalizedMatrix, fullyMatchedLocs) {
  const allRanked = ranked.filter(r => r.V > 0 || selectedSubjects.length === 0);

  return {
    type: 'single',
    mainLocation: fullyMatchedLocs ? fullyMatchedLocs[0] : ranked[0],
    topResults: allRanked.slice(0, 5),
    fullyMatched: fullyMatchedLocs || [],
  };
}

/**
 * Build result for multi-location proximity (Scenario 2)
 * Find best combo of locations where each covers at least one selected subject,
 * and together they cover ALL selected subjects, minimizing inter-location distance
 */
function buildMultiResult(ranked, scored, selectedSubjects, W, proximityRadiusM, decisionMatrix, normalizedMatrix) {
  // For each subject, get the best-scoring location that covers it
  const bestBySubject = {};
  selectedSubjects.forEach(sid => {
    const candidates = scored.filter(row => row.loc.subjects.includes(sid));
    candidates.sort((a, b) => b.V - a.V);
    if (candidates.length > 0) bestBySubject[sid] = candidates[0];
  });

  // Try to find combinations that cover all subjects with minimum total distance
  // Strategy: greedy cluster — start from top-scored universal candidates
  // Group subjects by best location
  const locationGroups = new Map();
  selectedSubjects.forEach(sid => {
    const best = bestBySubject[sid];
    if (!best) return;
    const locId = best.loc.id;
    if (!locationGroups.has(locId)) {
      locationGroups.set(locId, { locData: best, subjects: [] });
    }
    locationGroups.get(locId).subjects.push(sid);
  });

  // Build stop list (unique locations, covering max subjects each)
  let stops = [];
  const coveredSubjects = new Set();

  // Sort groups by SAW score descending, then greedily pick
  const sortedGroups = [...locationGroups.values()].sort((a, b) => b.locData.V - a.locData.V);
  sortedGroups.forEach(grp => {
    const newSubjects = grp.subjects.filter(s => !coveredSubjects.has(s));
    if (newSubjects.length > 0) {
      stops.push({ locData: grp.locData, coveredSubjects: newSubjects });
      newSubjects.forEach(s => coveredSubjects.add(s));
    }
  });

  // Make sure all subjects are covered (fallback: add remaining)
  selectedSubjects.forEach(sid => {
    if (!coveredSubjects.has(sid) && bestBySubject[sid]) {
      stops.push({ locData: bestBySubject[sid], coveredSubjects: [sid] });
      coveredSubjects.add(sid);
    }
  });

  // Limit to max 3 stops for clarity
  stops = stops.slice(0, 3);

  // Compute pairwise distances between stops
  const stopDistances = [];
  for (let i = 0; i < stops.length - 1; i++) {
    const a = stops[i].locData.loc;
    const b = stops[i + 1].locData.loc;
    const dist = haversineDistance(a.coords[0], a.coords[1], b.coords[0], b.coords[1]);
    const travel = estimateTravelTime(dist);
    stopDistances.push({ from: a, to: b, distM: dist, travel });
  }

  // Total combined SAW score (average of all stops)
  const combinedScore = stops.reduce((sum, s) => sum + s.locData.V, 0) / stops.length;
  const totalDist = stopDistances.reduce((sum, d) => sum + d.distM, 0);

  // Also prepare a fallback ranked list (all locations filtered to relevant)
  const relevantRanked = ranked.filter(r => r.V > 0).slice(0, 5);

  return {
    type: 'multi',
    stops,
    stopDistances,
    combinedScore,
    totalDist,
    withinRadius: totalDist <= proximityRadiusM,
    relevantRanked,
  };
}

window.runSAWAnalysis = runSAWAnalysis;
window.computeRelevanceScore = computeRelevanceScore;
window.adjustSafetyForWeather = adjustSafetyForWeather;
