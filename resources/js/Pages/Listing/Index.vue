<script setup>
import { Link } from '@inertiajs/vue3'
import ListingAddress from "@/Components/ListingAddress.vue";

defineProps({
    listings: Array
})
</script>

<template>
    <div v-for="listing in listings" :key="listing.id">
        <Link :href="route('listing.show', { listing: listing.id })">
            <ListingAddress :listing="listing"/>
        </Link>
        <Link :href="route('listing.edit', { listing: listing.id })">
            Edit
        </Link>
        <Link :href="route('listing.destroy', { listing: listing.id })" method="delete" as="button">
            Delete
        </Link>
    </div>
</template>

<style scoped>

</style>
