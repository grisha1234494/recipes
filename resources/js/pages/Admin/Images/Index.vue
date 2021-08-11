<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin/Images/Index
        </h1>
        <div class="max-w-7xl mx-auto pt-10 sm:px-6 lg:px-8">
            <images :selectable="false"></images>
        </div>
    </div>
</template>

<script>
    import Images from '@/components/Admin/Images/Images'

    export default {
        components: {
            Images,
        },
    }
</script>