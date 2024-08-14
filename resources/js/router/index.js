import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import Create from '../components/student/Create.vue';
import Availability from '../components/student/Availability.vue';
import View from '../components/student/View.vue';
import Schedule from '../components/student/Schedule.vue';
import Upload from '../components/student/Upload.vue';
import Rating from '../components/student/Rating.vue';
import Template from '../components/student/Template.vue';
import GenerateReport from '../components/student/GenerateReport.vue';

const routes = [
    {
        path: '/',
        component: Home,
        name: 'home'
    },
    {
        path: '/login',
        component: Login,
        name: 'login'
    },
    {
        path: '/create',
        component: Create,
        name: 'create'
    },
    {
        path: '/availability/:id',
        name: 'availability',
        component: Availability,
        props: true
    },
    {
        path: '/view/:id',
        name: 'view',
        component: View,
        props: true
    },
    {
        path: '/schedule/:id',
        name: 'schedule',
        component: Schedule,
        props: true,
        meta: { requiresAuth: true }
    },
    {
        path: '/upload-file/:id',
        name: 'upload',
        component: Upload,
        props: true,
    },
    {
        path: '/rating/:id',
        name: 'rating',
        component: Rating,
        props: true,
        meta: { requiresAuth: true }
    },
    {
        path: '/template/:id/:name',
        name: 'template',
        component: Template,
        props: true,
    },
    {
        path: '/generate-report/:id/:name',
        name: 'generateReport',
        component: GenerateReport,
        props: true
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }
});

export default router;
