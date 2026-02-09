<script setup>
import { defineProps, computed, inject, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
    HeartIcon as OutlineHeartIcon,
    LinkIcon,
    ArrowTopRightOnSquareIcon,
    ArrowRightStartOnRectangleIcon,
    TicketIcon,
    CalendarIcon,
    ClockIcon,
    BanknotesIcon,
    MapPinIcon,
    PencilIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import { HeartIcon as SolidHeartIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    concert: Object,
    provinces: Object,
    role: String,
});

const isDarkMode = inject("isDarkMode", ref(false));
const copied = ref(false);

const editHref = computed(() => {
    if (props.role === "admin") {
        return route("admin.concert.edit", { concert: props.concert.id });
    }
    return route("promoter.concert.edit", { concert: props.concert.id });
});

const copyUrlToClipboard = () => {
    const url = window.location.href;
    navigator.clipboard
        .writeText(url)
        .then(() => {
            copied.value = true;
            setTimeout(() => {
                copied.value = false;
            }, 2000);
        })
        .catch((err) => {
            console.error("Failed to copy URL: ", err);
        });
};

const deleteRouteName = computed(() => {
    if (props.role === "admin") {
        return "admin.concert.delete";
    }
    return "promoter.concert.delete";
});

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

const photoPreview = computed(() => {
    if (props.concert.picture_url) {
        if (props.concert.picture_url.startsWith("http")) {
            return props.concert.picture_url;
        }
        return `/storage/${props.concert.picture_url}`;
    }
    return isDarkMode.value
        ? "https://placehold.co/800x1200/1c1423/ffffff80?text=No%5CnPicture"
        : "https://placehold.co/800x1200/e5e7eb/00000080?text=No%5CnPicture";
});

const displayEventType = computed(() => {
    const eventType = eventTypes.find(
        (e) => e.value === props.concert.event_type
    );
    return eventType
        ? eventType.name
        : props.concert.event_type || "ไม่ระบุประเภท";
});

const displayGenre = computed(() => {
    const genre = genres.find((g) => g.value === props.concert.genre);
    return genre ? genre.name : props.concert.genre || "ไม่ระบุแนวเพลง";
});

const formattedDate = (dateString) => {
    if (!dateString) return null;
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return "Invalid Date";
        return date.toLocaleDateString("th-TH", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    } catch (e) {
        return dateString;
    }
};

const formattedTime = (timeString) => {
    if (!timeString) return null;
    const parts = timeString.split(":");
    if (parts.length < 2) return timeString;
    return `${parts[0]}:${parts[1]}`;
};

const formattedPrice = (price) => {
    if (price == 0) return null;
    return new Intl.NumberFormat("th-TH", {
        maximumFractionDigits: 0,
    }).format(price);
};

const startSaleDate = computed(() =>
    formattedDate(props.concert.start_sale_date)
);
const endSaleDate = computed(() => formattedDate(props.concert.end_sale_date));
const startShowDate = computed(() =>
    formattedDate(props.concert.start_show_date)
);
const endShowDate = computed(() => formattedDate(props.concert.end_show_date));
const startShowTime = computed(() =>
    formattedTime(props.concert.start_show_time)
);
const endShowTime = computed(() => formattedTime(props.concert.end_show_time));
const priceMin = computed(() => formattedPrice(props.concert.price_min));
const priceMax = computed(() => formattedPrice(props.concert.price_max));

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

const provinceName = computed(() => {
    if (props.provinces && props.concert && props.concert.province_id) {
        const province = props.provinces[props.concert.province_id];
        return province ? province.name_th : "Unknown Province";
    }
    return "ไม่ระบุจังหวัด";
});

