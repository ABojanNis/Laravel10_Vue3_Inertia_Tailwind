<script setup>
import Box from "../../../Components/UI/Box.vue";
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import NProgress from 'nprogress';
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    listing: Object
});

router.on('progress', (event) => {
    if (event.detail.progress.percentage) {
        NProgress.set((event.detail.progress.percentage / 100) * 0.9)
    }
})

const form = useForm({
    images: []
})

const imageErrors = computed(() => Object.values(form.errors))

const canUpload = computed(() => form.images.length)

const upload = () => {
    form.post(
        route('realtor.listing.image.store', { listing: props.listing.id }),
        {
            onSuccess: () => form.reset('images'),
        },
    )
}

const addFiles = (event) => {
    for (const image of event.target.files) {
        form.images.push(image)
    }
}

const reset = () => form.reset('images')
</script>

<template>
    <Box>
        <template #header>Upload New Images</template>
        <form @submit.prevent="upload">
            <section class="flex items-center my-4 gap-2">
                <input
                    class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:hover:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"
                    type="file"
                    multiple
                    @input="addFiles"
                />
                <button
                    type="submit"
                    class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
                    :disabled="!canUpload"
                >Upload
                </button>
                <button type="reset" class="btn-outline" @click="reset">Reset</button>
            </section>

            <div v-if="imageErrors.length" class="input-error">
                <div v-for="(error, index) in imageErrors" :key="index">
                    {{ error }}
                </div>
            </div>
        </form>
    </Box>

    <Box v-if="listing.images.length" class="mt-4">
        <template #header>Current Listing Images</template>
        <section class="mt-4 grid grid-cols-3 gap-4">
            <div
                v-for="image in listing.images"
                :key="image.id"
                class="flex group relative items-center justify-center"
            >
                <img :src="image.src" class="rounded-md group-hover:opacity-25" alt="Realtor image"/>
                <Link
                    :href="route('realtor.listing.image.destroy', { listing: props.listing.id, image: image.id })"
                    method="delete"
                    as="button"
                    class="invisible group-hover:visible absolute text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 dark:hover:bg-blue-500"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </Link>
            </div>
        </section>
    </Box>
</template>

<style scoped>

</style>
