/**
 * PESISIR-EDU | Decoupled Frontend REST API Client
 * Connects to Laravel Backend API running on http://127.0.0.1:8000
 */
const API_BASE_URL = 'http://127.0.0.1:8000/api';

const PesisirApi = {
    baseUrl: API_BASE_URL,

    async checkHealth() {
        try {
            const res = await fetch(`${this.baseUrl}/departure-presets`, { method: 'GET' });
            return res.ok;
        } catch (e) {
            return false;
        }
    },

    async getSubjects() {
        const res = await fetch(`${this.baseUrl}/subjects`);
        if (!res.ok) throw new Error('Gagal mengambil data mata pelajaran dari Backend API');
        const json = await res.json();
        return json.data || json;
    },

    async getDeparturePresets() {
        const res = await fetch(`${this.baseUrl}/departure-presets`);
        if (!res.ok) throw new Error('Gagal mengambil data titik keberangkatan');
        const json = await res.json();
        return json.data || json;
    },

    async getLocations() {
        const res = await fetch(`${this.baseUrl}/locations`);
        if (!res.ok) throw new Error('Gagal mengambil data lokasi pesisir');
        return await res.json();
    },

    async calculate(payload) {
        const res = await fetch(`${this.baseUrl}/calculate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) {
            const errJson = await res.json().catch(() => ({}));
            throw new Error(errJson.message || 'Gagal mengeksekusi kalkulasi SAW di Backend Laravel');
        }

        const json = await res.json();
        return json.data || json;
    },

    async createLocation(locationData) {
        const res = await fetch(`${this.baseUrl}/locations`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(locationData)
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            throw new Error(err.message || 'Gagal menyimpan lokasi ke MySQL');
        }

        return await res.json();
    },

    async deleteLocation(locationId) {
        const res = await fetch(`${this.baseUrl}/locations/${locationId}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) throw new Error('Gagal menghapus lokasi dari MySQL');
        return await res.json();
    }
};

window.PesisirApi = PesisirApi;
