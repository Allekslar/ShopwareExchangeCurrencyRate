const ApiService = Shopware.Classes.ApiService;


export default class ObtainingConfigService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'obtaining-exchange-rates') {
        super(httpClient, loginService, apiEndpoint);
    }

    getInterval() {
        const headers = this.getBasicHeaders({});

        return this.httpClient
            .get(`obtaining-exchange-rates/save-config`, {
                headers
            })
            .then((response) => {
                return ApiService.handleResponse(response);
            });
    }

}