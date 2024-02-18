import Client from "./client";
import { ToastService } from "./toast";

const toastService = new ToastService();
export class RequestService {

    async login(endPoint: string, body: any): Promise<any> {
        try {
            const res = await Client.post(endPoint, body);

            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }

    async logout(endPoint: string): Promise<any> {
        try {
            const res = await Client.post(endPoint);
            toastService.toastMessage('success','Logout successfully!')
            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }

    async create(endPoint: string, body: any): Promise<any> {
        try {
            const res = await Client.post(endPoint, body);

            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }

    async update(endPoint: string, body: any): Promise<any> {
        try {
            const res = await Client.put(endPoint, body);

            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }
    async updatebyId(endPoint: string): Promise<any> {
        try {
            const res = await Client.put(endPoint);
            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }
    async list(endPoint: string): Promise<any> {
        try {
            const res = await Client.get(endPoint);

            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }

    async detail(endPoint: string): Promise<any> {
        try {
            const res = await Client.get(endPoint);

            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }

    async delete(endPoint: string): Promise<any> {
        try {
            const res = await Client.delete(endPoint);

            return res;
        } catch (error) {
            toastService.toastMessage('error','Error',error)
            return Promise.reject(error);
        }
    }
}
