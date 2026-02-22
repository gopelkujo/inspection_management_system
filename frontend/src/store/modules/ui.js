export default {
    namespaced: true,
    state() {
        return {
            dialog: {
                isOpen: false,
                title: '',
                message: '',
                confirmText: 'Confirm',
                cancelText: 'Cancel',
                type: 'info',
                resolve: null,
                reject: null,
                showCancel: false,
            },
            loading: {
                active: false,
                message: 'Processing...',
            },
        };
    },
    mutations: {
        SET_DIALOG(state, dialogConfig) {
            state.dialog = { ...state.dialog, ...dialogConfig };
        },
        RESET_DIALOG(state) {
            state.dialog = {
                isOpen: false,
                title: '',
                message: '',
                confirmText: 'Confirm',
                cancelText: 'Cancel',
                type: 'info',
                resolve: null,
                reject: null,
                showCancel: false,
            };
        },
        SET_LOADING(state, loadingConfig) {
            state.loading = { ...state.loading, ...loadingConfig };
        },
    },
    actions: {
        confirm({ commit }, { title = 'Confirm Action', message = 'Are you sure?', confirmText = 'Confirm', cancelText = 'Cancel', type = 'warning' }) {
            return new Promise((resolve, reject) => {
                commit('SET_DIALOG', {
                    isOpen: true,
                    title,
                    message,
                    confirmText,
                    cancelText,
                    type,
                    resolve,
                    reject,
                    showCancel: true,
                });
            });
        },
        alert({ commit }, { title = 'Alert', message = '', confirmText = 'OK', type = 'info' }) {
            return new Promise((resolve) => {
                commit('SET_DIALOG', {
                    isOpen: true,
                    title,
                    message,
                    confirmText,
                    cancelText: '',
                    type,
                    resolve,
                    reject: null,
                    showCancel: false,
                });
            });
        },
        handleConfirm({ state, commit }) {
            if (state.dialog.resolve) state.dialog.resolve(true);
            commit('RESET_DIALOG');
        },
        handleCancel({ state, commit }) {
            if (state.dialog.resolve) state.dialog.resolve(false);
            commit('RESET_DIALOG');
        },
        startLoading({ commit }, message = 'Processing...') {
            commit('SET_LOADING', { active: true, message });
        },
        stopLoading({ commit }) {
            commit('SET_LOADING', { active: false, message: '' });
        },
    },
};
