/**
 * @vitest-environment jsdom
 */
import { mount } from '@vue/test-utils'
import { describe, it, expect, vi, beforeEach } from 'vitest'
import { createStore } from 'vuex'
import InspectionList from '../InspectionList.vue'

describe('InspectionList.vue', () => {
    let wrapper
    let store
    let mockActions

    beforeEach(() => {
        mockActions = {
            fetchAllInspections: vi.fn(),
        }

        store = createStore({
            modules: {
                masterData: {
                    namespaced: true,
                    getters: {
                        inspectionTypes: () => [{ code: 'NEW_ARRIVAL', label: 'New Arrival' }],
                    }
                },
                inspection: {
                    namespaced: true,
                    state: {
                        inspections: [
                            { id: 1, type: 'NEW_ARRIVAL', status: 'OPEN', created_at: '2023-01-01' },
                            { id: 2, type: 'NEW_ARRIVAL', status: 'FOR_REVIEW', created_at: '2023-01-02' },
                        ],
                        loading: false
                    },
                    actions: mockActions
                }
            }
        })

        wrapper = mount(InspectionList, {
            global: {
                plugins: [store],
                stubs: {
                    'router-link': true,
                    transition: {
                        template: '<div><slot /></div>'
                    },
                },
            },
        })
    })

    it('renders tabs correctly', () => {
        const tabs = wrapper.findAll('button')
        expect(tabs).toHaveLength(3) // Open, For Review, Completed
        expect(tabs[0].text()).toBe('Open')
    })

    it('displays open inspections by default', async () => {
        expect(wrapper.vm.currentTab).toBe('OPEN')

        // Wait for computed properties to update
        await wrapper.vm.$nextTick()

        const rows = wrapper.findAll('tbody tr')
        expect(rows).toHaveLength(1)
        expect(rows[0].text()).toContain('OPEN') // Fallback to code if mock fails
        expect(rows[0].text()).toContain('#1')
    })

    it('filters inspections when tab is clicked', async () => {
        const tabs = wrapper.findAll('button')
        await tabs[1].trigger('click') // Click 'For Review'

        expect(wrapper.vm.currentTab).toBe('FOR_REVIEW')

        const rows = wrapper.findAll('tbody tr')
        expect(rows).toHaveLength(1)
        expect(rows[0].text()).toContain('FOR_REVIEW') // Fallback to code
        expect(rows[0].text()).toContain('#2')
    })
})
