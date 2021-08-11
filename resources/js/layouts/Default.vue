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
                            <router-link to="/" class="nav-link">Front</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/admin" class="nav-link">Admin</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/recipes" class="nav-link">Recipes</router-link>
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
        <modal-login v-if="modalLoginOpen"></modal-login>
        <modal-register v-if="modalRegisterOpen"></modal-register>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import NavbarRight from '@/components/NavbarRight'
    import ModalLogin from '@/components/ModalLogin'
    import ModalRegister from '@/components/ModalRegister'

    export default {
        components: {
            NavbarRight,
            ModalLogin,
            ModalRegister,
        },
        data: () => ({
            modalLoginOpen: false,
            modalRegisterOpen: false,
        }),
        mounted() {
            eventBus.$on('openLoginModal', () => {
                this.modalLoginOpen = true
            })
            eventBus.$on('closeLoginModal', () => {
                this.modalLoginOpen = false
            })
            eventBus.$on('openRegisterModal', () => {
                this.modalRegisterOpen = true
            })
            eventBus.$on('closeRegisterModal', () => {
                this.modalRegisterOpen = false
            })
        },
    }
</script>