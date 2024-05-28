<template src="./Loan002.html"></template>

<script lang="ts">
import { defineComponent } from "vue";
import Client from "../../../services/client";
import { ToastService } from "../../../services/toast";
import { RequestService } from '../../../services/request-service';

const toastService = new ToastService();
const requestService = new RequestService();

export default defineComponent({
    name: "Loan002",
    data() {
        const customer = [] as any;
        const coemployees = [] as any;
        let mDate = new Date();
        mDate.setFullYear(mDate.getFullYear() + 1);
        return {
            customer,
            coemployees,
            customer_selected_id: "",
            coemployee_selected_id: "",
            borrow_type: "01",
            mDate: "",
            nDate: new Date().toISOString().split("T")[0],
            inputNumber: 1,
            remark: "",
            loan_amount: "0",
            // firstInterestPaymentDate: "20230901",
            taxRate: 0,
            newTaxRate: 0,
            newTaxRateFromDate: "",
            newTaxRateToDate: "",
            borrowingInterestCalculationTypeCode: "01",
            borrowingPrinciplePaymentTypeCode: "01",
            payType: "15",
            currency: "USD",
            loading: false,
            applyInterestRate: 0.0,
            customerId: "",
            coEmployeeId: "",
            isGeneral: 0,
            // numofmonth:0,
            isInvalide: false,
            countDay: 0,
            countDayOfMonth: 0,
            oldType: '00',
            totalCash:{
                cash_total_usd: 0,
                cash_total_kh: 0
            },
        };
    },
    watch: {
        'borrowingPrinciplePaymentTypeCode'(newValue) {
            this.generateDate(this.inputNumber, this.nDate, newValue)
        },
        'mDate'(newValue) {
            if (newValue) {
                this.mDate = newValue;
                const endDate = new Date(this.mDate);
                const startDate = new Date(this.nDate);

                if(this.borrowingPrinciplePaymentTypeCode == '02'){
                    if (typeof this.nDate == 'string' && typeof this.mDate == 'string') {
                        setTimeout(() => {
                            this.getCountDate();
                        }, 1000);
                    }
                } else{
                    setTimeout(() => {
                        this.countDayOfMonth = this.dayDiff(startDate, endDate)
                    }, 1000);
                }
            }
        },
        // 'nDate'(newValue) {
        //     if (newValue) {
        //         this.nDate = newValue;
        //         if (typeof this.nDate == 'string' && typeof this.mDate == 'string') {
        //             setTimeout(() => {
        //                 this.getCountDate();
        //             }, 1000);
        //         }
        //     }
        // }
    },
    created() {
        this.GetAllCustomer();
        this.GetAllCOEmployee();
        this.getTotalCash();
        if(this.inputNumber){
            this.generateDate(this.inputNumber, this.nDate, this.borrowingPrinciplePaymentTypeCode);
        }
    },
    methods: {
        async getTotalCash(){
            const res = await requestService.list(`/cash_transaction`);
            if (res.status == 200) {
                this.totalCash.cash_total_usd = parseFloat(res.data.data[0].cash_total_usd);
                this.totalCash.cash_total_kh = res.data.data[0].cash_total_kh;
            }
        },

        dayDiff(startDate: any, endDate: any) {
            if (!(startDate) || !(endDate)) {
                throw new Error('Both arguments must be Date objects');
            }
            const diffInMs = endDate - startDate;
            const days = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
            return days;
        },
        onChangeInputNumber(num:any){
            this.generateDate(num, this.nDate, this.borrowingPrinciplePaymentTypeCode);
            this.getCountDate();
        },
        onChangeStartDate(startDate: string){
            this.generateDate(this.inputNumber, startDate, this.borrowingPrinciplePaymentTypeCode);
            this.getCountDate();
        },
        generateDate(inputNumber: number, sDate: string, paymentType: string) {
            const date = new Date(sDate);
            if (paymentType == "02") {
                date.setDate(date.getDate() + Number(inputNumber));
                this.mDate = date.toISOString().split("T")[0];
            } else {
                date.setMonth(date.getMonth() + Number(inputNumber));
                this.mDate = date.toISOString().split("T")[0];
            }
        },
        onClickType(type: any) {
            this.oldType = type;
        },
        onChangeType(type: any) {
            this.borrowingPrinciplePaymentTypeCode = type;
            if (this.oldType !== this.borrowingPrinciplePaymentTypeCode) {
                this.getCountDate();
            }
        },
        async getCountDate() {
            const type = this.borrowingPrinciplePaymentTypeCode;
            const formDate = this.nDate.toString().replace(/-/g, "");
            const toDate = this.mDate.toString().replace(/-/g, "");
            const res = await requestService.list(`/retrivecountdate/${type}/${formDate}/${toDate}`);
            if (res.status === 200) {
                this.countDay = res.data[0].calcdays;
            }
        },
        GetAllCustomer() {
            Client.get("/allcustomers").then((res: any) => {
                this.customer = res.data;
            });
        },
        GetCustomer() {
            let cusIndex = parseInt(this.customer_selected_id);
            const cus = this.customer[cusIndex];
            this.customerId = cus.id;
            this.applyInterestRate = parseFloat(cus.customer_type_rate);
            this.isGeneral = cus.custypeid;
        },
        GetAllCOEmployee() {
            Client.get("/coemployees").then((res: any) => {
                this.coemployees = res.data.data;
            });
        },
        GetCOEmployee() {
            let coIndex = parseInt(this.coemployee_selected_id);
            const co = this.coemployees[coIndex];
            this.coEmployeeId = co.id;

        },
        getMonthDifference(startDate: Date, endDate: Date) {
            return (
                endDate.getMonth() -
                startDate.getMonth() +
                12 * (endDate.getFullYear() - startDate.getFullYear())
            );
        },
        CreateLoan() {
            this.loading = true;
            let obj = {
                customerId: this.customerId.toString(),
                coemployeeId: this.coEmployeeId,
                borrowingTypeCode: this.borrow_type,
                currencyCode: this.currency,
                loanAmount: parseFloat(this.loan_amount.toString()),
                maturityDate: this.mDate.toString().replace(/-/g, ""),
                remarkDesc: this.remark,
                applyInterestRate: this.applyInterestRate,
                paymentType: this.borrowingPrinciplePaymentTypeCode,
                borrowingPrinciplePaymentTypeCode:
                    this.borrowingPrinciplePaymentTypeCode,
                newDate: this.nDate.toString().replace(/-/g, ""),
                firstInterestPaymentDate: this.nDate
                    .toString()
                    .replace(/-/g, ""),
                taxRate: 0,
                newTaxRate: this.newTaxRate,
                newTaxRateFromDate: this.newTaxRateFromDate,
                newTaxRateToDate: this.newTaxRateToDate,
                borrowingInterestCalculationTypeCode: "01",
                payType: this.borrowingPrinciplePaymentTypeCode,
                numofmonth: this.getMonthDifference(
                    new Date(this.nDate.toString()),
                    new Date(this.mDate.toString())
                ),
            };

            // if(this.currency == 'USD'){
            //     if(parseFloat(this.loan_amount) > this.totalCash.cash_total_usd){
            //         toastService.toastMessage('error', 'Your cash not enough (USD)');
            //     }
            // }

            // if(this.currency == 'KHR'){
            //     if(parseFloat(this.loan_amount) > this.totalCash.cash_total_usd){
            //         toastService.toastMessage('error', 'Your cash not enough (KHR)');
            //     }
            // }

            if (this.customerId == "" || this.loan_amount == "0" || this.coEmployeeId == "") {
                this.isInvalide = true;
                toastService.toastMessage("error", "missing Some field");
                this.loading = false;
            } else {
                Client.post("/createborrower", obj)
                    .then(() => {
                        this.loading = false;
                        this.$router.push("/loan");
                        toastService.toastMessage("success", "Success");
                    })
                    .catch((err) => {
                        toastService.toastMessage("error", err.response.data.message);
                        this.loading = false;
                    });
                // this.loading = false;
            }
        },

        handleBack() {
            this.$router.push("/loan");
        },
        limitInputLength() {
            // Set the maximum length
            const maxLength = 8;

            // Check if the input length exceeds the maximum length
            if (this.loan_amount.length > maxLength) {
                // If it does, truncate the input value to the maximum length
                this.loan_amount = this.loan_amount.slice(0, maxLength);
            }
        },

        inputAccountNumber(event:any) {
            let keyCode = (event.keyCode ? event.keyCode : event.which);
            if (keyCode < 48 || keyCode > 57) {
                event.preventDefault();
            }
        },

        validateInput(event:any) {
            const value = event.target.value;
            const leadingZerosRegex = /^0+/;
            let num = leadingZerosRegex.test(value);
            if (num) {
                this.inputNumber = 1;
            }
        },
    }
});
</script>
