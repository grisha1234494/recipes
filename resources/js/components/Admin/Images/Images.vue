<template>
    <div>
        <div v-if="isloading">Loading</div>
        <div v-else>
            <div class="grid grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-md shadow">
                    <form @submit.prevent="store">
                        <div class="p-3">
                            <input type="file" multiple class="hidden" ref="images" @change="updateImagesPreview">
                            <label class="block mb-2 font-medium text-sm text-gray-700" for="images" v-if="!(imagesPreview.length > 0)">Upload Images</label>
                            <div class="grid grid-cols-6 gap-2 mb-2" v-if="imagesPreview.length > 0">
                                <span v-for="(imagePreview, imagePreviewIndex) in imagesPreview" :key="imagePreviewIndex" :data-index="imagePreviewIndex" 
                                    class="block w-20 h-20 border border-gray-300 rounded-md bg-center bg-no-repeat bg-cover"
                                    :style="'background-image: url(\'' + imagePreview + '\');'">
                                </span>
                            </div>
                            <div class="flex">
                                <button-secondary class="mt-2 mr-2" type="button" @click="selectToUpload">
                                    Select Images
                                </button-secondary>
                                <button-secondary class="mt-2 mr-2" type="button" @click="deleteImages" v-if="imagesPreview.length > 0">
                                    Remove Images
                                </button-secondary>
                                <button-primary type="submit" class="mt-2" v-if="imagesPreview.length > 0">Upload</button-primary>
                            </div>
                            <div v-if="errors.images" class="mt-2 text-red-400">{{ displayImagesError }}</div>
                        </div>
                    </form>
                </div>
                <div v-if="selectable && imagesSelected.length > 0" class="bg-white rounded-md shadow p-3">
                    <div class="grid grid-cols-6 gap-2 mb-2">
                        <div v-for="(imageSelected, imageSelectedIndex) in imagesSelected" :key="imageSelectedIndex" :data-index="imageSelectedIndex" 
                            class="flex justify-end w-20 h-20 border border-gray-300 rounded-md bg-center bg-no-repeat bg-cover"
                            :style="'background-image: url(\'' + imageSelected.full_path + '\');'"
                            >
                            <div class="flex justify-center items-center w-6 h-6 rounded-md opacity-40 bg-gray-200 hover:opacity-100 hover:bg-gray-400 cursor-pointer">
                                <i class="fas fa-times-circle p-2 text-red-400 hover:text-red-600" @click="deleteFromSelected(imageSelectedIndex)"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <button-primary type="button" @click="returnSelected()">Paste</button-primary>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="px-6 pt-6 pb-4">#</th>
                            <th class="px-6 pt-6 pb-4">Image</th>
                            <th class="px-6 pt-6 pb-4">User ID</th>
                            <th class="px-6 pt-6 pb-4">Path</th>
                            <th class="px-6 pt-6 pb-4">Actions</th>
                        </tr>
                        <tr v-for="(image, image_index) in images.data" :key="image.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t px-6 pt-6 pb-4">
                                {{ image.id }}
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                <div class="block w-40 border border-gray-300">
                                    <img :src="image.full_path">
                                </div>
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                {{ image.user_id }}
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                {{ image.path }}
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                <button-primary type="button" @click="select(image)" v-if="selectable && imagesSelectedIds.indexOf(image.id) < 0">
                                    Select
                                </button-primary>
                                <button-secondary type="button" @click="destroy(image.id, image_index)">
                                    Destroy
                                </button-secondary>
                            </td>
                        </tr>
                        <tr v-if="empty_data">
                            <td class="border-t px-6 py-4" colspan="4">No images found.</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import ButtonPrimary from '@/components/Admin/ButtonPrimary'
    import ButtonSecondary from '@/components/Admin/ButtonSecondary'
    import ErrorMessage from '@/components/Admin/ErrorMessage'

    export default {
        components: {
            ButtonPrimary,
            ButtonSecondary,
            ErrorMessage,
        },
        props: {
            selectable: {
                type: Boolean,
                default: true
            }
        },
        data: () => ({
            isloading: true,
            isStoreloading: false,
            images: [],
            empty_data: false,
            form: {
                images: [],
            },
            errors: {},
            imagesPreview: [],
            imagesSelected: [],
            imagesSelectedIds: [],
        }),
        mounted() {
            this.setData()
        },
        computed: {
            displayImagesError() {
                return (Array.isArray(this.errors.images)) ? this.errors.images.join('; ') : this.errors.images
            },
        },
        methods: {
            setData() {
                axios.get('/api/admin/images')
                .then(res => {
                    if(res.data.data.length === 0) {
                        this.empty_data = true
                    }
                    this.images = res.data
                })
                .finally(() => this.isloading = false)
            },
            store() {
                var formData = new FormData()
                for(var i = 0; i < this.form.images.length; i++) {
                    var image = this.form.images[i];
                    formData.append('images[' + i + ']', image);
                }
                this.isStoreloading = true
                axios.post('/api/admin/images', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success)
                    this.images.data = res.data.images.concat(this.images.data)
                    this.imagesPreview = []
                    this.form.images = []
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
            select(image) {
                this.imagesSelected.push(image)
                this.imagesSelectedIds.push(image.id)
            },
            deleteFromSelected(index) {
                this.imagesSelected.splice(index, 1)
                this.imagesSelectedIds.splice(index, 1)
            },
            returnSelected() {
                eventBus.$emit('selectedImages', this.imagesSelected)
                this.close()
            },
            selectToUpload() {
                this.$refs.images.click()
            },
            updateImagesPreview() {
                for(var fileI = 0; fileI < this.$refs.images.files.length; fileI++) {
                    const image = this.$refs.images.files[fileI]
                    if (! image) return
                    const reader = new FileReader()
                    reader.onload = (e) => {
                        this.imagesPreview.push(e.target.result)
                    }
                    reader.readAsDataURL(image)
                    this.form.images.push(image)
                }
            },
            deleteImages() {
                this.imagesPreview = []
                this.form.images = []
            },
            destroy(id, index) {
                if(confirm('Are you sure you want to delete this image?')) {
                    axios.delete('/api/admin/images/' + id)
                    .then(res => {
                        eventBus.$emit('flash-message', 'success', res.data.success)
                        this.images.data.splice(index, 1)
                    })
                }
            },
            close() {
                eventBus.$emit('closeImagesModal')
            }
        },
    }
</script>