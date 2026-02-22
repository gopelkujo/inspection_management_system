import api from '../../services/api';

export default {
    namespaced: true,
    state() {
        return {
            data: {},
            loading: false,
            error: null,
        };
    },
    getters: {
        inspectionTypes: (state) => state.data.inspection_type || [],
        inspectionStatuses: (state) => state.data.inspection_status || [],
        conditions: (state) => state.data.condition || [],
        owners: (state) => state.data.owner || [],
        allocations: (state) => state.data.allocation || [],
    },
    mutations: {
        SET_DATA(state, data) {
            state.data = data;
        },
        SET_LOADING(state, isLoading) {
            state.loading = isLoading;
        },
        SET_ERROR(state, error) {
            state.error = error;
        },
    },
    actions: {
        async fetchMasterData({ commit, state }) {
            if (Object.keys(state.data).length > 0) return; // Already loaded

            commit('SET_LOADING', true);
            try {
                const response = await api.get('/master-data');
                commit('SET_DATA', response.data);
            } catch (error) {
                commit('SET_ERROR', error.message);
                console.error('Failed to fetch master data:', error);
            } finally {
                commit('SET_LOADING', false);
            }
        },
    },
};
