<template src="./Loan001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from "../../../services/request-service";
import Loading from "../../../components/Loading.vue";
import Client from "../../../services/client";
import { ToastService } from "../../../services/toast";
import  DataformatService  from "../../../services/data-format.service";

const toastService = new ToastService();
const format = new DataformatService();

import Swal from "sweetalert2";

const requestService = new RequestService();

export default defineComponent({
    name: "Loan001",
    components: {
        Loading,
    },
    data() {
        const customerBorrowList = [] as any;
        const dataTable = [] as any[];
        return {
            customer_name: "",
            customerBorrowList,
            isLoading: false,
            dataTable,
            countOfPage: 10,
            currPage: 1,
            searchBy: ["customer_name"],
            search: "",
            getType:'',
            getFromDate:'',
            getEndDate:'',
            countDay:0 as any
        };
    },
    created() {
        this.inquiryCustomerType();
    },
    computed: {
        headers() {
            return [
                { label: "ID." },
                { label: "Customer Name" },
                { label: "CO Employee Name" },
                { label: "Start Date" },
                { label: "End Date" },
                { label: "Payment Type" },
                { label: "Currency Code" },
                { label: "Loan Amount" },
                { label: "Interest Rate" },
                { label: "Action" },
            ];
        },

        pageStart() {
            return (this.currPage - 1) * this.countOfPage;
        },
        totalPage() {
            return Math.ceil(this.customerBorrowList.length / this.countOfPage);
        },
        dataGrid() {
            if (this.search.length == 0) {
                this.dataTable = this.customerBorrowList;
            }
            this.dataTable = this.customerBorrowList.filter(
                (data: any) =>
                    !this.search ||
                    this.searchBy.some((item: any) =>
                        data[item]
                            .toString()
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                    )
            );
            return this.dataTable;
        },
    },
    methods: {
        async inquiryCustomerType() {
            this.isLoading = true;
            const res = await requestService.list(`/retriveborrower`);
            if (res.status === 200) {
                // console.log(res);

                this.customerBorrowList = this.mapData(res);
            }
            this.isLoading = false;
        },
        mapData(list: any) {
            return list.data.map((item: any) => {
                return {
                    id: item.id,
                    customer_id: item.customer_id,
                    customer_name: item.customer_name,
                    coEmployeeName: item.name,
                    startDate: item.startDate,
                    maturitydate: item.maturitydate,
                    paytype: item.payType,
                    currencycode: item.currencycode,
                    loanamount: format.formatCurrency(item.loanamount),
                    applyinterestrate: item.applyinterestrate,
                    ispaid: item.ispaid,
                };
            });
        },

        deleteLoan: async function (_id: string) {
            // console.log(_id);
            Swal.fire({
                title: "Are you sure?",
                // text: "You are paying for " + bid,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then(async (result: any) => {
                if (result.isConfirmed) {
                    const res = await Client.delete(`deleteborrower/${_id}`);
                    // console.log(res);

                    if (res.status === 200) {
                        toastService.toastMessage(
                            "success",
                            "Loan has been deleted!"
                        );
                        this.inquiryCustomerType();
                    }
                }
            });
        },

        getLoanSchedules: function (_id: string, _loandId: string) {
            localStorage.setItem("_id", _id);
            localStorage.setItem("_loandId", _loandId);
            this.$router.push(`/loan/schedules`);
        },

        getLoanSchedulesPrint: function (_id: string, _loandId: string, item:any) {
            this.isLoading = true;
            this.getType = item.paytype;
            this.getFromDate = item.startDate.toString().replace(/-/g, "");
            this.getEndDate = item.maturitydate.toString().replace(/-/g, "");
            this.getCountDate(this.getType, this.getFromDate, this.getEndDate);
            localStorage.setItem("_id", _id);
            localStorage.setItem("_loandId", _loandId);
            setTimeout(() => {
                let route = this.$router.resolve({ path: "/loan/print" });
                window.open(route.href, "_blank");
            }, 1000);
            //window.open("/loan/print", "_blank");
        },

        async getCountDate(type: string, from: string, to: string){
            this.countDay = 0;
            const res = await requestService.list(`/retrivecountdate/${type}/${from}/${to}`);
            if (res.status === 200) {
                this.countDay = res.data[0].count;
                localStorage.setItem("_countDay", this.countDay);
                this.isLoading = false;
            }
        },

        register() {
            this.$router.push("/loan/create");
        },

        setPage(data: any) {
            if (data <= 0 || data > this.totalPage) {
                return;
            }
            this.currPage = data;
        },
    },
});
</script>
