const { Component } = Shopware;


Component.override("sw-extension-config", {

    inject: ['obtainingConfigService'],

    methods: {

        async getStatus() {

            return this.obtainingConfigService.getInterval().then(response => {

                if (response.length === 0) {
                    return;
                }
                return response.interval;

            }).catch((exception) => {
                this.createNotificationError({
                    title: this.$tc('global.default.error'),
                    message: exception,
                });

            });

        },

        async onSave() {
            console.log(`onSave`);

            if (this.extension.name === 'ObtainingExchangeCurrencyRate') {
                let status = await this.getStatus();
                try {

                    await this.$refs.systemConfig.saveAll();
                    if (status) {
                        this.createNotificationSuccess({
                            message: this.$tc('sw-extension-store.component.sw-extension-config.messageSaveSuccess'),
                        });
                    } else {
                        this.createNotificationError({
                            message: this.$tc('sw-extension-override.error.failedSave'),
                        });
                    }

                } catch (err) {

                    this.createNotificationError({
                        message: err,
                    });
                }
            } else {
                this.$super("onSave");
            }

        }

    },
});
