<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin/Recipes/Index
        </h1>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <button-link-primary to="/admin/recipes/create">Create Recipe</button-link-primary>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">#</th>
                        <th class="px-6 pt-6 pb-4">Title</th>
                        <th class="px-6 pt-6 pb-4">Created</th>
                        <th class="px-6 pt-6 pb-4">Actions</th>
                    </tr>
                    <tr v-for="recipe in recipes.data" :key="recipe.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ recipe.id }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ recipe.title }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ recipe.created_at | displayDateTime }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            <button-link-primary :to="'/admin/recipes/' + recipe.id + '/edit'">Edit</button-link-primary>
                            Destroy
                        </td>
                    </tr>
                    <tr v-if="empty_data">
                        <td class="border-t px-6 py-4" colspan="4">No recipes found.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonLinkPrimary from '@/components/Admin/ButtonLinkPrimary'

    export default {
        components: {
            ButtonLinkPrimary
        },
        data: () => ({
            recipes: [],
            empty_data: false,
        }),
        mounted() {
            this.setData()
        },
        methods: {
            setData() {
                axios.get('/api/admin/recipes')
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.recipes = res.data
                })
            },
        }
    }
</script>
