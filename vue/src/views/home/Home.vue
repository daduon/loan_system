<template src="./Home.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../services/request-service';
import Loading from "../../components/Loading.vue";
import DataformatService from "../../services/data-format.service";
import { exportExcel, exportPDF } from "../../services/export";

const requestService = new RequestService();
const dataformatService = new DataformatService();
export default defineComponent({
    name: "Home",
    components:{
        Loading
    },
    data() {
        const customersMonthly = [] as any[];
        const dataTable = [] as any[];
        return {
            totalCustomerBorrow:0,
            paid:0,
            complated:0,
            isLoading:false,
            dataTable,
            customersMonthly,
            currPage: 1,
            countOfPage: 10,
            selectedMonth: '',
        };
    },

    computed: {
        headers() {
            return [
                { label: "No.", prop: 'No'},
                { label: "Customer Name", prop: 'customerName'},
                { label: "Due Date", prop: 'dueDate'},
                { label: "Principal", prop: 'principal'},
                { label: "Payment", prop: 'payment'},
            ];
        },
        pageStart() {
            return (this.currPage - 1) * this.countOfPage;
        },

        totalPage() {
            return Math.ceil(this.customersMonthly.length / this.countOfPage);
        },
    },

    watch: {
        selectedMonth(newMonth) {
            this.inquiryCustomersMonthly(newMonth);
        },
    },

    created() {
        this.countCustomerBorrow();
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        this.selectedMonth = `${year}-${month}`;
    },

    methods: {
        async countCustomerBorrow() {
            this.isLoading = true;
            const res = await requestService.list(`/retriveborrower`);
            // console.log(res)
            if (res.status === 200) {
                this.totalCustomerBorrow = res.data.length;
               this.complated = res.data.filter((item:any) => item.ispaid == 1).length;
               this.paid = res.data.filter((item:any) => item.ispaid == 0).length;
            }
            this.isLoading = false;
        },

        async inquiryCustomersMonthly(month: any) {
            this.isLoading = true;
            const res = await requestService.list(`customers_monthly/${month}`);
            if(res.status === 200) {
                this.customersMonthly = this.mapData(res.data);
                this.dataTable = this.customersMonthly;
                this.currPage = 1;
                this.isLoading = false;
            }
        },

        mapData(list: any) {
            return list.data.map((item: any) => {
                return {
                    No: item.No,
                    customerName: item.customer_name,
                    dueDate: dataformatService.formatDate(item.due_date),
                    principal: item.principal,
                    payment: item.payment,
                }
            })
        },

        exportExcel() {
            const exportData = {
                columns: this.headers.map((data: any) => {
                        return {
                            header: data.label,
                            dataKey: data.prop,
                        };
                }),
                body: this.dataTable,
                fileName: 'Monthly Customer Payment ' + this.selectedMonth,
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
                title: 'Monthly Customer Payment បង' + this.selectedMonth,
                list: this.dataTable,
                fileName: 'Monthly Customer Payment ' + this.selectedMonth,
            };
                exportPDF(exportData);
        },

        setPage(data: any) {
                  if (data <= 0 || data > this.totalPage) {
                        return;
                  }
                  this.currPage = data;
        },

        printElem()
        {
            const mywindow = window.open('', 'PRINT');
            const element = document.getElementById('printArea');
            const setMonthWithTitle: any =
                {
                    "01" : "មករា",
                    "02" : "កុម្ភៈ",
                    "03" : "មិនា",
                    "04" : "មេសា",
                    "05" : "ឧសភា",
                    "06" : "មិថុនា",
                    "07" : "កក្កដា",
                    "08" : "សីហា",
                    "09" : "កញ្ញា",
                    "10" : "តុលា",
                    "11" : "វិច្ឆិកា",
                    "12" : "ធ្នូ",
                };

            const showMonthTitle = setMonthWithTitle[this.selectedMonth.split('-')[1]];

            if (element) {
                const table = document.createElement('table');
                table.innerHTML = element.innerHTML;
                table.setAttribute('style', element.getAttribute('style') || '');

                mywindow?.document.write('<style>');
                mywindow?.document.write(`
                    body {
                        font-family: "Battambang", Arial, sans-serif;
                    }
                    .print-header {
                        font-size: 20px;
                        font-weight: bold;
                        margin-bottom: 20px;
                        margin-top: 20px;
                        text-align: center;
                    }
                    @page {
                        size: auto;
                        margin: 5mm;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th, td {
                        padding: 8px;
                        border: 1px solid #ddd;
                    }

                    td {
                        text-align: left;
                    }

                    th {
                        background-color: #f2f2f2;
                        text-align: center;
                    }

                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                `);
                mywindow?.document.write('</style></head><body>');
                mywindow?.document.write('<div class="print-header">បញ្ជីឈ្មោះអតិថិជនប្រចាំខែ');
                mywindow?.document.write(showMonthTitle);
                mywindow?.document.write('</div>');
                mywindow?.document.write(table.outerHTML);
                mywindow?.document.write('</body></html>');
            }

            mywindow?.document.close();
            mywindow?.focus();
            mywindow?.print();
            mywindow?.close();

            return true;
        }
    },
});
</script>
