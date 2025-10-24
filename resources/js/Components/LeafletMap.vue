<script setup>
// 1. Remove 'h' and 'createApp'.
import { onMounted, onUnmounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// 2. Remove the MapPopupCard import
// import MapPopupCard from '@/Components/MapPopupCard.vue'; // <-- DELETE THIS

const props = defineProps({
    concerts: {
        type: Array,
        default: () => []
    }
});

// 3. Define the event we are going to emit
const emit = defineEmits(['marker-click']);

const mapContainer = ref(null);
let map = null;
let markerLayer = null;

// --- Custom Icon Definitions (No changes here) ---
const createIconHtml = (color) => {
    return `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="${color}" width="32px" height="32px" style="filter: drop-shadow(0 2px 3px rgba(0,0,0,0.3));">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" stroke="white" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
        </svg>
    `;
};
const primaryColor = 'var(--color-primary, #3B82F6)';
const secondaryColor = 'var(--color-secondary-high, #F97316)';
const primaryIcon = L.divIcon({
    html: createIconHtml(primaryColor),
    className: '',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -34]
});
const secondaryIcon = L.divIcon({
    html: createIconHtml(secondaryColor),
    className: '',
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -34]
});
// --- End Icon Definitions ---

// --- Marker Update Function ---
const updateMarkers = (concerts) => {
    if (!map || !markerLayer) {
        return;
    }
    markerLayer.clearLayers();
    
    concerts.forEach(concert => {
        if (concert.latitude && concert.longitude) {
            
            let icon;
            if (concert.admin_id !== null) {
                icon = primaryIcon;
            } else if (concert.promoter_id !== null) {
                icon = secondaryIcon;
            } else {
                icon = new L.Icon.Default();
            }

            // --- 4. THIS IS THE MAJOR CHANGE ---
            // We no longer bind a popup.
            // We add a 'click' event listener that emits our custom event.
            L.marker([concert.latitude, concert.longitude], { icon: icon })
                .addTo(markerLayer)
                .on('click', () => {
                    // Emit the event up to the parent (Index.vue)
                    emit('marker-click', concert);
                });

        }
    });
};

// --- Map Initialization (No changes) ---
onMounted(() => {
    if (mapContainer.value) {
        map = L.map(mapContainer.value).setView([13.0, 101.55], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            className: 'map-tiles'
        }).addTo(map);
        markerLayer = L.layerGroup().addTo(map);
        updateMarkers(props.concerts);
    }
});

// --- Watcher (No changes) ---
watch(() => props.concerts, (newConcerts) => {
    updateMarkers(newConcerts);
});

// --- Unmount (No changes) ---
onUnmounted(() => {
    if (map) {
        map.remove();
        map = null;
    }
});
</script>

<template>
    <div ref="mapContainer" class="h-full w-full z-10"></div>
</template>