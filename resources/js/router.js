import vueRouter from 'vue-router';
import Vue from 'vue';
import store from '@/store'

Vue.use(vueRouter);

import Index from '@/pages/Index';

import SanctumRegister from '@/pages/Sanctum/Register';
import SanctumLogin from '@/pages/Sanctum/Login';

import RecipesIndex from '@/pages/Recipes/Index';
import RecipesShow from '@/pages/Recipes/Show';

import AdminIndex from '@/pages/Admin/Index';

import AdminRecipesIndex from '@/pages/Admin/Recipes/Index';
import AdminRecipesCreate from '@/pages/Admin/Recipes/Create';
import AdminRecipesEdit from '@/pages/Admin/Recipes/Edit';

import AdminIngridientsIndex from '@/pages/Admin/Ingridients/Index';
import AdminIngridientsCreate from '@/pages/Admin/Ingridients/Create';
import AdminIngridientsEdit from '@/pages/Admin/Ingridients/Edit';

import AdminImagesIndex from '@/pages/Admin/Images/Index';

const routes = [
    {name: 'index', path: '/', component: Index, meta: {layout: 'default'}},

    {name: 'sanctum-register', path: '/sanctum/register', component: SanctumRegister, meta: {layout: 'default', notAuth: true}},
    {name: 'sanctum-login', path: '/sanctum/login', component: SanctumLogin, meta: {layout: 'default', notAuth: true}},

    {name: 'recipes-index', path: '/recipes/', component: RecipesIndex, meta: {layout: 'default'}},
    {name: 'recipes-show', path: '/recipes/:id', component: RecipesShow, meta: {layout: 'default'}},

    {name: 'admin-index', path: '/admin/', component: AdminIndex, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-recipes-index', path: '/admin/recipes/', component: AdminRecipesIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-recipes-create', path: '/admin/recipes/create', component: AdminRecipesCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-recipes-edit', path: '/admin/recipes/:id/edit', component: AdminRecipesEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-ingridients-index', path: '/admin/ingridients', component: AdminIngridientsIndex, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-ingridients-create', path: '/admin/ingridients/create', component: AdminIngridientsCreate, meta: {layout: 'admin-default', auth: true}},
    {name: 'admin-ingridients-edit', path: '/admin/ingridients/:id/edit', component: AdminIngridientsEdit, meta: {layout: 'admin-default', auth: true}},

    {name: 'admin-images-index', path: '/admin/images', component: AdminImagesIndex, meta: {layout: 'admin-default', auth: true}},
];

const router = new vueRouter({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(m => m.meta.auth)) {
        if (!store.getters.isAuthenticated) {
            next('/sanctum/login')
        }
    }
    if (to.matched.some(m => m.meta.notAuth)) {
        if (store.getters.isAuthenticated) {
            next('/')
        }
    }
    return next()
})

export default router;