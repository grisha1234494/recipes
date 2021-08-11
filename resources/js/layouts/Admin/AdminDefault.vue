<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <router-link to="/" class="navbar-brand">Laravel</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <!--<a href="{{ route('index', '/') }}" class="nav-link">Front</a>-->
                            <router-link to="/" class="nav-link">Front</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin" class="nav-link">Admin</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin/recipes" class="nav-link">Admin/Recipes</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin/ingridients" class="nav-link">Admin/Ingridients</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin/images" class="nav-link">Admin/Images</router-link>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <navbar-right />
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <router-view></router-view>
            </div>
        </main>
        <modal-images v-if="modalImagesOpen"></modal-images>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import NavbarRight from '@/components/NavbarRight'
    import ModalImages from '@/components/Admin/Images/ModalImages'

    export default {
        components: {
            NavbarRight,
            ModalImages,
        },
        data: () => ({
            modalImagesOpen: false,
        }),
        mounted() {
            eventBus.$on('openImagesModal', () => {
                this.modalImagesOpen = true
            })
            eventBus.$on('closeImagesModal', () => {
                this.modalImagesOpen = false
            })
        },
    }
</script>