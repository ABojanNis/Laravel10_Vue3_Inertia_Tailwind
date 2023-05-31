<script setup>
import ListingAddress from "@/Components/ListingAddress.vue";

defineProps({
    listing: Object
})
</script>

<template>
    <div>
        <ListingAddress :listing="listing" />
    </div>
</template>

<style scoped>

</style>
