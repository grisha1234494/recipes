<template>
    <div>
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin/Ingridients/Create
        </h1>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="max-w-3xl bg-white rounded-md shadow">
                <form @submit.prevent="store">
                    <div class="p-6">
                        <text-input v-model="form.title" :error="errors.title" id="title" label="Title" />
                        <button-primary type="submit" :isLoading="isStoreloading">Store</button-primary>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import TextInput from '@/components/Admin/TextInput'
    import ButtonPrimary from '@/components/Admin/ButtonPrimary'

    export default {
        components: {
            TextInput,
            ButtonPrimary,
        },
        data: () => ({
            isStoreloading: false,
            form: {
                title: null,
            },
            errors: {},
        }),
        methods: {
            store() {
                this.isStoreloading = true
                axios.post('/api/admin/ingridients', this.form)
                .then(res => {
                    this.errors = {}
                    eventBus.$emit('flash-message', 'success', res.data.success);
                    this.$router.push({name: 'admin-ingridients-index'})
                })
                .catch(err => {
                    this.errors = {}
                    if(err.response.data.errors) {
                        eventBus.$emit('flash-message', 'error', 'The given data was invalid.');
                        this.errors = err.response.data.errors
                    }
                })
                .finally(() => this.isStoreloading = false)
            },
        },
    }
</script>