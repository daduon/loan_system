<template src="./CashIn.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import { RequestService } from '../../services/request-service';
import { ToastService } from '../../services/toast';

const toastService = new ToastService();
const requestService = new RequestService();

export default defineComponent({
    name: 'CashIn',
    data() {
        const coemployees = [] as any;
        const cashInForm = {
            cashInAmountUSD: 0,
            cashInAmountKHR: 0,
            cashInUserId: '',
            description: '',
        }
        return {
            toastService,
            coemployees,
            coemployee_selected_id: "",
            cashInForm,
            loading: false,
            isInvalide: false,
        }
    },

    created() {
        this.GetAllCOEmployee();
    },

    methods: {
        handleSave() {
            if (this.cashInForm.description === '' ||
                this.coemployee_selected_id === '') {
                this.isInvalide = true;
                return;
            }
            this.CreateCashIn();
        },
        GetAllCOEmployee() {
            requestService.list("/coemployees").then((res: any) => {
                this.coemployees = res.data.data;
            });
        },
        GetCOEmployee() {
            let coIndex = parseInt(this.coemployee_selected_id);
            const co = this.coemployees[coIndex];
            this.cashInForm.cashInUserId = co.id;

        },

        async CreateCashIn() {
            this.loading = true;
            const reqBody = {
                cash_in_user_id: this.cashInForm.cashInUserId,
                cash_in_amt_usd: this.cashInForm.cashInAmountUSD,
                cash_in_amt_khr: this.cashInForm.cashInAmountKHR,
                cash_in_desc: this.cashInForm.description,
            }
            this.loading = true;
            await requestService.create("cashins", reqBody).then(res => {
                this.loading = false;
                console.log(res);
                toastService.toastMessage('success', 'Cash In Successfully!');
            }).catch(err => {
                this.loading = false;
                toastService.toastMessage('error', 'Cash In failed', err.response.data.message);
            })
        }
    }
})
</script>
<style scoped>
.btnSave {
    display: flex;
    align-items: center;
    justify-content: center;
}

.loader {
    width: 20px;
    height: 20px;
    border: 5px solid #FFF;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    margin-right: 10px;
}

@keyframes rotation {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