const googleMapsLink = computed(() => {
    if (props.concert.latitude && props.concert.longitude) {
        return `https://www.google.com/maps/search/?api=1&query=${props.concert.latitude},${props.concert.longitude}`;
    }
    const query = `${props.concert.venue_name}, ${
        props.concert.province?.name || props.concert.province_id
    }`;
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
        query
    )}`;
});

const followConcert = (follow) => {
    router.post(
        route("concert.follow", props.concert.id),
        {
            is_following: !follow,
        },
        { preserveState: true }
    );
};
</script>

<template>
    <div
        class="mx-auto max-w-xl lg:max-w-fit bg-card shadow-xl rounded-md space-y-2 lg:space-y-6"
    >
        <div class="lg:flex px-6 pt-6 space-y-2 lg:space-y-0">
            <div class="flex-none w-fit lg:mr-4">
                <div class="lg:h-[444px]">
                    <img
                        :src="photoPreview"
                        class="aspect-[2/3] h-full object-fill rounded-md"
                        alt="Concert Poster"
                    />
                </div>
            </div>

            <div class="flex flex-col min-w-[300px] justify-between pb-2">
                <div class="flex flex-col">
                    <div class="flex space-x-2">
                        <div
                            class="inline-flex items-center border-[2px] border-primary rounded-full px-3 h-6 text-sm text-primary"
                        >
                            {{ displayEventType }}
                        </div>
                        <div
                            class="inline-flex items-center border-[2px] border-secondary rounded-full px-3 h-6 text-sm text-secondary"
                        >
                            {{ displayGenre }}
                        </div>
                    </div>

                    <h1
                        class="custom-scrollbar mt-1 ml-2 font-semibold text-2xl lg:text-4xl text-text break-words line-clamp-2 py-1"
                    >
                        {{ props.concert.name }}
                    </h1>

                    <div
                        class="flex items-center space-x-12 mx-auto lg:ml-12 mt-4"
                    >
                        <OutlineHeartIcon
                            v-if="
                                props.role === 'admin' ||
                                props.role === 'promoter'
                            "
                            class="flex-none h-8 w-8 text-primary"
                        />
                        <button
                            v-else
                            @click.prevent.stop="
                                followConcert(props.concert.is_followed)
                            "
                        >
                            <component
                                :is="
                                    props.concert.is_followed
                                        ? SolidHeartIcon
                                        : OutlineHeartIcon
                                "
                                class="flex-none h-8 w-8 text-primary"
                            />
                        </button>
                        <div class="relative">
                            <button
                                @click="copyUrlToClipboard"
                                type="button"
                                class="flex items-center"
                                aria-label="Copy page URL"
                            >
                                <LinkIcon
                                    class="flex-none h-8 w-8 text-secondary"
                                />
                            </button>
                            <transition
                                enter-active-class="transition-opacity duration-200"
                                leave-active-class="transition-opacity duration-300"
                                enter-from-class="opacity-0"
                                leave-to-class="opacity-0"
                            >
                                <div
                                    v-if="copied"
                                    class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-background text-text text-xs rounded-md shadow-xl whitespace-nowrap"
                                >
                                    คัดลอกลิงก์แล้ว!
                                </div>
                            </transition>
                        </div>
                        <a
                            v-if="props.concert.ticket_link"
                            :href="props.concert.ticket_link"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="ไปยังเว็บไซต์คอนเสิร์ต"
                        >
                            <ArrowRightStartOnRectangleIcon
                                class="flex-none h-8 w-8 text-accent"
                            />
                        </a>
                    </div>
                </div>

                <div class="flex flex-col mt-12 space-y-2 pl-2 text-base">
                    <div class="flex items-center">
                        <div class="flex items-center space-x-2 mr-2">
                            <TicketIcon class="flex-none h-8 w-8 text-accent" />
                            <span class="text-accent text-center">
                                {{
                                    props.concert.start_sale_date
                                        ? "วันจำหน่ายบัตร |"
                                        : "ไม่กำหนดวันจำหน่ายบัตร"
                                }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{{ startSaleDate }}</span>
                            <span v-if="props.concert.end_sale_date">-</span>
                            <span v-if="props.concert.end_sale_date">
                                {{ endSaleDate }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center lg:max-w-xl">
                        <div class="flex items-center space-x-2 mr-2">
                            <CalendarIcon
                                class="flex-none h-8 w-8 text-secondary"
                            />
                            <span class="text-secondary text-center">
                                {{
                                    props.concert.start_show_date
                                        ? "วันที่แสดง |"
                                        : "ไม่กำหนดวันที่แสดง"
                                }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{{ startShowDate }}</span>
                            <span v-if="props.concert.end_show_date">-</span>
                            <span v-if="props.concert.end_show_date">
                                {{ endShowDate }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center lg:max-w-xl">
                        <div class="flex items-center space-x-2 mr-2">
                            <ClockIcon class="flex-none h-8 w-8 text-primary" />
                            <span class="text-primary text-center">
                                {{
                                    props.concert.start_show_time
                                        ? "เวลาแสดง |"
                                        : "ไม่กำหนดเวลาแสดง"
                                }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{{ startShowTime }}</span>
                            <span v-if="props.concert.end_show_time">-</span>
                            <span v-if="props.concert.end_show_time">
                                {{ endShowTime }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center lg:max-w-xl">
                        <div class="flex items-center space-x-2 mr-2">
                            <BanknotesIcon
                                class="flex-none h-8 w-8 text-accent"
                            />
                            <span class="text-accent text-center">
                                {{
                                    props.concert.price_min != null
                                        ? "ราคาบัตร |"
                                        : "ไม่ระบุราคาบัตร"
                                }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span v-if="props.concert.price_min">{{
                                priceMin
                            }}</span>
                            <span v-if="props.concert.price_max">-</span>
                            <span v-if="props.concert.price_max">
                                {{ priceMax }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center lg:max-w-xl">
                        <div class="flex items-center space-x-2 mr-2">
                            <MapPinIcon
                                class="flex-none h-8 w-8 text-secondary"
                            />
                            <span
                                v-if="props.concert.province_id"
                                class="text-secondary text-center"
                            >
                                {{
                                    props.concert.venue_name ||
                                    [
                                        props.concert.latitude,
                                        props.concert.longitude,
                                    ].every(Boolean)
                                        ? provinceName + " |"
                                        : provinceName
                                }}
                            </span>
                        </div>
                        <a
                            :href="googleMapsLink"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="hover:underline"
                            aria-label="View on map"
                        >
                            <span
                                v-if="props.concert.venue_name"
                                class="text-text"
                            >
                                {{ props.concert.venue_name }}
                                <ArrowTopRightOnSquareIcon
                                    class="h-4 w-4 inline-block stroke-[2px] mb-2"
                                />
                            </span>
                            <span
                                v-else-if="
                                    props.concert.latitude &&
                                    props.concert.longitude
                                "
                                class="text-text"
                            >
                                View On Google Map
                                <ArrowTopRightOnSquareIcon
                                    class="h-4 w-4 inline-block stroke-[2px] mb-2"
                                />
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-6">
            <div class="flex flex-col">
                <div class="lg:hidden bg-background w-full h-[1px] mb-2" />
                <div class="w-full space-y-2">
                    <div
                        class="w-full px-2 py-4 bg-background rounded-md space-y-2"
                    >
                        <div
                            v-if="props.concert.artists?.length"
                            class="flex flex-wrap items-center gap-y-2 gap-x-1 mx-2"
                        >
                            <div
                                v-for="artist in props.concert.artists"
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
                                <span
                                    class="text-sm font-medium uppercase text-text"
                                    >{{ artist.name }}</span
                                >
                            </div>
                        </div>

                        <div
                            :class="{
                                'custom-scrollbar max-h-[16rem]':
                                    props.role === 'admin' ||
                                    props.role === 'promoter',
                            }"
                            class="text-center w-full min-h-[16rem] overflow-auto p-2 whitespace-pre-wrap"
                        >
                            {{
                                props.concert.description ||
                                "ไม่มีรายละเอียดงาน"
                            }}
                        </div>
                    </div>

                    <div
                        v-if="
                            props.role === 'admin' || props.role === 'promoter'
                        "
                        class="flex space-x-2"
                    >
                        <Link
                            :href="editHref"
                            class="flex w-full justify-center px-2 items-center space-x-1 border-[2px] border-secondary text-md text-secondary rounded-md transition-colors duration-200"
                        >
                            <PencilIcon
                                class="w-4 h-4 stroke-current stroke-[2px]"
                            />
                            <span>แก้ไข</span>
                        </Link>
                        <Link
                            :href="
                                route(deleteRouteName, {
                                    concert: props.concert.id,
                                })
                            "
                            method="delete"
                            as="button"
                            preserve-scroll
                            class="flex w-full justify-center px-2 items-center space-x-1 border-[2px] border-red-600 text-md text-red-600 rounded-md transition-colors duration-200"
                        >
                            <TrashIcon
                                class="w-4 h-4 stroke-current stroke-[2px]"
                            />
                            <span>ลบ</span>
                        </Link>
                    </div>

                    <div v-else class="flex justify-end">
                        <a
                            v-if="props.concert.ticket_link"
                            :href="props.concert.ticket_link"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="border-[2px] border-primary w-full flex items-center justify-center space-x-2 px-2 text-primary rounded-md"
                        >
                        <span>ไปยังเว็บไซต์คอนเสิร์ต</span>
                        <ArrowRightStartOnRectangleIcon
                            class="w-4 h-4 stroke-current stroke-[2px]"
                        />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
