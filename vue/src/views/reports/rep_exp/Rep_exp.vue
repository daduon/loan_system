<template src="./Rep_exp.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import Loading from "../../../components/Loading.vue";
import { exportExcel, exportPDF } from "../../../services/export";
import { RequestService } from '../../../services/request-service';
// import moment from 'moment';

const requestService = new RequestService();

export default defineComponent({
    name: "req-exp",
    components: {
        Loading,
    },
    data() {
        const expenseList = [] as any;
        const dataTable = [] as any[];
        return {
            expenseList,
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
                // { label: "Title", prop: 'expense_no' },
                { label: "Expense By", prop: 'expense_by' },
                { label: "Amount (USD)", prop: 'expense_amount_usd' },
                { label: "Amount (KHR)", prop: 'expense_amount_kh' },
                { label: "Expense Date", prop: 'expense_date' },
                { label: "Description", prop: 'expense_desc' },
                { label: "Status", prop: 'expense_status' },
            ];
        },
        totalPage() {
            return Math.ceil(this.expenseList.length / this.countOfPage);
        },
        pageStart() {
            return (this.currPage - 1) * this.countOfPage;
        },
    },

    mounted() {
        this.inquiryExpense();
    },

    methods: {
        async inquiryExpense() {
            this.isLoading = true
                  const res = await requestService.list('expenses');
                  if (res.status === 200) {
                      this.expenseList = this.mapData(res.data);
                      this.isLoading = false
                  }
        },

        mapData(list: any) {
                return list.data.map((item: any) => {
                    return {
                            id: item.id,
                            expense_no: item.expense_no,
                            expense_by: item.expense_by,
                            expense_date: this.dateFormat(item.expense_date),
                            expense_amount_usd: this.formatCurrency(item.expense_amount_usd),
                            expense_amount_kh: this.formatCurrencyKHR(item.expense_amount_kh),
                            expense_desc: item.expense_desc,
                            expense_status: item.expense_status == "01" ? "Active" : "Inactive",
                    }
                })
        },

        back() {
            this.$router.push("/");
        },

        exportExcel() {
            const exportData = {
                columns: this.headers.map((data: any) => {
                        return {
                            header: data.label,
                            dataKey: data.prop,
                        };
                }),
                body: this.expenseList,
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
                list: this.expenseList,
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
