<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            <router-link to="/recipes" class="font-semibold text-indigo-700 hover:text-indigo-400">Recipes</router-link>
            <span> / </span>
            Recipe: {{ recipe.title }}
        </h1>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4">Title</th>
                        <th class="px-6 pt-6 pb-4">Description</th>
                        <th class="px-6 pt-6 pb-4">Created</th>
                    </tr>
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ recipe.title }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ recipe.description }}
                        </td>
                        <td class="border-t px-6 pt-6 pb-4">
                            {{ recipe.created_at | displayDateTime }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <comments :comments="recipe_comments" v-if="recipe_comments.length > 0" />
    </div>
</template>

<script>
    import ButtonPrimary from '@/components/ButtonPrimary'
    import Comments from '@/pages/Recipes/Comments'

    export default {
        components: {
            ButtonPrimary,
            Comments,
        },
        data: () => ({
            recipe: {
                title: null
            },
            recipe_comments: [],
            not_found: false,
        }),
        mounted() {
            this.setData()
        },
        methods: {
            setData() {
                axios.get('/api/recipes/' + this.$route.params.id)
                .then(res => {
                    console.log(res.data)
                    this.recipe = res.data.recipe
                    this.recipe_comments = res.data.recipe_comments
                    this.not_found = (res.data.recipe.length === 0)
                })
            },
        },
    }
</script>