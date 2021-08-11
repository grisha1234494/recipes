import { USER_REQUEST, USER_ERROR, USER_SUCCESS } from '@/store/actions/user'
import { AUTH_LOGOUT } from '@/store/actions/auth'
import Vue from 'vue'

const state = {status: '', profile: {}}

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name
}

const actions = {
    [USER_REQUEST]: ({ commit, dispatch }) => {
        commit(USER_REQUEST);
        axios.get('/api/sanctum/user')
        .then(resp => {
            commit(USER_SUCCESS, resp.data.user)
        })
        .catch(() => {
            commit(USER_ERROR)
            dispatch(AUTH_LOGOUT)
        })
    }
}

const mutations = {
    [USER_REQUEST]: state => {
        state.status = 'loading'
    },
    [USER_SUCCESS]: (state, user) => {
        state.status = 'success'
        Vue.set(state, 'profile', user)
    },
    [USER_ERROR]: state => {
        state.status = 'error'
    },
    [AUTH_LOGOUT]: state => {
        state.profile = {}
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}