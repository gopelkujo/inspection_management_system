import { createStore } from 'vuex';
import masterData from './modules/masterData';
import inspection from './modules/inspection';
import ui from './modules/ui';

export default createStore({
  modules: {
    masterData,
    inspection,
    ui,
  },
});
