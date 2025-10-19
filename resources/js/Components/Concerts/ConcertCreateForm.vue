<script setup>
import InputError from "@/Components/InputError.vue"; // This is no longer used, but we can leave the import
import { ref, inject, computed, watch, nextTick } from "vue";
import {
    XMarkIcon,
    HeartIcon,
    ClockIcon,
    LinkIcon,
    TicketIcon,
    CalendarIcon,
    BanknotesIcon,
    MapIcon,
    MapPinIcon as MapPinIconOutline,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import { MapPinIcon as MapPinIconSolid } from "@heroicons/vue/24/solid";
import DropdownSelector from "@/Components/DropdownSelector.vue";
import DatePicker from "@/Components/DatePicker.vue";
import TimePicker from "@/Components/TimePicker.vue";
import AnimatedRangeInput from "@/Components/AnimatedRangeInput.vue";
import ProvinceDropdown from "@/Components/ProvinceDropdown.vue";
import ArtistDropdown from "@/Components/ArtistDropdown.vue";

const props = defineProps({
    form: Object,
    artists: Object,
});

const isDarkMode = inject("isDarkMode");
const emit = defineEmits(["submit"]);

const submit = () => {
    emit("submit");
};

const openMap = () => {
    console.log("Opening map to choose location...");
};

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

const uploadedPhotoUrl = ref(null);
const photoInput = ref(null);

const photoPreview = computed(() => {
    if (uploadedPhotoUrl.value) {
        return uploadedPhotoUrl.value;
    }
    if (props.form.picture_url && typeof props.form.picture_url !== "string") {
        return URL.createObjectURL(props.form.picture_url);
    }

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

const tempArtistId = ref(null);

const selectedArtists = computed(() => {
    return props.form.artist_ids
        .map((id) => props.artists.find((artist) => artist.id === id))
        .filter(Boolean);
});

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

const availableArtists = computed(() => {
    return props.artists
        .filter((artist) => !props.form.artist_ids.includes(artist.id))
        .map((artist) => ({
            name: artist.name,
            value: artist.id,
            picture_url:
                artist.picture_url || artistPicturePlaceholder.value(artist),
        }));
});

watch(tempArtistId, (newId) => {
    if (newId && !props.form.artist_ids.includes(newId)) {
        props.form.artist_ids.push(newId);
    }
    nextTick(() => {
        tempArtistId.value = null;
    });
});

watch(() => props.form.start_sale_date, (newStartDate) => {
    if (newStartDate && props.form.end_sale_date && new Date(props.form.end_sale_date) < new Date(newStartDate)) {
        props.form.end_sale_date = null;
    }
});

watch(() => props.form.start_show_date, (newStartDate) => {
    if (newStartDate && props.form.end_show_date && new Date(props.form.end_show_date) < new Date(newStartDate)) {
        props.form.end_show_date = null;
    }
});

function removeArtist(artistId) {
    props.form.artist_ids = props.form.artist_ids.filter(
        (id) => id !== artistId
    );
}
</script>

<template>
    <div
        class="max-w-xl lg:max-w-full mx-auto bg-card lg:shadow-xl rounded-lg space-y-2"
    >
        <div class="lg:flex px-6 pt-6 space-y-2 lg:space-y-0">
            <div class="flex-none w-fit lg:mr-4 relative">
                <div
                    v-if="photoPreview"
                    class="relative cursor-pointer lg:h-[444px]"
                    :class="{
                        'ring-1 ring-accent rounded-md':
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
                            'ring-1 ring-accent rounded-md':
                                props.form.errors.event_type,
                        }"
                    />
                    <DropdownSelector
                        placeholder="ประเภทเพลง"
                        v-model="props.form.genre"
                        :options="genres"
                        :class="{
                            'ring-1 ring-accent rounded-md':
                                props.form.errors.genre,
                        }"
                    />
                </div>
                <input
                    type="text"
                    id="name"
                    v-model="props.form.name"
                    class="mt-2 bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                    :class="{ 'ring-1 ring-accent': props.form.errors.name }"
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
                            'ring-1 ring-accent': props.form.errors.ticket_link,
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
                            ><TicketIcon
                                class="h-8 w-8 text-accent stroke-[2px]"
                        /></template>
                        <template #startInput
                            ><DatePicker
                                v-model="props.form.start_sale_date"
                                :min-date="new Date()"
                                :max-date="props.form.end_sale_date"
                                placeholder="วันจำหน่ายบัตร"
                                :class="{
                                    'ring-1 ring-accent rounded-md':
                                        props.form.errors.start_sale_date,
                                }"
                        /></template>
                        <template #endInput
                            ><DatePicker
                                v-model="props.form.end_sale_date"
                                :min-date="props.form.start_sale_date"
                                placeholder="วันที่สิ้นสุดการจำหน่าย"
                                :class="{
                                    'ring-1 ring-accent rounded-md':
                                        props.form.errors.end_sale_date,
                                }"
                        /></template>
                    </AnimatedRangeInput>

                    <AnimatedRangeInput
                        :trigger-value="props.form.start_show_date"
                        v-model:end-value="props.form.end_show_date"
                    >
                        <template #icon
                            ><CalendarIcon
                                class="h-8 w-8 text-secondary stroke-[2.5px]"
                        /></template>
                        <template #startInput
                            ><DatePicker
                                v-model="props.form.start_show_date"
                                placeholder="วันที่แสดง"
                                :min-date="new Date()"
                                :max-date="props.form.end_show_date"
                                :class="{
                                    'ring-1 ring-accent rounded-md':
                                        props.form.errors.start_show_date,
                                }"
                        /></template>
                        <template #endInput
                            ><DatePicker
                                v-model="props.form.end_show_date"
                                placeholder="สิ้นสุดการแสดง"
                                :min-date="props.form.start_show_date"
                                :class="{
                                    'ring-1 ring-accent rounded-md':
                                        props.form.errors.end_show_date,
                                }"
                        /></template>
                    </AnimatedRangeInput>
                    <AnimatedRangeInput
                        :trigger-value="props.form.start_show_time"
                        v-model:end-value="props.form.end_show_time"
                    >
                        <template #icon
                            ><ClockIcon
                                class="h-8 w-8 text-primary stroke-[2.5px]"
                        /></template>
                        <template #startInput
                            ><TimePicker
                                v-model="props.form.start_show_time"
                                placeholder="เวลาแสดง"
                                :class="{
                                    'ring-1 ring-accent rounded-md':
                                        props.form.errors.start_show_time,
                                }"
                        /></template>
                        <template #endInput
                            ><TimePicker
                                v-model="props.form.end_show_time"
                                placeholder="เวลาสิ้นสุด"
                                :class="{
                                    'ring-1 ring-accent rounded-md':
                                        props.form.errors.end_show_time,
                                }"
                        /></template>
                    </AnimatedRangeInput>
                    <AnimatedRangeInput :trigger-value="props.form.price_min">
                        <template #icon
                            ><BanknotesIcon
                                class="h-8 w-8 text-accent stroke-[2px]"
                        /></template>
                        <template #startInput
                            ><input
                                type="number"
                                v-model="props.form.price_min"
                                class="bg-background rounded-md text-sm font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium w-[90px]"
                                :class="{
                                    'ring-1 ring-accent':
                                        props.form.errors.price_min,
                                }"
                                placeholder="ราคาเริ่มต้น"
                        /></template>
                        <template #endInput
                            ><input
                                type="number"
                                v-model="props.form.price_max"
                                class="bg-background rounded-md text-sm font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium w-[150px]"
                                :class="{
                                    'ring-1 ring-accent':
                                        props.form.errors.price_max,
                                }"
                                placeholder="ราคาสูงสุด"
                        /></template>
                    </AnimatedRangeInput>
                    <div class="flex items-start space-x-2">
                        <AnimatedRangeInput
                            :trigger-value="props.form.province_id"
                            separator=","
                            :is-long="true"
                            class="grow"
                        >
                            <template #icon
                                ><MapIcon
                                    class="h-8 w-8 text-secondary stroke-[2px]"
                            /></template>
                            <template #startInput
                                ><ProvinceDropdown
                                    v-model="props.form.province_id"
                                    :class="{
                                        'ring-1 ring-accent rounded-md':
                                            props.form.errors.province_id,
                                    }"
                            /></template>
                            <template #endInput
                                ><input
                                    type="text"
                                    v-model="props.form.venue_name"
                                    class="bg-background rounded-md text-sm font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium w-full"
                                    :class="{
                                        'ring-1 ring-accent':
                                            props.form.errors.venue_name,
                                    }"
                                    placeholder="ชื่อสถานที่จัดงาน"
                            /></template>
                        </AnimatedRangeInput>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="openMap"
                            class="bg-primary rounded-md text-white font-semibold text-sm p-2 w-fit transition-colors duration-200"
                            aria-label="Choose from map"
                        >
                            เพิ่มหมุดในแผนที่
                        </button>
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
                                        'ring-1 ring-accent rounded-full':
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
                                'ring-1 ring-accent rounded-md':
                                    props.form.errors.description,
                            }"
                            placeholder="รายละเอียดงานดนตรี"
                        ></textarea>
                    </div>
                    <div class="">
                        <button
                            type="button"
                            @click="submit"
                            class="w-full bg-primary text-md text-white font-semibold py-2 rounded-md"
                        >
                            สร้างงานดนตรี
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
