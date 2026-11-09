/**
 * PESISIR-EDU | data.js
 * Seed data: lokasi pesisir Kota Bengkulu, mata pelajaran, sekolah
 */

// ===== MATA PELAJARAN =====
const SUBJECTS = [
  { id: 'biologi',   label: 'Biologi',   emoji: '🌿', desc: 'Ekosistem Laut & Mangrove',     color: '#10B981' },
  { id: 'geografi',  label: 'Geografi',  emoji: '🗺️', desc: 'Geomorfologi Pesisir',          color: '#00F0FF' },
  { id: 'sejarah',   label: 'Sejarah',   emoji: '🏛️', desc: 'Kolonial & Arkeologi Pesisir', color: '#A78BFA' },
  { id: 'fisika',    label: 'Fisika',    emoji: '⚡', desc: 'Gelombang & Energi Laut',       color: '#F59E0B' },
  { id: 'ekonomi',   label: 'Ekonomi',   emoji: '💹', desc: 'UMKM & Ekonomi Pesisir',        color: '#34D399' },
  { id: 'sosiologi', label: 'Sosiologi', emoji: '👥', desc: 'Masyarakat Nelayan',             color: '#60A5FA' },
  { id: 'senibudaya',label: 'Seni Budaya',emoji: '🎨',desc: 'Budaya & Kesenian Pesisir',     color: '#F472B6' },
];

