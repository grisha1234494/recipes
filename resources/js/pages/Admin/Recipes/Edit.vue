<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin/Recipes/Edit
        </h1>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <div v-if="isloading">Loading</div>
                <div v-else>
                    <form @submit.prevent="store">
                        <div class="p-6">
                            <div class="mb-6">
                                <label class="mb-2 block">Images:</label>
                                <div class="grid grid-cols-6 gap-2 mb-2" v-if="images.length > 0">
                                    <div v-for="(image, imageIndex) in images" :key="imageIndex" :data-index="imageIndex" 
                                        class="flex justify-end w-20 h-20 border border-gray-300 rounded-md bg-center bg-no-repeat bg-cover"
                                        :style="'background-image: url(\'' + image.full_path + '\');'"
                                    >
                                        <div class="flex justify-center items-center w-6 h-6 rounded-md opacity-60 hover:opacity-100 cursor-pointer">
                                            <i class="fas fa-times-circle p-2 text-red-400 hover:text-red-600" @click="deleteImage(imageIndex)"></i>
                                        </div>
                                    </div>
                                </div>
                                <button-secondary type="button" class="mt-2" @click="deleteImages" v-if="images.length > 0">
                                    Remove Images
                                </button-secondary>
                                <button-primary type="button" @click="openImagesModal()">
                                    <span v-if="images.length > 0">Replace Images</span>
                                    <span v-else>Select Images</span>
                                </button-primary>
                                <div v-if="errors.recipe_images" class="mt-2 text-red-400">{{ displayRecipeImagesError }}</div>
                            </div>
                            <text-input v-model="form.title" :error="errors.title" id="title" label="Title" />
                            <textarea-input v-model="form.description" :error="errors.description" id="description" label="Description" />
                            <div class="pb-4 w-full">
                                <label class="mb-2 block">Ingridients:</label>
                                <select v-model="form.recipe_ingridients" :class="{ 'input-error': errors.recipe_ingridients }" label="Ingridients" multiple class="block w-full p-2 border rounded border-gray-400">
                                    <option :value="null" />
                                    <option v-for="ingridient in ingridients" :key="ingridient.id" :value="ingridient.id">{{ ingridient.title }}</option>
                                </select>
                                <div v-if="errors.recipe_ingridients" class="text-red-400">{{ displayRecipeIngridientsError }}</div>
                            </div>
                            <button-primary type="submit" :isLoading="isStoreloading">Update</button-primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import TextInput from '@/components/Admin/TextInput'
    import TextareaInput from '@/components/Admin/TextareaInput'
    import SelectInput from '@/components/Admin/SelectInput'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            TextareaInput,
            SelectInput,
            ButtonSecondary,
            ButtonPrimary,
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            form: {
                title: null,
                description: null,
                recipe_ingridients: [],
                recipe_images: [],
            },
            errors: {},
            ingridients: [],
            images: [],
        }),
        mounted() {
            this.setData()
            eventBus.$on('selectedImages', (images) => {
                this.images = images
                this.form.recipe_images = []
                for(var image of images) {
                    this.form.recipe_images.push(image.id)
                }
            })
        },
        computed: {
            displayRecipeIngridientsError() {
                return (Array.isArray(this.errors.recipe_ingridients)) ? this.errors.recipe_ingridients.join('; ') : this.errors.recipe_ingridients
            },
            displayRecipeImagesError() {
                return (Array.isArray(this.errors.recipe_images)) ? this.errors.recipe_images.join('; ') : this.errors.recipe_images
            },
        },
        methods: {
            setData() {
                axios.get('/api/admin/recipes/' + this.$route.params.id + '/edit')
                .then(res => {
                    this.form.title = res.data.recipe.title
                    this.form.description = res.data.recipe.description

                    this.form.recipe_ingridients = res.data.recipe_ingridients
                    this.form.recipe_images = res.data.recipe_images

                    this.images = res.data.images
                    this.ingridients = res.data.ingridients
                })
                .finally(() => this.isloading = false)
            },
            store() {
                this.isStoreloading = true
                axios.put('/api/admin/recipes/' + this.$route.params.id, this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.$router.push({name: 'admin-recipes-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.')
                        this.errors = err.response.data.errors
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
            deleteImage(index) {
                this.images.splice(index, 1)
                this.form.recipe_images.splice(index, 1)
            },
            deleteImages() {
                this.images = []
                this.form.recipe_images = []
            },
            openImagesModal() {
                eventBus.$emit('openImagesModal')
            }
        },
    }
</script>