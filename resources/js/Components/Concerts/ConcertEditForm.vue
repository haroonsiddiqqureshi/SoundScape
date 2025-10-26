<script setup>
// --- Imports ---
// Vue
import { ref, inject, computed, watch, nextTick, onMounted } from "vue";

// Components
import DropdownSelector from "@/Components/DropdownSelector.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TimePicker from "@/Components/TimePicker.vue";
import AnimatedRangeInput from "@/Components/AnimatedRangeInput.vue";
import ProvinceDropdown from "@/Components/ProvinceDropdown.vue";
import ArtistDropdown from "@/Components/ArtistDropdown.vue";

// Icons
import {
    XMarkIcon,
    HeartIcon,
    LinkIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import {
    TicketIcon,
    CalendarIcon,
    ClockIcon,
    BanknotesIcon,
    MapPinIcon,
    MapIcon,
    ArrowTurnLeftUpIcon,
} from "@heroicons/vue/24/solid";

// Third-Party Libraries
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// --- Core Vue Setup ---
const props = defineProps({
    form: Object,
    artists: Object,
    concert: Object, // Added for initial photo
});

const emit = defineEmits(["submit"]);
const isDarkMode = inject("isDarkMode");

// --- Static Configuration ---
const eventTypes = [
    { value: "music_festival", name: "เทศกาลดนตรี" },
    { value: "concert", name: "คอนเสิร์ต" },
    { value: "club", name: "คลับ / ผับ" },
    { value: "fan_meeting", name: "แฟนมีตติ้ง" },
    { value: "folk", name: "เพลงพื้นบ้าน / หมอลำ" },
    { value: "other", name: "อื่นๆ" },
];

const genres = [
    { value: "pop", name: "ป๊อป" },
    { value: "rock", name: "ร็อก" },
    { value: "hiphop", name: "ฮิปฮอป" },
    { value: "jazz", name: "แจ๊ส" },
    { value: "classical", name: "คลาสสิก" },
    { value: "country", name: "ลูกทุ่ง / คันทรี่" },
    { value: "edm", name: "อีดีเอ็ม (EDM)" },
    { value: "other", name: "อื่นๆ" },
];

const fieldLabels = {
    name: "ชื่องานดนตรี",
    event_type: "ประเภทงาน",
    genre: "ประเภทเพลง",
    ticket_link: "ลิงก์จำหน่ายบัตร",
    start_sale_date: "วันจำหน่ายบัตร",
    end_sale_date: "วันสิ้นสุดการจำหน่าย",
    start_show_date: "วันที่แสดง",
    end_show_date: "สิ้นสุดการแสดง",
    start_show_time: "เวลาแสดง",
    end_show_time: "เวลาสิ้นสุด",
    price_min: "ราคาเริ่มต้น",
    price_max: "ราคาสูงสุด",
    latitude: "ตำแหน่งที่ตั้ง",
    longitude: "ตำแหน่งที่ตั้ง",
    province_id: "จังหวัด",
    venue_name: "ชื่อสถานที่จัดงาน",
    artist_ids: "ศิลปิน",
    description: "รายละเอียดงานดนตรี",
    picture_url: "รูปภาพ",
};

// --- Form Submission & Error Handling ---
const errorSummary = ref(null);

const submit = () => {
    emit("submit");
};

// Watch for errors and scroll to the summary
watch(
    () => props.form.errors,
    (newErrors) => {
        if (newErrors && Object.keys(newErrors).length > 0) {
            nextTick(() => {
                if (errorSummary.value) {
                    errorSummary.value.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            });
        }
    },
    { deep: true }
);

// --- Lifecycle Hooks ---
const allProvinces = ref([]);

// Fetches provinces from your API on component load
onMounted(() => {
    fetch("/api/provinces")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            allProvinces.value = data;
            // Pre-fill province name if we have a province_id
            if (props.form.province_id && allProvinces.value.length > 0) {
                const initialProvince = allProvinces.value.find(
                    (p) => p.id === props.form.province_id
                );
                if (initialProvince) {
                    selectedProvinceName.value = initialProvince.name_th;
                }
            }
        })
        .catch((error) => console.error("Error fetching provinces:", error));
});

// --- Feature: Photo Upload ---
const uploadedPhotoUrl = ref(null);
const photoInput = ref(null);

