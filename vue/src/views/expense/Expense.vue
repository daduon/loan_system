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
            }
        }
    },

    methods: {
        async handleSave() {
            if (
                this.expense.expense_amount_usd == 0 || !this.expense.expense_amount_usd ||
                this.expense.expense_amount_kh == 0 || !this.expense.expense_amount_kh
            ) {
                this.isInvalide = true;
                return;
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