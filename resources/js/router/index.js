import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router';
import DefaultLayout from '../../views/components/layout/DefaultLayout.vue';

const routes = [
      {
            path:'/login',
            name:'Login',
            component: () =>
            import(/* webpackChunkName: "Login" */ '../../views/components/login/Login.vue'),
      },
      {
            path: '/',
            name: 'home',
            meta: { requireAuth: true },
            component: DefaultLayout,
            redirect: '/home',
            children: [
                  {
                        path: '/home',
                        name: 'home',
                        component: () =>
                              import(/* webpackChunkName: "Home" */ '../../views/components/home/Home.vue'),
                  },
                  {
                        path:'/profile',
                        name:'profile',
                        component: () =>
                              import(/* webpackChunkName: "Profile" */ '../../views/components/profile/Profile.vue'),
                  },
                  {
                        path: '/customer-type',
                        name: 'customerType',
                        component: () =>
                              import(/* webpackChunkName: "CustomerType001" */ '../../views/components/customer_type/cus001/CustomerType001.vue'),
                  },
                  {
                        path: '/customer-type/:type/:id?',
                        name: 'customer-type-register-edit',
                        component: () =>
                              import(/* webpackChunkName: "CustomerType002" */ '../../views/components/customer_type/cus002/CustomerType002.vue')
                  },
                  {
                        path: '/customer',
                        name: 'customer',
                        component: () =>
                              import(/* webpackChunkName: "Customer001" */ '../../views/components/customer/cus001/Customer001.vue'),
                  },
                  {
                        path: '/customer/:type/:id?',
                        name: 'customer-register-edit',
                        component: () =>
                              import(/* webpackChunkName: "Customer002" */ '../../views/components/customer/cus002/Customer002.vue'),
                        props:true
                  },
                  {
                        path: '/loan',
                        name: 'loan',
                        component: () =>
                              import(/* webpackChunkName: "Loan001" */ '../../views/components/loan/loan001/Loan001.vue'),
                  },
                  {
                        path: '/loan/create',
                        name: 'loan-create',
                        component: () =>
                              import(/* webpackChunkName: "Loan001" */ '../../views/components/loan/loan002/Loan002.vue'),
                  },
                  {
                        path: '/loan/schedules/:id?',
                        name: 'loan-schedules',
                        component: () =>
                              import(/* webpackChunkName: "Loan001" */ '../../views/components/loan/loan003/Loan003.vue'),
                  },
                  {
                        path: '/summary-loan',
                        name: 'summaryLoan',
                        component: () =>
                              import(/* webpackChunkName: "SummarLoan001" */ '../../views/components/summary_loan/sum001/SummaryLoan001.vue'),
                  },
            ]
      },
]

const router = createRouter({
      history: createWebHashHistory(),
      routes,
      scrollBehavior() {
            // always scroll to top
            return { top: 0 }
      },
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.meta.requireAuth && !token) {
        next({name: 'Login'});
    } else if (token && to.name === 'Login'){
        next({name: 'home'});
    } else {
        next();
    }
})

export default router
