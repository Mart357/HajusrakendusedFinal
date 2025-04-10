<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { onMounted, ref } from 'vue';
import Radar from 'radar-sdk-js';
import 'radar-sdk-js/dist/radar.css';
import { MapMouseEvent, Marker, Popup } from 'maplibre-gl';
import axios from 'axios';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

defineProps(["weather"]);

const mapMouseEv = ref<MapMouseEvent>();
const popup = ref<Popup | null>(null);
const markers = ref<{ id: number; title: string; description: string; lng: number; lat: number }[]>([]);
const filter = ref('');

let map: any;

Radar.initialize('prj_live_pk_5f8b91bd6f08ed5c08499ef10299181e002edcea');

// Markerite laadimine serverist ja kaardile lisamine
const loadMarkers = async () => {
    try {
        const response = await axios.get('/api/markers', {
            params: { search: filter.value }
        });
        markers.value = response.data;

        // Eemalda kõik olemasolevad markerid
        document.querySelectorAll('.maplibregl-marker').forEach(el => el.remove());

        markers.value.forEach((marker) => {
            const newMarker = new Marker()
                .setLngLat([marker.lng, marker.lat])
                .setPopup(
                    new Popup().setHTML(`
                        <div style="min-width: 200px;">
                            <h3>${marker.title}</h3>
                            <p>${marker.description}</p>
                            <button onclick="window.editMarker(${marker.id})">Muuda</button>
                            <button onclick="window.deleteMarker(${marker.id})" style="color: red;">Kustuta</button>
                        </div>
                    `)
                )
                .addTo(map);
        });
    } catch (error) {
        console.error('Error loading markers:', error);
    }
};

onMounted(() => {
    map = Radar.ui.map({
        container: 'map',
        style: 'radar-default-v1',
        center: [22.488819593503443, 58.25398239259826],
        zoom: 14,
    });

    loadMarkers();

    map.on('click', (e: MapMouseEvent) => {  // Tüüp parandatud
        mapMouseEv.value = e;
        if (popup.value) popup.value.remove();

        popup.value = new Popup({ closeOnClick: true })
            .setLngLat(e.lngLat)
            .setHTML(`
                <div style="min-width: 200px;">
                    <h3 style="margin: 0; font-size: 16px;">Add Marker</h3>
                    <p>Longitude: ${e.lngLat.lng.toFixed(6)}</p>
                    <p>Latitude: ${e.lngLat.lat.toFixed(6)}</p>
                    <label for="markerTitle">Title:</label>
                    <input id="markerTitle" type="text" placeholder="Enter title" style="width: 100%; margin-bottom: 5px;" />
                    <label for="markerDescription">Description:</label>
                    <textarea id="markerDescription" placeholder="Enter description" style="width: 100%; margin-bottom: 5px;"></textarea>
                    <button id="saveMarkerButton" style="padding: 5px 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Save Marker
                    </button>
                </div>
            `)
            .addTo(map);

        setTimeout(() => {
            const saveMarkerButton = document.getElementById('saveMarkerButton');
            if (saveMarkerButton) {
                saveMarkerButton.addEventListener('click', async () => {
                    const markerTitle = (document.getElementById('markerTitle') as HTMLInputElement)?.value;
                    const markerDescription = (document.getElementById('markerDescription') as HTMLTextAreaElement)?.value;

                    if (markerTitle && markerDescription) {
                        try {
                            const response = await axios.post('/api/markers', {
                                title: markerTitle,
                                description: markerDescription,
                                longitude: e.lngLat.lng,
                                latitude: e.lngLat.lat,
                            });

                            markers.value.push(response.data);
                            loadMarkers(); // Uuenda kaarti
                            alert('Marker saved successfully!');
                        } catch (error) {
                            console.error('Error saving marker:', error);
                        }

                        popup.value?.remove();
                    } else {
                        alert('Please fill in both title and description.');
                    }
                });
            }
        }, 0);
    });
});

// Muutmise funktsioon
window.editMarker = async (id: number) => {
    const marker = markers.value.find(m => m.id === id);
    if (!marker) return;

    const newTitle = prompt('Uus pealkiri:', marker.title);
    const newDesc = prompt('Uus kirjeldus:', marker.description);
    if (!newTitle || !newDesc) return;

    try {
        await axios.put(`/api/markers/${id}`, {
            title: newTitle,
            description: newDesc,
        });
        alert('Marker uuendatud');
        loadMarkers();
    } catch (e) {
        console.error('Update error:', e);
    }
};

// Kustutamise funktsioon
window.deleteMarker = async (id: number) => {
    if (!confirm('Kas oled kindel, et soovid markeri kustutada?')) return;

    try {
        await axios.delete(`/api/markers/${id}`);
        alert('Marker kustutatud');
        loadMarkers();
    } catch (e) {
        console.error('Delete error:', e);
    }
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="flex h-16 flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium"> Weather </CardTitle>
                        <img :src="'http://openweathermap.org/img/wn/' + weather.weather[0].icon + '@2x.png'" alt="Weather icon" class="h-12 w-12" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ weather.main.temp }}°C</div>
                        <p class="text-xs text-muted-foreground">{{ weather.wind.speed }} m/s ({{ weather.weather[0].description }})</p>
                    </CardContent>
                </Card>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>

            <!-- Filtriväli -->
            <input v-model="filter" @input="loadMarkers" type="text" placeholder="Otsi pealkirja järgi..." class="border p-2 rounded w-full max-w-md mt-4" />

            <!-- Kaart -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div id="map" style="width: 100%; height: 100%;" />
            </div>
        </div>
    </AppLayout>
</template>
