<template src="./SummaryLoan001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import Client from "../../../services/client";
import { ToastService } from "../../../services/toast";

const toastService = new ToastService();

export default defineComponent({
    name: "SummaryLoan001",
    data() {
        const customer = [] as any;
        const summary = [] as any;
        const borrowId = [] as any;

        return {
            customer_selected: "",
            borrowId_selected: "",
            type_selected: "all",
            customer,
            summary,
            borrowId,
            loading: false,
        };
    },
    methods: {
        GetSummary() {
            if (this.customer_selected === "") {
                toastService.toastMessage("info", "Please Select Customer!");
                return;
            }

            if (this.borrowId_selected === "") {
                toastService.toastMessage("info", "Please Select Borrow Id!");
                return;
            }

            if (this.borrowId == null) {
                toastService.toastMessage("info", "Please Select Borrow Id!");
                return;
            }

            this.loading = true;

            if (this.type_selected === "all") {
                Client.get(
                    `/getsummarybycusbyborrow/${this.customer_selected}/${this.borrowId_selected}`
                )
                    .then((res) => {
                        if (res.data.detail.length === 0) {
                            this.loading = false;
                            this.summary = [];
                            toastService.toastMessage("info", "No Data!");
                            return;
                        }
                        this.summary = res.data;
                        this.loading = false;
                    })
                    .catch((error) => {
                        toastService.toastMessage("error", "Error", error);
                        this.loading = false;
                        this.summary = [];
                    });
            } else if (this.type_selected === "paid") {
                Client.get(
                    `/getpaidsummarybycusbyborrow/${this.customer_selected}/${this.borrowId_selected}`
                )
                    .then((res) => {
                        if (res.data.detail.length === 0) {
                            this.loading = false;
                            this.summary = [];
                            toastService.toastMessage("info", "No Data!");
                            return;
                        }
                        this.summary = res.data;
                        this.loading = false;
                    })
                    .catch((error) => {
                        toastService.toastMessage("error", "Error", error);
                        this.loading = false;
                        this.summary = [];
                    });
            } else if (this.type_selected === "unpaid") {
                Client.get(
                    `/getunpaidsummarybycusbyborrow/${this.customer_selected}/${this.borrowId_selected}`
                )
                    .then((res) => {
                        if (res.data.detail.length === 0) {
                            this.loading = false;
                            this.summary = [];
                            toastService.toastMessage("info", "No Data!");
                            return;
                        }
                        this.summary = res.data;
                        this.loading = false;
                    })
                    .catch((error) => {
                        toastService.toastMessage("error", "Error", error);
                        this.loading = false;
                        this.summary = [];
                    });
            }
        },
        GetAllCustomer() {
            Client.get("/allcustomers")
                .then((res: any) => {
                    this.customer = res.data;
                })
                .catch((error) => {
                    toastService.toastMessage("error", "Error", error);
                });
        },
        FomartDate(dateStr: string) {
            // const dateObj = new Date(dateStr);
            // const newDateStr = dateObj.toLocaleDateString("en-US", {
            //     year: "numeric",
            //     month: "long",
            //     day: "numeric",
            // });
            return dateStr;
        },
        GetBorrowId() {
            this.borrowId_selected = "";
            this.borrowId = [];
            if (this.customer_selected === "") {
                return;
            }
            Client.get("/getborrowidbycus/" + this.customer_selected)
                .then((res: any) => {
                    this.borrowId = res.data;

                    if (res.data[0]) {
                        this.borrowId_selected = this.borrowId[0].id;
                    }
                })
                .catch((error) => {
                    toastService.toastMessage("error", error);
                });
        },
        formatCurrency(amount:any): string {
            const val = (Math.floor(amount * 100000) / 100000).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
    },
    created() {
        this.GetAllCustomer();
    },
});
</script>
