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

const promoterPicturePlaceholder = computed(() => {
    return (promoter) => {
        const name = promoter?.fullname;
        return isDarkMode.value
            ? `https://ui-avatars.com/api/?name=${name}&background=1c1423&color=ffffff`
            : `https://ui-avatars.com/api/?name=${name}&background=e5e7eb&color=000000`;
    };
});

const displayEventType = computed(() => {
    const eventType = eventTypes.find(
        (e) => e.value === props.concert.event_type,
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
    formattedDate(props.concert.start_sale_date),
);
const endSaleDate = computed(() => formattedDate(props.concert.end_sale_date));
const startShowDate = computed(() =>
    formattedDate(props.concert.start_show_date),
);
const endShowDate = computed(() => formattedDate(props.concert.end_show_date));
const startShowTime = computed(() =>
    formattedTime(props.concert.start_show_time),
);
const endShowTime = computed(() => formattedTime(props.concert.end_show_time));
const priceMin = computed(() => formattedPrice(props.concert.price_min));
const priceMax = computed(() => formattedPrice(props.concert.price_max));

const artistPicturePlaceholder = computed(() => {
    return (artist) => {
        return isDarkMode.value
            ? `https://ui-avatars.com/api/?name=${encodeURIComponent(
                  artist.name,
              )}&background=ff1493&color=ffffff`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(
                  artist.name,
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
        query,
    )}`;
});

const followConcert = (follow) => {
    router.post(
        route("concert.follow", props.concert.id),
        {
            is_following: !follow,
        },
        { preserveState: true },
    );
};
</script>

<template>
    <div
        class="mx-auto max-w-xl lg:max-w-fit bg-card shadow-xl rounded-md space-y-2 lg:space-y-6 relative"
    >
        <div class="lg:flex px-6 pt-6 space-y-2 lg:space-y-0">
            <div class="flex-none w-fit lg:mr-4">
                <div class="lg:h-[444px]">
                    <img
                        :src="photoPreview"
                        class="h-full object-cover rounded-md"
                        alt="Concert Poster"
                    />
                </div>
            </div>

            <div class="flex flex-col min-w-[300px] justify-between pb-2">
                <div class="flex flex-col">
                    <div class="flex space-x-2">
                        <div
                            class="inline-flex items-center bg-primary rounded-md px-3 h-6 text-sm text-white font-semibold"
                        >
                            {{ displayEventType }}
                        </div>
                        <div
                            class="inline-flex items-center bg-secondary rounded-md px-3 h-6 text-sm text-white font-semibold"
                        >
                            {{ displayGenre }}
                        </div>
                        <div
                            v-if="props.role == 'admin'"
                            class="absolute right-6"
                        >
                            <template v-if="props.concert.origin">
                                <span
                                    v-if="props.concert.origin == 'The Concert'"
                                    class="inline-flex items-center bg-[#000000] dark:bg-transparent rounded-md px-2 h-6"
                                >
                                    <img
                                        src="https://cdn.theconcert.com/dist/2.0.303/assets/images/tcc-main-logo.svg"
                                        alt="The Concert"
                                        class="h-4"
                                    />
                                </span>
                                <span
                                    v-if="props.concert.origin == 'Ticketier'"
                                    class="inline-flex items-center dark:bg-[#ffffff] rounded-md px-2 h-6"
                                >
                                    <img
                                        src="https://www.ticketier.com/ticketier_logo_text.svg"
                                        alt="Ticketier"
                                        class="h-4"
                                    />
                                </span>
                                <span
                                    v-if="props.concert.origin = 'All Ticket'"
                                    class="inline-flex items-center bg-[#333333] dark:bg-transparent rounded-md px-2 h-6"
                                >
                                    <img
                                        src="https://atkmedia.allticket.com/assets/images/allticket-logo.png?_dat=20260315"
                                        alt="All Ticket"
                                        class="h-4"
                                    />
                                </span>
                            </template>

                            <template v-if="props.concert.promoter_id">
                                <div class="flex items-center">
                                    <Link
                                        v-if="props.role === 'admin'"
                                        :href="
                                            route('admin.promoter.detail', {
                                                promoter:
                                                    props.concert.promoter_id,
                                            })
                                        "
                                    >
                                        <img
                                            :src="
                                                promoterPicturePlaceholder(
                                                    props.concert.promoter,
                                                )
                                            "
                                            alt="Promoter Icon"
                                            class="w-6 h-6 border-2 border-primary rounded-full object-cover"
                                        />
                                    </Link>
                                </div>
                            </template>
                        </div>
                    </div>

                    <h1
                        class="custom-scrollbar mt-1 font-semibold text-2xl lg:text-4xl text-text break-words line-clamp-2 py-1"
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

                <div class="flex flex-col mt-12 space-y-1 pl-1">
                    <div
                        v-if="props.concert.start_sale_date"
                        class="flex items-center font-semibold"
                    >
                        <div
                            class="flex items-center space-x-1 mr-2 border-2 border-primary rounded-md px-2 py-1"
                        >
                            <TicketIcon class="flex-none h-6 w-6" />
                            <span class="text-center"> จำหน่ายบัตร </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{{ startSaleDate }}</span>
                            <div
                                v-if="props.concert.end_sale_date"
                                class="bg-text h-[3px] w-2 mt-1"
                            ></div>
                            <span v-if="props.concert.end_sale_date">
                                {{ endSaleDate }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="props.concert.start_show_date"
                        class="flex items-center font-semibold"
                    >
                        <div
                            class="flex items-center space-x-1 mr-2 border-2 border-primary rounded-md px-2 py-1"
                        >
                            <CalendarIcon class="flex-none h-6 w-6" />
                            <span class="text-center"> วันที่แสดง </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{{ startShowDate }}</span>
                            <div
                                v-if="props.concert.end_sale_date"
                                class="bg-text h-[3px] w-2 mt-1"
                            ></div>
                            <span v-if="props.concert.end_show_date">
                                {{ endShowDate }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="props.concert.start_show_time"
                        class="flex items-center font-semibold"
                    >
                        <div
                            class="flex items-center space-x-1 mr-2 border-2 border-primary rounded-md px-2 py-1"
                        >
                            <ClockIcon class="flex-none h-6 w-6" />
                            <span class="text-center"> เวลาแสดง </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span>{{ startShowTime }}</span>
                            <div
                                v-if="props.concert.end_show_time"
                                class="bg-text h-[3px] w-2 mt-1"
                            ></div>
                            <span v-if="props.concert.end_show_time">
                                {{ endShowTime }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="props.concert.price_min"
                        class="flex items-center font-semibold"
                    >
                        <div
                            class="flex items-center space-x-1 mr-2 border-2 border-primary rounded-md px-2 py-1"
                        >
                            <BanknotesIcon class="flex-none h-6 w-6" />
                            <span class="text-center"> ราคาบัตร </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span v-if="props.concert.price_min">{{
                                priceMin
                            }}</span>
                            <div
                                v-if="props.concert.end_show_time"
                                class="bg-text h-[3px] w-2 mt-1"
                            ></div>
                            <span v-if="props.concert.price_max">
                                {{ priceMax }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="
                            props.concert.province_id ||
                            props.concert.venue_name
                        "
                        class="flex items-center font-semibold"
                    >
                        <div
                            class="flex items-center space-x-1 mr-2 border-2 border-primary rounded-md px-2 py-1"
                        >
                            <MapPinIcon class="flex-none h-6 w-6" />
                            <span
                                v-if="props.concert.province_id"
                                class="text-center"
                            >
                                {{
                                    props.concert.venue_name ||
                                    [
                                        props.concert.latitude,
                                        props.concert.longitude,
                                    ].every(Boolean)
                                        ? provinceName
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
                            <span v-if="props.concert.venue_name">
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
                                'custom-scrollbar max-h-fit':
                                    props.role === 'admin' ||
                                    props.role === 'promoter',
                            }"
                            class="text-center w-full min-h-fit overflow-auto p-2 whitespace-pre-wrap"
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
                        class="flex justify-end space-x-2 font-semibold"
                    >
                        <Link
                            :href="editHref"
                            class="flex w-fit justify-center px-2 py-1 items-center space-x-1 border-[2px] border-secondary rounded-md"
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
                            class="flex w-fit justify-center px-2 py-1 items-center space-x-1 border-[2px] border-red-600 rounded-md"
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
