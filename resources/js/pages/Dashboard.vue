<script setup lang="ts">

import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { MapMouseEvent } from 'maplibre-gl';
import Radar from 'radar-sdk-js';
import 'radar-sdk-js/dist/radar.css';
import { RadarMap } from 'radar-sdk-js/dist/ui/RadarMap';
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps({
    weather: {
        type: Object,
        required: true,
    },
    markers: {
        type: Array,
        required: true,
    },
});

// console.log('Markers:', props.markers);

const form = useForm({
    id: null,
    name: '',
    description: '',
    latitude: 0,
    longitude: 0,
});

const show = ref(false);
const map = ref<RadarMap>();

const mapMouseEv = ref<MapMouseEvent>();

Radar.initialize('prj_test_pk_a4a4006fc00a17f9f08ec73995e78a0393b3c22f', {
    /* map config options */
});

onMounted(() => {
    const map = Radar.ui.map({
        container: 'map', // OR document.getElementById('map')
        style: 'radar-default-v1',
        center: [22.488819593503443, 58.25398239259826], // NYC
        zoom: 14,
    });

for (let marker of props.markers) {
    const popupContent = `
        <strong>${marker.name}</strong><br>
        ${marker.description ?? ''}<br>
        <button onclick="window.editMarker(${marker.id})" style='color:blue'>Muuda</button>
        <button onclick="window.deleteMarker(${marker.id})" style='color:red'>Kustuta</button>
    `;
    Radar.ui
        .marker({
            color: '#000257',
            width: 40,
            height: 80,
            popup: { html: popupContent },
        })
        .setLngLat([marker.longitude, marker.latitude])
        .addTo(map);
}
    // Radar.ui.marker({
    //   color: '#000257',
    //   width: 40,
    //   height: 80,
    //   popup: {
    //     text: 'My popup.',
    //   }
    // })
    //     .setLngLat([-73.990550, 40.735225])
    //     .addTo(map);

    map.on('click', (e) => {
        // mapMouseEv.value = e;
        form.latitude = e.lngLat.lat;
        form.longitude = e.lngLat.lng;
        show.value = true;
    });
});

function handleSubmit() {
    if (form.id) {
        form.put(route('markers.update', form.id), {
            onSuccess: () => {
                show.value = false;
                form.id = null;
                form.name = '';
                form.description = '';
            }
        });
    } else {
        form.post(route('markers.store'), {
            onSuccess: () => {
                show.value = false;
                form.name = '';
                form.description = '';
            }
        });
    }
}

function editMarker(id) {
    const marker = props.markers.find(m => m.id === id);
    if (!marker) return;
    form.id = marker.id;
    form.name = marker.name ?? '';
    form.description = marker.description ?? '';
    form.latitude = marker.latitude;
    form.longitude = marker.longitude;
    show.value = true;
}

function deleteMarker(id) {
    if (confirm('Kas oled kindel, et soovid selle markeri kustutada?')) {
        router.delete(route('markers.destroy', id), {
            onSuccess: () => {
                show.value = false;
                form.id = null;
                form.name = '';
                form.description = '';
            }
        });
    }
}

// Tee funktsioonid globaalselt kättesaadavaks popupi jaoks
window.editMarker = editMarker;
window.deleteMarker = deleteMarker;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="flex h-16 flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium"> Weather </CardTitle>
                        <img :src="'http://openweathermap.org/img/wn/' + weather.weather[0].icon + '@2x.png'" alt="Weather icon" class="h-12 w-12 bg-slate-400" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ weather.main.temp }}°C</div>
                        <p class="text-xs text-muted-foreground">{{ weather.wind.speed }} m/s ( {{ weather.weather[0].description }} )</p>
                    </CardContent>
                </Card>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min"
            >
                <div id="map" style="width: 100%; height: 100%" />
            </div>
            <Dialog :open="show" @update:open="show = $event">
    <DialogContent>
        <DialogHeader>
            <DialogTitle>
                {{ form.id ? 'Muuda markerit' : 'Lisa uus marker' }}
            </DialogTitle>
            <DialogDescription>
                {{ form.id ? 'Muuda markeri infot ja salvesta muudatused.' : 'Lisa uue markeri info ja salvesta.' }}
            </DialogDescription>
        </DialogHeader>
        <form class="space-y-4" @submit.prevent="handleSubmit">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Pealkiri</label>
                <input
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="Näiteks: Minu koht"
                    v-model="form.name"
                    name="name"
                    required
                />
                <div v-if="form.errors && form.errors.name" class="text-red-500 text-xs mt-1">
                    {{ form.errors.name }}
                </div>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Kirjeldus</label>
                <textarea
                    id="description"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="Kirjelda markerit..."
                    v-model="form.description"
                    name="description"
                ></textarea>
                <div v-if="form.errors && form.errors.description" class="text-red-500 text-xs mt-1">
                    {{ form.errors.description }}
                </div>
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500">Laiuskraad</label>
                    <input
                        type="number"
                        step="any"
                        v-model="form.latitude"
                        class="w-full rounded border-gray-300 p-1 text-xs bg-gray-100"
                        readonly
                    />
                </div>
                <div class="flex-1">
                    <label class="block text-xs text-gray-500">Pikkuskraad</label>
                    <input
                        type="number"
                        step="any"
                        v-model="form.longitude"
                        class="w-full rounded border-gray-300 p-1 text-xs bg-gray-100"
                        readonly
                    />
                </div>
            </div>
            <div class="flex justify-between items-center mt-6 gap-2">
                <button
                    v-if="form.id"
                    type="button"
                    @click="deleteMarker(form.id)"
                    class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700 transition"
                >
                    Kustuta marker
                </button>
                <span class="flex-1"></span>
                <button
                    type="submit"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 transition ml-auto"
                >
                    Salvesta
                </button>
            </div>
        </form>
    </DialogContent>
</Dialog>
        </div>
    </AppLayout>
</template>
