import './module/extension/sw-cms/component/sw-cms-sidebar';
import './module/sw-cms/blocks/currency-rate';
import './module/sw-cms/elements/currency-rate';
import "./module/sw-extension-override/page/sw-extension-config";
import './init/obtaining-service.init';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Locale.extend('de-DE', deDE);
Shopware.Locale.extend('en-GB', enGB);