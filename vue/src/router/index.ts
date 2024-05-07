import { createRouter, createWebHashHistory } from "vue-router";
import DefaultLayout from "../layouts/DefaultLayout.vue";

const routes = [
    {
        path: "/login",
        name: "Login",
        component: () =>
            import(
                /* webpackChunkName: "Login" */ "../views/auth/log/Login.vue"
            ),
    },
    {
        path: "/register",
        name: "Register",
        component: () =>
            import(
                /* webpackChunkName: "Register" */ "../views/auth/reg/Register.vue"
            ),
    },
    {
        path: "/loan/print",
        name: "printLoan",
        component: () =>
            import(
                /* webpackChunkName: "SummarLoan002" */ "../views/summary_loan/sum002/SummaryLoan002.vue"
            ),
    },
    {
        path: "/",
        name: "home",
        meta: { requireAuth: true },
        component: DefaultLayout,
        redirect: "/home",
        children: [
            {
                path: "/home",
                name: "home",
                component: () =>
                    import(
                        /* webpackChunkName: "Home" */ "../views/home/Home.vue"
                    ),
            },
            {
                path: "/profile",
                name: "profile",
                component: () =>
                    import(
                        /* webpackChunkName: "Profile" */ "../views/profile/Profile.vue"
                    ),
            },
            {
                path: "/customer-type",
                name: "customerType",
                component: () =>
                    import(
                        /* webpackChunkName: "CustomerType001" */ "../views/customer_type/cus001/CustomerType001.vue"
                    ),
            },
            {
                path: "/customer-type/:type/:id?",
                name: "customer-type-register-edit",
                component: () =>
                    import(
                        /* webpackChunkName: "CustomerType002" */ "../views/customer_type/cus002/CustomerType002.vue"
                    ),
            },
            {
                path: "/customer",
                name: "customer",
                component: () =>
                    import(
                        /* webpackChunkName: "Customer001" */ "../views/customer/cus001/Customer001.vue"
                    ),
            },
            {
                path: "/customer/:type/:id?",
                name: "customer-register-edit",
                component: () =>
                    import(
                        /* webpackChunkName: "Customer002" */ "../views/customer/cus002/Customer002.vue"
                    ),
                props: true,
            },
            {
                path: "/loan",
                name: "loan",
                component: () =>
                    import(
                        /* webpackChunkName: "Loan001" */ "../views/loan/loan001/Loan001.vue"
                    ),
            },
            {
                path: "/loan/create",
                name: "loan-create",
                component: () =>
                    import(
                        /* webpackChunkName: "Loan001" */ "../views/loan/loan002/Loan002.vue"
                    ),
            },
            {
                path: "/loan/schedules",
                name: "loan-schedules",
                component: () =>
                    import(
                        /* webpackChunkName: "Loan001" */ "../views/loan/loan003/loan003.vue"
                    ),
            },
            {
                path: "/summary-loan",
                name: "summaryLoan",
                component: () =>
                    import(
                        /* webpackChunkName: "SummarLoan001" */ "../views/summary_loan/sum001/SummaryLoan001.vue"
                    ),
            },
            {
                path: "/holiday-date",
                name: "holidayDate",
                component: () =>
                    import(
                        /* webpackChunkName: "HolidayDate001" */ "../views/holiday_date/holiday001/HolidayDate001.vue"
                    ),
            },
            {
                path: "/holiday-date/:type/:id?",
                name: "holidayDateCreate",
                component: () =>
                    import(
                        /* webpackChunkName: "HolidayDate002" */ "../views/holiday_date/holiday002/HolidayDate002.vue"
                    ),
            },
            {
                path: "/coemployee",
                name: "coemployee",
                component: () =>
                    import(
                        /* webpackChunkName: "Customer001" */ "../views/co_employee/co001/COEmployee001.vue"
                    ),
            },
            {
                path: "/coemployee/:type/:id?",
                name: "coemployee-register-edit",
                component: () =>
                    import(
                        /* webpackChunkName: "Customer002" */ "../views/co_employee/co002/COEmployee002.vue"
                    ),
                props: true,
            },
            {
                path: "/cash-in",
                name: "cash-in",
                component: () =>
                    import(
                        /* webpackChunkName: "cash-in" */ "../views/cash_in/CashIn.vue"
                    ),
            },
            {
                path: "/expense",
                name: "expense",
                component: () =>
                    import(
                        /* webpackChunkName: "expense" */ "../views/expense/Expense.vue"
                    ),
            },
            {
                path: "/req-cash",
                name: "req-cash",
                component: () =>
                    import(
                        /* webpackChunkName: "req-cash" */ "../views/reports/rep_cash/Rep_cash.vue"
                    ),
            },
            {
                path: "/req-expense",
                name: "req-expense",
                component: () =>
                    import(
                        /* webpackChunkName: "req-expense" */ "../views/reports/rep_exp/Rep_exp.vue"
                    ),
            },
        ],
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
    scrollBehavior() {
        // always scroll to top
        return { top: 0 };
    },
});

router.beforeEach((to, from, next) => {
    console.log(from)
    const token = localStorage.getItem("token");
    if (to.meta.requireAuth && !token) {
        next({ name: "Login" });
    } else if (token && to.name === "Login") {
        next({ name: "home" });
    } else {
        next();
    }
});

export default router;