// ===== LOKASI PESISIR KOTA BENGKULU =====
// Koordinat GPS riil, diverifikasi terhadap kawasan pesisir Kota Bengkulu
const LOCATIONS = [
  {
    id: 'pantai-panjang-sport',
    name: 'Pantai Panjang (Zona Sport Center)',
    shortName: 'Pantai Panjang',
    zone: 'Kec. Kampung Melayu / Selebar',
    coords: [-3.8423, 102.2698],
    emoji: '🏖️',
    subjects: ['geografi', 'ekonomi', 'sosiologi'],
    description: 'Pantai Panjang adalah pantai terpanjang di Bengkulu (~7 km) dengan hamparan pasir putih dan ekosistem pesisir yang kaya. Zona Sport Center menjadi pusat aktivitas wisata dan ekonomi lokal. Ideal untuk studi geomorfologi pesisir, dinamika pantai, dan ekonomi UMKM pesisir.',
    // Raw criteria scores (before normalization by distance)
    c1_relevance: 4.5,   // Relevansi mapel (benefit)
    c3_eduAttract: 4.2,  // Daya tarik edukasi & fasilitas (benefit)
    c4_safety: 3.8,      // Aksesibilitas & keamanan (benefit)
    facilities: ['Toilet Umum', 'Tempat Parkir', 'Warung Makan', 'Tempat Berteduh', 'Penginapan'],
    weather: {
      condition: 'Cerah Berawan',
      wind_speed_knots: 8,
      wind_dir: 'SW',
      wave_height_m: 0.8,
      risk: 'low',
    },
  },
  {
    id: 'mangrove-jenggalu',
    name: 'Hutan Mangrove Muara Jenggalu',
    shortName: 'Mangrove Jenggalu',
    zone: 'Kawasan Pelabuhan Pulau Baai',
    coords: [-3.9012, 102.3187],
    emoji: '🌳',
    subjects: ['biologi', 'geografi'],
    description: 'Kawasan hutan mangrove di muara sungai Jenggalu dekat Pelabuhan Pulau Baai. Ekosistem mangrove yang masih terjaga dengan keanekaragaman hayati pesisir. Sangat ideal untuk pengamatan flora mangrove, fauna air payau, dan studi geomorfologi muara.',
    c1_relevance: 5.0,
    c3_eduAttract: 4.8,
    c4_safety: 3.5,
    facilities: ['Jembatan Kayu', 'Papan Interpretasi', 'Area Pengamatan'],
    weather: {
      condition: 'Berawan',
      wind_speed_knots: 6,
      wind_dir: 'S',
      wave_height_m: 0.5,
      risk: 'low',
    },
  },
  {
    id: 'benteng-marlborough',
    name: 'Benteng Marlborough (Kawasan Tapak Paderi)',
    shortName: 'Benteng Marlborough',
    zone: 'Kel. Kebun Ros, Kec. Teluk Segara',
    coords: [-3.7831, 102.2554],
    emoji: '🏰',
    subjects: ['sejarah', 'senibudaya', 'sosiologi'],
    description: 'Benteng Marlborough dibangun Inggris (1713-1719) dan merupakan peninggalan kolonial terbesar di Bengkulu. Berlokasi di tepi pesisir Teluk Bengkulu. Sangat kaya nilai sejarah kolonial, arsitektur pertahanan, dan jejak interaksi budaya Anglo-Melayu.',
    c1_relevance: 5.0,
    c3_eduAttract: 4.9,
    c4_safety: 4.5,
    facilities: ['Museum', 'Pemandu Wisata', 'Toilet', 'Area Parkir', 'Kantin'],
    weather: {
      condition: 'Cerah',
      wind_speed_knots: 10,
      wind_dir: 'NW',
      wave_height_m: 1.2,
      risk: 'medium',
    },
  },
  {
    id: 'tapak-paderi-pondok-besi',
    name: 'Pantai Tapak Paderi & Pondok Besi',
    shortName: 'Tapak Paderi',
    zone: 'Kel. Malabero, Kec. Teluk Segara',
    coords: [-3.7898, 102.2503],
    emoji: '⚓',
    subjects: ['sejarah', 'fisika', 'geografi'],
    description: 'Kawasan pesisir bersejarah tempat pendaratan awal kolonial. Pantai berbatu dengan fenomena gelombang yang terukur. Ideal untuk observasi fisika gelombang laut, sejarah maritim, dan kajian geomorfologi tebing pantai. Berdekatan dengan Benteng Marlborough.',
    c1_relevance: 4.8,
    c3_eduAttract: 4.0,
    c4_safety: 3.2,
    facilities: ['Area Observasi', 'Jalan Pejalan Kaki', 'Warung'],
    weather: {
      condition: 'Cerah Berawan',
      wind_speed_knots: 12,
      wind_dir: 'NW',
      wave_height_m: 1.5,
      risk: 'medium',
    },
  },
  {
    id: 'tpi-pulau-baai',
    name: 'Kampung Nelayan & TPI Pulau Baai',
    shortName: 'TPI Pulau Baai',
    zone: 'Kec. Kampung Melayu (Selatan)',
    coords: [-3.8867, 102.3098],
    emoji: '🐟',
    subjects: ['ekonomi', 'sosiologi', 'biologi'],
    description: 'Tempat Pelelangan Ikan (TPI) Pulau Baai dan kampung nelayan tradisional. Pusat aktivitas perikanan tangkap Kota Bengkulu. Ideal untuk studi ekonomi perikanan, sosiologi masyarakat nelayan, dan kajian biota laut hasil tangkapan.',
    c1_relevance: 4.7,
    c3_eduAttract: 3.9,
    c4_safety: 4.0,
    facilities: ['TPI', 'Dermaga', 'Warung Nelayan', 'Area Parkir'],
    weather: {
      condition: 'Cerah',
      wind_speed_knots: 7,
      wind_dir: 'SE',
      wave_height_m: 0.6,
      risk: 'low',
    },
  },
  {
    id: 'pantai-jakat',
    name: 'Pantai Jakat',
    shortName: 'Pantai Jakat',
    zone: 'Kel. Jakat, Kec. Ratu Samban',
    coords: [-3.8012, 102.2534],
    emoji: '🌊',
    subjects: ['fisika', 'ekonomi', 'sosiologi'],
    description: 'Pantai perkotaan di tengah Kota Bengkulu dengan gelombang moderat. Terdapat aktivitas UMKM (kuliner pesisir, kerajinan). Ideal untuk demonstrasi fisika gelombang, studi ekonomi pesisir perkotaan, dan sosiologi urban coastal community.',
    c1_relevance: 4.3,
    c3_eduAttract: 3.8,
    c4_safety: 3.5,
    facilities: ['Jogging Track', 'Warung Kuliner', 'Toilet Umum', 'Parkir'],
    weather: {
      condition: 'Berawan',
      wind_speed_knots: 9,
      wind_dir: 'W',
      wave_height_m: 1.1,
      risk: 'medium',
    },
  },
  {
    id: 'sungai-suci-abrasi',
    name: 'Kawasan Konservasi Penyu & Abrasi Sungai Suci',
    shortName: 'Konservasi Sungai Suci',
    zone: 'Pantai Sungai Suci / Pasar Bengkulu',
    coords: [-3.7634, 102.2401],
    emoji: '🐢',
    subjects: ['biologi', 'fisika', 'geografi'],
    description: 'Lokasi konservasi penyu belimbing dan area observasi abrasi pantai aktif. Fenomena fisika erosi gelombang yang dramatis. Sangat kaya nilai biologi konservasi, fisika dinamika sedimen, dan kajian geografi pesisir tentang perubahan garis pantai.',
    c1_relevance: 4.9,
    c3_eduAttract: 4.6,
    c4_safety: 2.8,
    facilities: ['Pusat Konservasi', 'Papan Interpretasi', 'Area Penelitian'],
    weather: {
      condition: 'Berawan Tebal',
      wind_speed_knots: 14,
      wind_dir: 'SW',
      wave_height_m: 2.1,
      risk: 'high',
    },
  },
  {
    id: 'pantai-zakat-kampung-cina',
    name: 'Pantai Zakat & Kampung Cina Pesisir',
    shortName: 'Pantai Zakat / Pecinan',
    zone: 'Kel. Berkas, Kec. Teluk Segara',
    coords: [-3.7858, 102.2531],
    emoji: '🏮',
    subjects: ['sejarah', 'ekonomi', 'senibudaya'],
    description: 'Kawasan pecinan pesisir bersejarah dekat Marlborough dengan arsitektur kolonial Tionghoa. Pantai Zakat menjadi pusat perdagangan pesisir historis. Ideal untuk studi sejarah jalur perdagangan maritim, etnografi budaya peranakan, dan ekonomi kawasan pelabuhan kuno.',
    c1_relevance: 4.6,
    c3_eduAttract: 4.3,
    c4_safety: 4.1,
    facilities: ['Toko Budaya', 'Klenteng', 'Warung Peranakan', 'Jalan Kota Tua'],
    weather: {
      condition: 'Cerah',
      wind_speed_knots: 8,
      wind_dir: 'NW',
      wave_height_m: 1.0,
      risk: 'low',
    },
  },
];

// Subject-to-location mapping (which locations cover which subjects, and their relevance score)
// This allows quick lookup for Scenario 1 & 2 logic
function getLocationsBySubject(subjectId) {
  return LOCATIONS.filter(loc => loc.subjects.includes(subjectId));
}

function getLocationsForAllSubjects(subjectIds) {
  if (!subjectIds || subjectIds.length === 0) return [];
  return LOCATIONS.filter(loc => subjectIds.every(sid => loc.subjects.includes(sid)));
}

// Export
window.SUBJECTS = SUBJECTS;
window.LOCATIONS = LOCATIONS;
window.getLocationsBySubject = getLocationsBySubject;
window.getLocationsForAllSubjects = getLocationsForAllSubjects;
