<template src="./SummaryLoan001.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import Client from "../../../../js/services/client";

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
            // console.log(this.customer_selected);
            // console.log(this.type_selected);

            if (this.customer_selected === "") {
                alert("Please Select Customer!");
                return;
            }

            if (this.borrowId_selected === "") {
                return;
            }

            if (this.borrowId == null) {
                return;
            }

            this.loading = true;

            if (this.type_selected === "all") {
                Client.get(
                    `/getsummarybycusbyborrow/${this.customer_selected}/${this.borrowId_selected}`
                )
                    .then((res) => {
                        this.summary = res.data;
                        // console.log(this.summary);
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                    });
            } else if (this.type_selected === "paid") {
                Client.get(
                    `/getpaidsummarybycusbyborrow/${this.customer_selected}/${this.borrowId_selected}`
                )
                    .then((res) => {
                        this.summary = res.data;
                        // console.log(this.summary);
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                    });
            } else if (this.type_selected === "unpaid") {
                Client.get(
                    `/getunpaidsummarybycusbyborrow/${this.customer_selected}/${this.borrowId_selected}`
                )
                    .then((res) => {
                        this.summary = res.data;
                        // console.log(this.summary);
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.loading = false;
                    });
            }
        },
        GetAllCustomer() {
            Client.get("/allcustomers").then((res: any) => {
                //console.log(res.data);
                this.customer = res.data;
            });
        },
        FomartDate(dateStr: string) {
            const dateObj = new Date(dateStr);
            const newDateStr = dateObj.toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
            return newDateStr;
        },
        GetBorrowId() {
            this.borrowId_selected = "";
            this.borrowId = [];
            if (this.customer_selected === "") {
                return;
            }
            Client.get("/getborrowidbycus/" + this.customer_selected).then(
                (res: any) => {
                    this.borrowId = res.data;
                }
            );
        },
    },
    created() {
        this.GetAllCustomer();
    },
});
</script>
