import api from '../../services/api';

export default {
    namespaced: true,
    state() {
        return {
            inspections: [],
            currentInspection: null,
            loading: false,
            error: null,
        };
    },
    getters: {
        openInspections: (state) => state.inspections.filter(i => i.status === 'OPEN'),
        reviewInspections: (state) => state.inspections.filter(i => i.status === 'FOR_REVIEW'),
        completedInspections: (state) => state.inspections.filter(i => i.status === 'COMPLETED'),
    },
    mutations: {
        SET_INSPECTIONS(state, inspections) {
            state.inspections = inspections;
        },
        ADD_INSPECTION(state, inspection) {
            state.inspections.push(inspection);
        },
        UPDATE_INSPECTION_IN_LIST(state, updatedInspection) {
            const index = state.inspections.findIndex(i => i.id === updatedInspection.id);
            if (index !== -1) {
                state.inspections[index] = updatedInspection;
            }
        },
        SET_CURRENT_INSPECTION(state, inspection) {
            state.currentInspection = inspection;
        },
        SET_LOADING(state, isLoading) {
            state.loading = isLoading;
        },
        SET_ERROR(state, error) {
            state.error = error;
        },
    },
    actions: {
        async fetchInspections({ commit, state }, status = null) {
            commit('SET_LOADING', true);
            try {
                const params = status ? { status } : {};
                const response = await api.get('/inspections', { params });

                if (status) {
                    const newItems = response.data.data;
                    const combined = [
                        ...state.inspections.filter(i => i.status !== status),
                        ...newItems
                    ];
                    commit('SET_INSPECTIONS', combined);
                } else {
                    commit('SET_INSPECTIONS', response.data.data);
                }
            } catch (error) {
                commit('SET_ERROR', error.message);
            } finally {
                commit('SET_LOADING', false);
            }
        },

        async fetchAllInspections({ commit }) {
            commit('SET_LOADING', true);
            try {
                const response = await api.get('/inspections?per_page=100');
                commit('SET_INSPECTIONS', response.data.data);
            } catch (error) {
                commit('SET_ERROR', error.message);
            } finally {
                commit('SET_LOADING', false);
            }
        },

        async createInspection({ commit }, payload) {
            commit('SET_LOADING', true);
            try {
                const response = await api.post('/inspections', payload);
                commit('ADD_INSPECTION', response.data);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        },

        async fetchInspection({ commit }, id) {
            commit('SET_LOADING', true);
            try {
                const response = await api.get(`/inspections/${id}`);
                commit('SET_CURRENT_INSPECTION', response.data);
                return response.data;
            } catch (error) {
                commit('SET_ERROR', error.message);
            } finally {
                commit('SET_LOADING', false);
            }
        },

        async updateInspection({ commit, state }, { id, payload }) {
            commit('SET_LOADING', true);
            try {
                const response = await api.put(`/inspections/${id}`, payload);
                commit('UPDATE_INSPECTION_IN_LIST', response.data);

                if (state.currentInspection && state.currentInspection.id === id) {
                    commit('SET_CURRENT_INSPECTION', response.data);
                }
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        }
    }
};
