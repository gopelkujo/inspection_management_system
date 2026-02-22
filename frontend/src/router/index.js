import { createRouter, createWebHistory } from 'vue-router';
import InspectionList from '../views/InspectionList.vue';
import CreateInspection from '../views/CreateInspection.vue';
import InspectionDetail from '../views/InspectionDetail.vue';

const routes = [
    {
        path: '/',
        name: 'InspectionList',
        component: InspectionList,
    },
    {
        path: '/create',
        name: 'CreateInspection',
        component: CreateInspection,
    },
    {
        path: '/inspection/:id',
        name: 'InspectionDetail',
        component: InspectionDetail,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
