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
    title: '',
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
      console.log('Marker:', marker);
        Radar.ui
            .marker({
                color: '#000257',
                width: 40,
                height: 80,
                popup: {
                    text: `<strong>${marker.name}</strong><br>${marker.description}`,
                },
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
    form.post(route('markers.store'));
    show.value = false;
    // console.log('Form submitted');
}
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
                        <p class="text-xs text-muted-foreground">{{ weather.wind.speed }} m/s ( {{ weather.weather[0].description }} )</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Card Title</CardTitle>
                        <CardDescription>Card Description</CardDescription>
                    </CardHeader>
                    <CardContent> Card Content </CardContent>
                    <CardFooter> Card Footer </CardFooter>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Card Title</CardTitle>
                        <CardDescription>Card Description</CardDescription>
                    </CardHeader>
                    <CardContent> Card Content </CardContent>
                    <CardFooter> Card Footer </CardFooter>
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
                        <DialogTitle>Edit profile</DialogTitle>
                        <DialogDescription> Make changes to your profile here. Click save when you're done. </DialogDescription>
                    </DialogHeader>
                    <form class="space-y-4" @submit.prevent="handleSubmit">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input
                                id="title"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter title"
                                v-model="form.title"
                                name="title"
                            />
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea
                                id="description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter description"
                                v-model="form.description"
                                name="description"
                            ></textarea>
                        </div>
                        <input
                            type="submit"
                            value="Save"
                            class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        />
                    </form>
                    <!-- <DialogFooter>
        Save changes
      </DialogFooter> -->
                </DialogContent>
            </Dialog>
            <!-- <Dialog :open="show" @update:open="show = $event">
            <DialogHeader>
                <DialogTitle>Are you absolutely sure?</DialogTitle>
                <DialogDescription>
                    This action cannot be undone. Are you sure you want to permanently
                    delete this file from our servers?
                </DialogDescription>
            </DialogHeader>
            </Dialog> -->
        </div>
    </AppLayout>
</template>
