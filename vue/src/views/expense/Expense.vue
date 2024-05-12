<template src="./Expense.html"></template>

<script lang="ts">
import { defineComponent } from 'vue';
import { RequestService } from '../../services/request-service';
import { ToastService } from '../../services/toast';

const toastService = new ToastService();
const requestService = new RequestService();

export default defineComponent({
    name: 'Expense',
    data() {
        return {
            isInvalide: false,
            isLoading: false,
            expense: {
                expense_no: '',
                expense_desc: '',
                expense_date: '',
                expense_by: 'admin',
                expense_status: '01',
                expense_amount_usd: 0,
                expense_amount_kh: 0
            },
            totalCash:{
                cash_total_usd: 0,
                cash_total_kh: 0
            },
            coemployees:[] as any[],
        }
    },

    mounted() {
        this.getTotalCash();
        this.GetAllCOEmployee();
    },

    methods: {

        GetAllCOEmployee() {
            requestService.list("/coemployees").then((res: any) => {
                this.coemployees = res.data.data;
            });
        },

        async getTotalCash(){
            this.isLoading = true;
            const res = await requestService.list(`/cash_transaction`);
            if (res.status === 200) {
                this.totalCash.cash_total_usd = res.data.data[0].cash_total_usd;
                this.totalCash.cash_total_kh = res.data.data[0].cash_total_kh;
            }
            this.isLoading = false;
        },

        async handleSave() {

            if(this.expense.expense_date == '' || this.expense.expense_by == ''){
                this.isInvalide = true;
                return;
            }
            if (this.expense.expense_amount_usd == 0  && this.expense.expense_amount_kh == 0) {
                toastService.toastMessage('error', 'The amount is required');
                return
            }

            if(this.expense.expense_amount_usd != 0  || this.expense.expense_amount_kh != 0){
                if(this.expense.expense_amount_usd < this.totalCash.cash_total_usd){
                    toastService.toastMessage('error', 'Your cash not enough');
                    return
                }

                if(this.expense.expense_amount_kh < this.totalCash.cash_total_kh){
                    toastService.toastMessage('error', 'Your cash not enough');
                    return
                }
            }

            const body = {
                expense_no: this.expense.expense_no,
                expense_desc: this.expense.expense_desc,
                expense_date: this.expense.expense_date.toString().replace(/-/g, ""),
                expense_by: this.expense.expense_by,
                expense_status: this.expense.expense_status,
                expense_amount_usd: this.expense.expense_amount_usd,
                expense_amount_kh: this.expense.expense_amount_kh,
            }

            this.isLoading = true;

            await requestService.create('/expenses', body).then(() => {
                this.isLoading = false;
                toastService.toastMessage('success', 'Created Success');
            }).catch((error: any) => {
                this.isLoading = false;
                toastService.toastMessage('error', 'Create failed', error.response.data.message);
            })

        },

        handleBack() {
            this.$router.push('/')
        }
    }
});
</script>