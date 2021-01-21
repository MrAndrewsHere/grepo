import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index'


Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => {
            return import('../components/dashboard');
        },
        meta: {
            requiresAuth: true,
        }
    },

    {
        path: '/groupRank',
        name: 'groupRank',
        component: () => {
            return import('../components/groupRank');
        },
        meta: {
            requiresAuth: true,
        }
    },


    {
        path: "/404",
        name: "404",
        component: () => import("../components/n404"),

    },
    {
        path: "/500",
        name: "500",
        component: () => import("../components/n500"),

    },
    {
        path: "/login",
        name: "login",
        component: () => import("../components/login"),
        meta: {}

    },
    {
        path: '*',
        name: 'CustomPage',
        component: () => import("../components/n404"),
    },

]


const router = new VueRouter({
    mode: 'history',
    routes
})
import {HTTP} from "../axios";

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            HTTP.post('/me').then(() => {
                next();
                return
            }).catch(() => {
                next('/login')
            })


        } else {
            next('/login')
        }

    } else {
        next()
    }
})

export default router