// Calculate the initial image URL from the concert prop
const initialImageUrl = computed(() => {
    if (props.concert && props.concert.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return null; // No initial image
});

const photoPreview = computed(() => {
    // 1. If user just uploaded a new photo, show it.
    if (uploadedPhotoUrl.value) {
        return uploadedPhotoUrl.value;
    }

    // 2. If the form.picture_url is a File object (from a previous edit attempt?), show it.
    if (props.form.picture_url && typeof props.form.picture_url !== "string") {
        return URL.createObjectURL(props.form.picture_url);
    }

    // 3. If no new photo, show the initial image from the concert.
    if (initialImageUrl.value) {
        return initialImageUrl.value;
    }

    // 4. Fallback to placeholder
    return isDarkMode.value
        ? "https://placehold.co/800x1200/1c1423/ffffff80?text=Upload%5CnPicture"
        : "https://placehold.co/800x1200/e5e7eb/00000080?text=Upload%5CnPicture";
});

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    props.form.picture_url = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        uploadedPhotoUrl.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

// --- Feature: Artist Selection ---
const tempArtistId = ref(null);

const artistPicturePlaceholder = computed(() => {
    return (artist) => {
        return isDarkMode.value
            ? `https://ui-avatars.com/api/?name=${encodeURIComponent(
                  artist.name
              )}&background=ff1493&color=ffffff`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                  artist.name
              )}&background=008be6&color=ffffff`;
    };
});

// Defensive checks for props.form.artist_ids and props.artists
const selectedArtists = computed(() => {
    if (
        !props.form.artist_ids ||
        !Array.isArray(props.form.artist_ids) ||
        !props.artists ||
        !Array.isArray(props.artists)
    ) {
        return [];
    }
    return props.form.artist_ids
        .map((id) => props.artists.find((artist) => artist.id === id))
        .filter(Boolean); // Filter out any undefined results
});

// Defensive checks for props.artists and props.form.artist_ids
const availableArtists = computed(() => {
    if (!props.artists || !Array.isArray(props.artists)) {
        return [];
    }
    // Ensure form.artist_ids is also an array for safe filtering
    const selectedIds = Array.isArray(props.form.artist_ids)
        ? props.form.artist_ids
        : [];

    return props.artists
        .filter((artist) => !selectedIds.includes(artist.id))
        .map((artist) => ({
            name: artist.name,
            value: artist.id,
            picture_url:
                artist.picture_url || artistPicturePlaceholder.value(artist),
        }));
});

watch(tempArtistId, (newId) => {
    if (newId) {
        // Ensure artist_ids is an array before pushing
        if (!Array.isArray(props.form.artist_ids)) {
            props.form.artist_ids = [];
        }
        if (!props.form.artist_ids.includes(newId)) {
             props.form.artist_ids.push(newId);
        }
    }
    // Reset the dropdown
    nextTick(() => {
        tempArtistId.value = null;
    });
});

function removeArtist(artistId) {
    if (!props.form.artist_ids || !Array.isArray(props.form.artist_ids)) {
        return;
    }
    props.form.artist_ids = props.form.artist_ids.filter(
        (id) => id !== artistId
    );
}

// --- Feature: Location & Map ---
const selectedProvinceName = ref(null);
const showMapModal = ref(false);
const mapInstance = ref(null);
const selectedLocation = ref(null);
const mapMarker = ref(null);
const isGeocoding = ref(false);
const locationMode = ref("map");

const selectedCoordinates = computed(() => {
    if (props.form.latitude && props.form.longitude) {
        return `${Number(props.form.latitude).toFixed(
            6
        )}, ${Number(props.form.longitude).toFixed(6)}`;
    }
    return null;
});

const modalSelectedCoordinates = computed(() => {
    if (selectedLocation.value) {
        return `${selectedLocation.value.lat.toFixed(
            6
        )}, ${selectedLocation.value.lng.toFixed(6)}`;
    }
    return null;
});

function toggleLocationMode() {
    locationMode.value = locationMode.value === "manual" ? "map" : "manual";
    // Clear the other method's data to avoid conflicts
    if (locationMode.value === "manual") {
        props.form.latitude = null;
        props.form.longitude = null;
    } else {
        props.form.province_id = null;
        props.form.venue_name = null;
    }
}

const openMap = () => {
    showMapModal.value = true;
    selectedLocation.value = null;
    selectedProvinceName.value = null; // Clear this, it will be re-set

    // If there's an initial province_id, set the province name
    if (props.form.province_id && allProvinces.value.length > 0) {
        const initialProvince = allProvinces.value.find(
            (p) => p.id === props.form.province_id
        );
        if (initialProvince) {
            selectedProvinceName.value = initialProvince.name_th;
        }
    }
};

const initMap = () => {
    if (mapInstance.value) return;

    let initialLat = props.form.latitude || 13.7563; // Default to Bangkok
    let initialLng = props.form.longitude || 100.5018;
    let initialZoom = props.form.latitude ? 16 : 10;

    mapInstance.value = L.map("map-picker").setView(
        [initialLat, initialLng],
        initialZoom
    );

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(mapInstance.value);

    // Set initial marker if coords exist
    if (props.form.latitude && props.form.longitude) {
        selectedLocation.value = {
            lat: props.form.latitude,
            lng: props.form.longitude,
        };
        mapMarker.value = L.marker(selectedLocation.value).addTo(
            mapInstance.value
        );
    }

    // Handle map click
    mapInstance.value.on("click", (e) => {
        selectedLocation.value = e.latlng;
        if (mapMarker.value) {
            mapInstance.value.removeLayer(mapMarker.value);
        }
        mapMarker.value = L.marker(e.latlng).addTo(mapInstance.value);
        selectedProvinceName.value = null; // Clear old name
        reverseGeocode(e.latlng);
    });
};

const confirmLocation = () => {
    if (selectedLocation.value) {
        props.form.latitude = selectedLocation.value.lat;
        props.form.longitude = selectedLocation.value.lng;
    }
    showMapModal.value = false;
};

const closeModal = () => {
    showMapModal.value = false;
};

async function reverseGeocode(latlng) {
    if (allProvinces.value.length === 0) {
        console.warn("Provinces list not loaded. Cannot find province ID.");
        return;
    }
    isGeocoding.value = true;
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&accept-language=th,en`
        );
        const data = await response.json();
        if (data && data.address) {
            const provinceName = data.address.province || data.address.city;
            if (provinceName) {
                const normalizedName = provinceName
                    .replace("จังหวัด", "")
                    .trim();
                const matchingProvince = allProvinces.value.find(
                    (p) =>
                        p.name_th.includes(normalizedName) ||
                        (p.name_en && p.name_en.includes(normalizedName))
                );
                if (matchingProvince) {
                    props.form.province_id = matchingProvince.id;
                    selectedProvinceName.value = matchingProvince.name_th;
                } else {
                    console.warn(`Could not match province: ${provinceName}`);
                    props.form.province_id = null;
                    selectedProvinceName.value = "ไม่พบ";
                }
            } else {
                props.form.province_id = null;
                selectedProvinceName.value = "ไม่พบ";
            }
        }
    } catch (error) {
        console.error("Reverse geocoding failed:", error);
        props.form.province_id = null;
        selectedProvinceName.value = "ผิดพลาด";
    } finally {
        isGeocoding.value = false;
    }
}

