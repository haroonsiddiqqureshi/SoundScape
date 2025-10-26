<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DropdownSelector from "@/Components/DropdownSelector.vue";

// Define props to accept the form object from the parent (Create.vue)
const props = defineProps({
    form: Object,
});

const BusinessCategories = [
    { value: "individual", name: "โปรโมเตอร์ส่วนบุคคล" },
    { value: "company", name: "บริษัทจัดการศิลปิน" },
    { value: "nightclub", name: "ไนท์คลับ/สถานที่" },
    { value: "other", name: "อื่นๆ" },
];

// Define the event this component can emit
const emit = defineEmits(["submit"]);

// Submit function just emits the event up to the parent
const submit = () => {
    emit("submit");
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <input
                    id="fullname"
                    type="text"
                    class="block w-full bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                    v-model="form.fullname"
                    autofocus
                    placeholder="ชื่อ-นามสกุล"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            form.errors.fullname,
                    }"
                />
            </div>

            <div>
                <input
                    id="business_name"
                    type="text"
                    class="block w-full bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                    v-model="form.business_name"
                    placeholder="ชื่อธุรกิจ"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            form.errors.business_name,
                    }"
                />
            </div>

            <div>
                <DropdownSelector
                        placeholder="ประเภทธุรกิจ"
                        v-model="props.form.business_category"
                        :options="BusinessCategories"
                        :class="{
                            'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                props.form.errors.business_category,
                        }"
                    />
            </div>

            <div>
                <input
                    id="business_address"
                    type="text"
                    class="block w-full bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                    v-model="form.business_address"
                    placeholder="ที่อยู่ธุรกิจ"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            form.errors.business_address,
                    }"
                />
            </div>

            <div>
                <textarea
                    id="bio"
                    v-model="form.bio"
                    class="block resize-none w-full bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                    rows="4"
                    placeholder="ประวัติย่อเกี่ยวกับคุณหรือธุรกิจของคุณ"
                    :class="{
                        'outline-dashed outline-primary -outline-offset-4 rounded-md':
                            form.errors.bio,
                    }"
                ></textarea>
            </div>

            <div class="col-span-1">
                <h3 class="text-lg font-medium">Social Links</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Provide links to your social media profiles for
                    verification.
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div v-for="(value, key) in form.social_links" :key="key">
                        <input
                            :id="key"
                            type="url"
                            class="block w-full bg-background rounded-md font-medium border-none focus:ring-transparent placeholder:font-normal placeholder:text-text-medium"
                            v-model="form.social_links[key]"
                            :placeholder="`https://${key}.com/`"
                            :class="{
                                'outline-dashed outline-primary -outline-offset-4 rounded-md':
                                    form.errors[`social_links.${key}`],
                            }"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <PrimaryButton
                :class="{ 'cursor-not-allowed': form.processing }"
                :disabled="form.processing"
            >
                Create Promoter Account
            </PrimaryButton>
        </div>
    </form>
</template>
