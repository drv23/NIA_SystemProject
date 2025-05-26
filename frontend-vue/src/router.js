import {createRouter, createWebHistory} from "vue-router"
import DefaultLayout from "./layouts/DefaultLayout.vue"
import Dashboard from "./pages/Dashboard.vue"
// import Inventory from "./pages/Inventory.vue"
import Login from "./pages/Login.vue"
import Signup from "./pages/Signup.vue"
import NotFound from "./pages/NotFound.vue"
import Inventory from "./pages/Inventory.vue"
import Admin from "./pages/Admin.vue"
import Analytics from "./pages/Analytics.vue"
import AddItem from "./pages/AddItem.vue"
import Maintenance from "./pages/Maintenance.vue"
import AddAccount from './pages/AddAccount.vue'

const routes = [
    {
        path: '/',
        component: DefaultLayout,
        children: [
            {
                path: '/dashboard',
                name: 'Dashboard',
                component: Dashboard
            },
            {
                path: '/inventory',
                name: 'Inventory',
                component: Inventory
            },
            {
                path: '/add-item',
                name: 'AddItem',
                component: AddItem
            },
            {
                path: '/maintenance',
                name: 'Maintenance',
                component: Maintenance
            },
            {
                path: '/admin',
                name: 'Admin',
                component: Admin
            },{
                path: '/analytics',
                name: 'Analytics',
                component: Analytics
            },
            {
        path: '/add-account',
        name: 'AddAccount',
        component: AddAccount
    },
        ]
    },
    {
        path: '',
        redirect: '/Login',
        component: Login
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/signup',
        name: 'Signup',
        component: Signup
    },
    
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router 