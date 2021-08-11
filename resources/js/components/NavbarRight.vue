<template>
    <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        <li class="nav-item" v-if="isProfileLoaded">
            <router-link to="/account" class="nav-link">{{ name }}</router-link>
        </li>
        <li class="nav-item" v-if="isAuthenticated">
            <a href="#" class="nav-link" @click.prevent="logout">Logout</a>
        </li>
        <li class="nav-item" v-if="!isAuthenticated && !authLoading">
            <router-link to="/sanctum/login" class="nav-link">Login</router-link>
        </li>
        <li class="nav-item" v-if="!isAuthenticated && !authLoading">
            <router-link to="/sanctum/register" class="nav-link">Register</router-link>
        </li>
    </ul>
</template>

<script>
    import { mapGetters, mapState } from 'vuex'
    import { AUTH_LOGOUT } from '@/store/actions/auth'

    export default {
        computed: {
            ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded"]),
            ...mapState({
                authLoading: state => state.auth.status === "loading",
                name: state => state.user.profile.name
            })
        },
        methods: {
            logout: function() {
                this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push("/sanctum/login"))
            }
        },
    }
</script>