// Watcher for map modal lifecycle
watch(showMapModal, (isShowing) => {
    if (isShowing) {
        nextTick(() => {
            initMap();
        });
    } else {
        // Destroy map instance when modal is closed to prevent memory leaks
        if (mapInstance.value) {
            mapInstance.value.remove();
            mapInstance.value = null;
            mapMarker.value = null;
        }
    }
});

// --- Feature: Date Validation Watchers ---
watch(
    () => props.form.start_sale_date,
    (newStartDate) => {
        if (
            newStartDate &&
            props.form.end_sale_date &&
            new Date(props.form.end_sale_date) < new Date(newStartDate)
        ) {
            props.form.end_sale_date = null; // Reset end date if invalid
        }
    }
);

watch(
    () => props.form.start_show_date,
    (newStartDate) => {
        if (
            newStartDate &&
            props.form.end_show_date &&
            new Date(props.form.end_show_date) < new Date(newStartDate)
        ) {
            props.form.end_show_date = null; // Reset end date if invalid
        }
    }
);
</script>

<template>
    <div
        ref="errorSummary"
        v-if="Object.keys(props.form.errors).length"
        class="max-w-xl lg:max-w-full mx-auto bg-card lg:shadow-xl rounded-md mb-4 p-6"
    >
        <div>
            <div class="flex flex-col w-full space-y-2">
                <span class="text-lg font-semibold text-primary"
                    >โอ๊ะ! เกิดข้อผิดพลาด</span
                >
                <span class="text-sm"
                    >กรุณาตรวจสอบข้อมูลในช่องที่มีกรอบเส้นประอีกครั้ง</span
                >
                <span></span>
                <ul
                    class="list-disc list-inside space-y-1 pl-5 bg-background p-4 rounded-md outline-dashed -outline-offset-4 text-primary"
                >
                    <li
                        v-for="(errorMessages, fieldName) in props.form.errors"
                        :key="fieldName"
                        class="text-sm text-primary"
                    >
                        <strong class="font-bold"
                            >{{ fieldLabels[fieldName] || fieldName }}:</strong
                        >
                        <span class="ml-1 text-text">{{ errorMessages }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div
        class="max-w-xl lg:max-w-full mx-auto bg-card lg:shadow-xl rounded-md space-y-2"
    >
        <div class="lg:flex px-6 pt-6 space-y-2 lg:space-y-0">
            <div class="flex-none w-fit lg:mr-4 relative">
                <div
                    v-if="photoPreview"
                    class="relative cursor-pointer lg:h-[444px]"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            props.form.errors.picture_url,
                    }"
                >
                    <img
                        :src="photoPreview"
                        class="aspect-[2/3] h-full object-fill rounded-md"
                    />
                    <input
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        @change="updatePhotoPreview"
                        accept="image/*"
                    />
                    <button
                        type="button"
                        @click.prevent="selectNewPhoto"
                        class="absolute inset-0"
                    />
                </div>
            </div>
            <div class="flex flex-col w-full">
                <div class="flex space-x-4">
                    <DropdownSelector
                        placeholder="ประเภทงาน"
                        v-model="props.form.event_type"
                        :options="eventTypes"
                        :class="{
                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                props.form.errors.event_type,
                        }"
                    />
                    <DropdownSelector
                        placeholder="ประเภทเพลง"
                        v-model="props.form.genre"
                        :options="genres"
                        :class="{
                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                props.form.errors.genre,
                        }"
                    />
                </div>
                <input
                    type="text"
                    id="name"
                    v-model="props.form.name"
                    class="mt-2 bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            props.form.errors.name,
                    }"
                    placeholder="ชื่องานดนตรี"
                />
                <div class="flex items-center space-x-8 ml-12 mt-4">
                    <HeartIcon
                        class="flex-none h-8 w-8 text-primary stroke-[3px]"
                    />
                    <LinkIcon
                        class="flex-none h-8 w-8 text-secondary stroke-[2.5px]"
                    />
                    <input
                        type="text"
                        v-model="props.form.ticket_link"
                        class="w-full bg-background rounded-md border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                        :class="{
                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                props.form.errors.ticket_link,
                        }"
                        placeholder="ลิงก์จำหน่ายบัตร"
                    />
                </div>
                <div class="flex flex-col mt-12 space-y-2 pl-2">
                    <AnimatedRangeInput
                        :trigger-value="props.form.start_sale_date"
                        v-model:end-value="props.form.end_sale_date"
                    >
                        <template #icon
                            ><TicketIcon class="h-8 w-8 text-accent"
                        /></template>
                        <template #startInput
                            ><DatePicker
                                v-model="props.form.start_sale_date"
                                :max-date="props.form.end_sale_date"
                                placeholder="วันจำหน่ายบัตร"
                                :error="props.form.errors.start_sale_date"
                        /></template>
                        <template #endInput
                            ><DatePicker
                                v-model="props.form.end_sale_date"
                                :min-date="props.form.start_sale_date"
                                placeholder="วันสิ้นสุดการจำหน่าย"
                                :error="props.form.errors.end_sale_date"
                        /></template>
                    </AnimatedRangeInput>
                    <AnimatedRangeInput
                        :trigger-value="props.form.start_show_date"
                        v-model:end-value="props.form.end_show_date"
                    >
                        <template #icon
                            ><CalendarIcon class="h-8 w-8 text-secondary"
                        /></template>
                        <template #startInput
                            ><DatePicker
                                v-model="props.form.start_show_date"
                                placeholder="วันที่แสดง"
                                :max-date="props.form.end_show_date"
                                :error="props.form.errors.start_show_date"
                        /></template>
                        <template #endInput
                            ><DatePicker
                                v-model="props.form.end_show_date"
                                placeholder="สิ้นสุดการแสดง"
                                :min-date="props.form.start_show_date"
                                :error="props.form.errors.end_show_date"
                        /></template>
                    </AnimatedRangeInput>
                    <AnimatedRangeInput
                        :trigger-value="props.form.start_show_time"
                        v-model:end-value="props.form.end_show_time"
                    >
                        <template #icon
                            ><ClockIcon class="h-8 w-8 text-primary"
                        /></template>
                        <template #startInput
                            ><TimePicker
                                v-model="props.form.start_show_time"
                                placeholder="เวลาแสดง"
                                :error="props.form.errors.start_show_time"
                        /></template>
                        <template #endInput
                            ><TimePicker
                                v-model="props.form.end_show_time"
                                placeholder="เวลาสิ้นสุด"
                                :error="props.form.errors.end_show_time"
                        /></template>
                    </AnimatedRangeInput>
                    <AnimatedRangeInput :trigger-value="props.form.price_min">
                        <template #icon
                            ><BanknotesIcon class="h-8 w-8 text-accent"
                        /></template>
                        <template #startInput
                            ><input
                                type="number"
                                v-model="props.form.price_min"
                                class="bg-background rounded-md text-sm font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium w-[100px]"
                                :class="{
                                    'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                        props.form.errors.price_min,
                                }"
                                placeholder="ราคาเริ่มต้น"
                        /></template>
                        <template #endInput
                            ><input
                                type="number"
                                v-model="props.form.price_max"
                                class="bg-background rounded-md text-sm font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium w-[100px]"
                                :class="{
                                    'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                        props.form.errors.price_max,
                                }"
                                placeholder="ราคาสูงสุด"
                        /></template>
                    </AnimatedRangeInput>

                    <div class="flex items-start space-x-2">
                        <AnimatedRangeInput
                            :trigger-value="
                                locationMode === 'manual'
                                    ? props.form.province_id
                                    : props.form.latitude
                            "
                            separator=","
                            :is-long="true"
                            class="grow"
                        >
                            <template #icon>
                                <button
                                    type="button"
                                    @click="toggleLocationMode"
                                    class="flex items-center justify-center text-secondary hover:text-secondary-hover transition-colors duration-150 outline-none"
                                    :title="
                                        locationMode === 'manual'
                                            ? 'เลือกจากแผนที่'
                                            : 'กรอกด้วยตนเอง'
                                    "
                                >
                                    <MapIcon
                                        v-if="locationMode === 'manual'"
                                        class="h-8 w-8"
                                    />
                                    <MapPinIcon v-else class="h-8 w-8" />
                                </button>
                            </template>

                            <template #startInput>
                                <button
                                    v-if="locationMode === 'map'"
                                    type="button"
                                    @click="openMap"
                                    class="bg-background hover:bg-background-hover rounded-md text-sm font-medium border-none text-text-medium text-left w-full px-3 py-2"
                                    :class="{
                                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                            props.form.errors.latitude ||
                                            props.form.errors.longitude,
                                        'text-text': props.form.latitude,
                                    }"
                                >
                                    <span
                                        v-if="selectedCoordinates"
                                        class="text-text"
                                        >{{ selectedProvinceName }} :
                                        <span class="text-text-medium">{{
                                            selectedCoordinates
                                        }}</span></span
                                    >
                                    <span v-else
                                        >เลือกตำแหน่งที่ตั้งจากแผนที่</span
                                    >
                                </button>
                                <ProvinceDropdown
                                    v-if="locationMode === 'manual'"
                                    v-model="props.form.province_id"
                                    :provinces="allProvinces"
                                    :class="{
                                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                            props.form.errors.province_id,
                                    }"
                                />
                            </template>

                            <template #endInput>
                                <div
                                    v-if="locationMode === 'manual'"
                                    class="relative w-full"
                                >
                                    <input
                                        type="text"
                                        v-model="props.form.venue_name"
                                        class="bg-background rounded-md text-sm font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium w-full pr-10"
                                        :class="{
                                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                                props.form.errors.venue_name,
                                        }"
                                        placeholder="ชื่อสถานที่จัดงาน"
                                    />
                                </div>
                            </template>
                        </AnimatedRangeInput>
                    </div>
                    <div class="flex text-sm space-x-4 ml-2.5">
                        <ArrowTurnLeftUpIcon class="h-4 w-4 inline-block" />
                        <span class="mt-0.5"
                            >คลิกเพื่อเปลี่ยนโหมดเลือกตำแหน่ง</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <div class="flex flex-col">
                <div class="lg:hidden bg-background w-full h-[1px] mb-2" />
                <div class="w-full space-y-2">
                    <div
                        class="w-full h-full px-2 py-4 bg-background rounded-md space-y-2"
                    >
                        <div
                            class="flex flex-wrap items-center gap-y-2 gap-x-1 mx-2 bg-transparent"
                        >
                            <div class="relative">
                                <ArtistDropdown
                                    v-model="tempArtistId"
                                    :options="availableArtists"
                                    :is-dark-mode="isDarkMode"
                                    :class="{
                                        'outline-dashed outline-primary -outline-offset-4 rounded-full':
                                            props.form.errors.artist_ids,
                                    }"
                                >
                                    <template #button-face="{ selectedOption }">
                                        <div
                                            class="group flex items-center bg-card hover:bg-primary py-1 px-2 rounded-full text-sm font-medium cursor-pointer transition-all duration-150"
                                        >
                                            <PlusIcon
                                                class="h-4 w-4 stroke-current stroke-[3px] text-primary group-hover:text-white"
                                            />
                                            <span
                                                class="mx-1 text-text group-hover:text-white uppercase"
                                                >Singer</span
                                            >
                                        </div>
                                    </template>
                                </ArtistDropdown>
                            </div>
                            <div
                                v-for="artist in selectedArtists"
                                :key="artist.id"
                                class="flex items-center space-x-2 bg-card py-1 px-2 rounded-full"
                            >
                                <img
                                    :src="
                                        artist.picture_url ||
                                        artistPicturePlaceholder(artist)
                                    "
                                    :alt="artist.name"
                                    class="w-5 h-5 rounded-full object-cover"
                                />
                                <span class="text-sm font-medium uppercase">{{
                                    artist.name
                                }}</span>
                                <button
                                    @click="removeArtist(artist.id)"
                                    type="button"
                                    class="text-text-medium hover:text-primary rounded-full p-0.5"
                                >
                                    <XMarkIcon
                                        class="w-4 h-4 stroke-current stroke-[2px]"
                                    />
                                </button>
                            </div>
                        </div>
                        <textarea
                            v-model="props.form.description"
                            class="w-full resize-none h-64 bg-transparent border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                            :class="{
                                'text-center': props.form.description,
                                'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                    props.form.errors.description,
                            }"
                            placeholder="รายละเอียดงานดนตรี"
                        ></textarea>
                    </div>
                    <button
                        type="button"
                        @click="submit"
                        class="rounded-md bg-primary px-4 py-2 font-bold w-full text-white hover:bg-primary-hover disabled:bg-primary-hover disabled:cursor-not-allowed"
                        :disabled="props.form.processing"
                    >
                        <span v-if="props.form.processing"
                            >กำลังอัปเดต...</span
                        >
                        <span v-else>อัปเดตงานดนตรี</span>
                    </button>
                </div>
            </div>
        </div>

        <teleport to="body">
            <div
                v-if="showMapModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                @click.self="closeModal"
            >
                <div
                    class="bg-card rounded-md shadow-xl w-full max-w-2xl p-6 space-y-4"
                >
                    <div class="flex justify-between items-center">
                        <h3
                            class="text-lg flex flex-col sm:flex-row items-start sm:items-center sm:space-x-2 font-medium leading-6"
                        >
                            <span>เลือกตำแหน่งที่ตั้ง</span>

                            <span
                                v-if="isGeocoding"
                                class="text-sm font-normal text-text-medium"
                            >
                                (กำลังค้นหาจังหวัด...)
                            </span>
                            <span
                                v-else-if="selectedLocation"
                                class="text-sm font-normal text-text-medium"
                            >
                                ({{ selectedProvinceName }} - พิกัด:
                                {{ modalSelectedCoordinates }})
                            </span>
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-text hover:text-text-hover"
                        >
                            <XMarkIcon class="h-6 w-6" />
                        </button>
                    </div>

                    <div
                        id="map-picker"
                        class="rounded-md h-[400px] w-full"
                    ></div>

                    <button
                        type="button"
                        @click="confirmLocation"
                        class="rounded-md w-full bg-primary px-4 py-2 font-bold text-white hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!selectedLocation || isGeocoding"
                    >
                        <span v-if="isGeocoding">กำลังค้นหา...</span>
                        <span v-else>ยืนยันตำแหน่ง</span>
                    </button>
                </div>
            </div>
        </teleport>
    </div>
</template>