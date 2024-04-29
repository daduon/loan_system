<template src="./Rep_cash.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import Loading from "../../../components/Loading.vue";
import { exportExcel, exportPDF } from "../../../services/export";

export default defineComponent({
    name: "req-cash",
    components: {
        Loading,
    },
    data() {
        const listCashIn = [] as any;
        const dataTable = [] as any[];
        return {
            listCashIn,
            isLoading: false,
            dataTable,
            countOfPage: 10,
            currPage: 1,
            searchBy: ['customername'],
            search:'',
            companyName: "",
            companyImage: "",
        };
    },
    computed: {
        dateFormat() {
            return (date:String) =>(date.replace(/(\d{4})(\d{2})(\d{2})/, '$1-$2-$3'));
        },
        headers() {
            return [
                { label: "Title", prop: 'title' },
                { label: "Expense By", prop: 'expenseBy' },
                { label: "Date", prop: 'date' },
                { label: "Amount", prop: 'amount' },
                { label: "Currency", prop: 'currency' },
                { label: "Description", prop: 'description' },
                { label: "Status", prop: 'status' },
            ];
        }
    },

    mounted() {
        this.inquiryCashIn();
    },

    methods: {
        async inquiryCashIn() {
            this.listCashIn = [];
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
                body: this.listCashIn,
                fileName: 'excelFile',
            };
            exportExcel(exportData);
        },

        exportPdf(){
            const exportData:any = {
                headerList: this.headers.map((data: any) => {
                        return {
                            header: data.label,
                            dataKey: data.prop,
                        };
                }),
                list: this.listCashIn,
                fileName: 'pdfFile',
            };
            exportPDF(exportData);
        },

        formatCurrency(amount:any): string {
            const val = (Math.floor(amount * 100000) / 100000).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
    },
});
</script>
