<script setup>
defineProps({
    listing: Object
})
</script>

<template>
    <span>
        {{ listing.street }} {{ listing.street_nr }}, {{ listing.city }} for {{ listing.price }}
    </span>
</template>

<style scoped>

</style>
