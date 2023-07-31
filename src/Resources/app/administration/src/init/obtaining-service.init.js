const { Application } = Shopware;
import ObtainingConfigService from './../api/config';

Application.addServiceProvider('obtainingConfigService', container => {
    const initContainer = Application.getContainer('init');
    return new ObtainingConfigService(initContainer.httpClient, container.loginService);
});