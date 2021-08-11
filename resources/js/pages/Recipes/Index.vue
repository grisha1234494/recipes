<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Recipes/Index
        </h1>
        <form @submit.prevent="setFilter">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="grid grid-cols-4 gap-6">
                    <div class="">
                        <div class="bg-white rounded-md shadow p-6">
                            <h4 class="font-semibold text-xl">Ingridients</h4>
                            <div v-for="ingridient in ingridients" :key="ingridient.id" class="mt-6">
                                <div class="flex items-center mb-3">
                                    <input class="mr-2" type="checkbox" :id="'ingridient-' + ingridient.id" :value="ingridient.id" v-model="filter.ingridients" />
                                    <label :for="'ingridient-' + ingridient.id">{{ ingridient.title }}</label>
                                </div>
                            </div>
                            <div v-if="empty_ingridients_data">
                                <h4>No ingridients found.</h4>
                            </div>
                            <div v-else>
                                <button-primary type="submit">Filter</button-primary>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="bg-white rounded-md shadow p-6">
                            <div v-for="recipe in recipes.data" :key="recipe.id">
                                <div class="mb-6">
                                    <div>{{ recipe.title }}</div>
                                    <div>{{ recipe.created_at | displayDateTime }}</div>
                                    <div>
                                        <button-link-primary :to="'/recipes/' + recipe.id" class="mr-1">Show</button-link-primary>
                                    </div>
                                </div>
                            </div>
                            <div v-if="empty_recipes_data">
                                <h4>No recipes found.</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import ButtonLinkPrimary from '@/components/Admin/ButtonLinkPrimary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            ButtonLinkPrimary,
            ButtonPrimary,
        },
        data: () => ({
            recipes: [],
            empty_recipes_data: false,
            ingridients: [],
            empty_ingridients_data: false,
            filter: {
                ingridients: []
            },
        }),
        mounted() {
            this.setData()
        },
        methods: {
            setData() {
                axios.get('/api/recipes')
                .then(res => {
                    this.setResponseData(res)
                })
            },
            setFilter() {
                console.log(this.filter)
                axios.get('api/recipes', {params: {
                    ingridients: this.filter.ingridients
                }})
                .then(res => {
                    this.setResponseData(res)
                })
            },
            setResponseData(res) {
                console.log(res.data)
                this.recipes = res.data.recipes
                this.ingridients = res.data.ingridients
                this.filter.ingridients = res.data.filterIngridients

                this.empty_recipes_data = (res.data.recipes.data.length === 0)
                this.empty_ingridients_data = (res.data.ingridients.length === 0)
            }
        },
    }
</script>