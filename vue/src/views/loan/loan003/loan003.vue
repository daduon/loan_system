<template src="./Loan003.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from "../../../services/request-service";
import Loading from "../../../components/Loading.vue";
import Client from "../../../services/client";
import Swal from 'sweetalert2';
import { exportExcel, exportPDF } from "../../../services/export";

const requestService = new RequestService();

export default defineComponent({
    name: "loan-schedules",
    components: {
        Loading,
    },
    data() {
        const borrowerScheduleList = [] as any;
        const dataTable = [] as any[];
        return {
            borrowerScheduleList,
            isLoading: false,
            dataTable,
            countOfPage: 10,
            currPage: 1,
            searchBy: ['customername'],
            search: '',
            companyName: "",
            companyImage: "",
        };
    },
    computed: {
        dateFormat() {
            return (date: String) => (date.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3'));
        },
        headers() {
            return [
                { label: "Customer Name", prop: 'customername' },
                { label: "Due Date", prop: 'paymentapplydate' },
                { label: "Currency", prop: 'currencyCode' },
                { label: "Payment", prop: 'payment' },
                { label: "Balance", prop: 'transactionprincipal' },
                { label: "Remark", prop: 'remark' },
            ];
        },

        pageStart() {
            return (this.currPage - 1) * this.countOfPage;
        },

        totalPage() {
            return Math.ceil(this.borrowerScheduleList.length / this.countOfPage);
        },

        dataGrid() {

            if (this.search.length == 0) {
                this.dataTable = this.borrowerScheduleList
            }
            this.dataTable = this.borrowerScheduleList.filter((data: any) => !this.search || this.searchBy.some((item: any) => data[item].toString().toLowerCase().includes(this.search.toLowerCase())))
            // this.dataTable.forEach((item) => {
            //     item.payment =
            // })
            return this.dataTable;
        }
    },

    mounted() {
        this.borrowerSchedule();
    },

    methods: {
        async borrowerSchedule() {
            // console.log("GG", localStorage.getItem("_loandId"));

            this.isLoading = true;
            const url = `retriveborrower/${localStorage.getItem(
                "_id"
            )}/${localStorage.getItem("_loandId")}`;
            const res = await requestService.list(url);
            if (res.status === 200) {
                this.borrowerScheduleList = res.data;
                this.isLoading = false;
            } else {
                this.isLoading = false;
            }
        },
        back() {
            this.$router.push("/loan");
        },

        exportExcel() {
            const exportData = {
                columns: this.headers.map((data: any) => {
                    return {
                        header: data.label,
                        dataKey: data.prop,
                    };
                }),
                body: this.dataGrid,
                fileName: 'excelFile',
            };
            exportExcel(exportData);
        },
        exportPdf() {
            const exportData: any = {
                headerList: this.headers.map((data: any) => {
                    return {
                        header: data.label,
                        dataKey: data.prop,
                    };
                }),
                list: this.dataGrid,
                fileName: 'pdfFile',
            };
            exportPDF(exportData);
        },

        async update(id: any, bid: any, seq: any) {
            const Toast = Swal.mixin({
                toast: true,
                position: "center",
                customClass: {
                    popup: "colored-toast",
                },
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
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
                    this.isLoading = true;
                    const res = await Client.get(
                        `payloanbyid/${id}/${bid}/${seq}`
                    );
                    if (res.status === 200) {
                        if (res.data.message != "N") {
                            this.borrowerSchedule();
                            Toast.fire({
                                icon: "success",
                                title: "Success",
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                title: "Last Month Not yet paid",
                            });
                            this.isLoading = false;

                            document
                                .querySelectorAll(".paid_btn")
                                .forEach((checkbox: any) => {
                                    checkbox.checked = false;
                                });
                        }
                    }
                } else {
                    document
                        .querySelectorAll(".paid_btn")
                        .forEach((checkbox: any) => {
                            checkbox.checked = false;
                        });
                }
            });
        },
        countMonthBetweenTwoDates(startDateT: string, endDateT: string) {
            let endDate = new Date(endDateT);
            let startDate = new Date(startDateT);

            // Calculate the difference in years between the two dates.
            const yearsDiff = endDate.getFullYear() - startDate.getFullYear();

            // Calculate the difference in months between the two dates.
            const monthsDiff = endDate.getMonth() - startDate.getMonth();

            // If the end date is in the same year as the start date, then the number of
            // months between the two dates is simply the difference in months.
            if (yearsDiff === 0) {
                return monthsDiff;
            }

            // If the end date is in a different year than the start date, then the number of
            // months between the two dates is the difference in years multiplied by 12, plus
            // the difference in months.
            else {
                return yearsDiff * 12 + monthsDiff;
            }
        },

        setPage(data: any) {
            if (data <= 0 || data > this.totalPage) {
                return;
            }
            this.currPage = data;
        },
        formatCurrency(amount: any): string {
            const val = (Math.floor(amount * 100000) / 100000).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
    },
});
</script>
