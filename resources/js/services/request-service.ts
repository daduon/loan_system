import Client from './client';

export class RequestService {
      async login(endPoint: string, body: any): Promise<any>{
            try {
                  const res = await Client.post(endPoint,body);

                  return res;
            } catch (error) {
                  alert(error)
            }
      }

      async logout(endPoint: string): Promise<any>{
            try {
                  const res = await Client.post(endPoint);
                  return res;
            } catch (error) {
                  alert(error)
            }
      }

      async create(endPoint: string, body: any): Promise<any>{
            try {
                  const res = await Client.post(endPoint,body);

                  return res;
            } catch (error) {
                  alert(error)
            }
      }

      async update(endPoint: string, body: any): Promise<any>{
            try {
                  const res = await Client.put(endPoint,body)

                  return res;
            } catch (error) {
                  alert(error)
            }
      }
      async updatebyId(endPoint: string): Promise<any>{
            try {
                  const res = await Client.put(endPoint)

                  return res;
            } catch (error) {
                  alert(error)
            }
      }
      async list(endPoint: string): Promise<any>{
            try {
                  const res = await Client.get(endPoint);

                  return res;
            } catch (error) {
                  alert(error)
            }
      }

      async detail(endPoint: string): Promise<any>{
            try {
                  const res = await Client.get(endPoint);

                  return res;
            } catch (error) {
                  alert(error)
            }
      }

      async delete(endPoint: string): Promise<any>{
            try {
                  const res = await Client.delete(endPoint);

                  return res;
            } catch (error) {
                  alert(error)
            }
      }
}
