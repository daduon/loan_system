<template src="./Rep_cash.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import Loading from "../../../components/Loading.vue";
import { exportExcel, exportPDF } from "../../../services/export";
import { RequestService } from '../../../services/request-service';

const requestService = new RequestService();
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
                { label: "Cash In By", prop: 'cash_in_user_name' },
                { label: "Amount (USD)", prop: 'cash_in_amt_usd' },
                { label: "Amount (KHR)", prop: 'cash_in_amt_khr' },
                // { label: "Income USD", prop: 'income_cash_in_usd' },
                // { label: "Income KHR", prop: 'income_cash_in_kh' },
                { label: "Date", prop: 'cash_in_date' },
                { label: "Description", prop: 'cash_in_desc' },
            ];
        },
        totalPage() {
            return Math.ceil(this.listCashIn.length / this.countOfPage);
        },
        pageStart() {
            return (this.currPage - 1) * this.countOfPage;
        },
    },

    mounted() {
        this.inquiryCashIn();
    },

    methods: {
        async inquiryCashIn() {
            this.isLoading = true
            const res = await requestService.list('cashins');
            if (res.status === 200) {
                this.listCashIn = this.mapData(res.data);
                this.isLoading = false
            }
        },

        mapData(list: any) {
            return list.data.map((item: any) => {
                return {
                    id: item.id,
                    cash_in_user_name: item.cash_in_user_name,
                    cash_in_amt_usd: this.formatCurrency(item.cash_in_amt_usd == 0 ? item.income_cash_in_usd : item.cash_in_amt_usd),
                    cash_in_amt_khr: this.formatCurrencyKHR(item.cash_in_amt_khr == 0 ? item.income_cash_in_kh : item.cash_in_amt_khr),
                    // income_cash_in_usd: this.formatCurrency(item.income_cash_in_usd),
                    // income_cash_in_kh: this.formatCurrencyKHR(item.income_cash_in_kh),
                    cash_in_date: this.dateFormat(item.cash_in_date),
                    cash_in_desc: item.cash_in_desc,
                }
            })
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

        exportPdf() {
            const exportData: any = {
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

        formatCurrency(amount: any): string {
            const val = (Math.floor(amount * 100000) / 100000).toFixed(2)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
        formatCurrencyKHR(amount: any): string {
            const val = (Math.floor(amount * 100000) / 100000)
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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
