import axios from 'axios';

const API_URL = '/api/announcements';

const announcements = {
  namespace: true,
  state: {
    amdals: [],
    uklupls: [],
    alls: [],
    amdalPopup: [],
    ukluplPopup: [],
    countAmdal: [],
    countUklupl: [],
    countAll: [],
    loadingStatus: false,
  },
  mutations: {
    SET_ALL(state, payload) {
      state.alls = payload;
    },
    SET_AMDAL(state, payload) {
      state.amdals = payload;
    },
    SET_UKLUPL(state, payload) {
      state.uklupls = payload;
    },
    COUNT_AMDAL(state, payload) {
      state.countAmdal = payload;
    },
    COUNT_UKLUPL(state, payload) {
      state.countUklupl = payload;
    },
    COUNT_ALL(state, payload) {
      state.countAll = payload;
    },
    LOADING_STATUS(state, payload) {
      state.loadingStatus = payload;
    },
  },
  actions: {
    getAll({ commit }) {
      commit('LOADING_STATUS', true);
      axios.get(`${API_URL}?keyword=ALL`)
        .then(response => {
          commit('SET_ALL', response.data);
          commit('LOADING_STATUS', false);
        });
    },
    getAmdal({ commit }) {
      commit('LOADING_STATUS', true);
      axios.get(`${API_URL}?keyword=AMDAL&limit=10`)
        .then(response => {
          commit('SET_AMDAL', response.data);
          commit('LOADING_STATUS', false);
        });
    },
    getUklupl({ commit }) {
      commit('LOADING_STATUS', true);
      axios.get(`${API_URL}?keyword=UKL-UPL&limit=10`)
        .then(response => {
          commit('SET_UKLUPL', response.data);
          commit('LOADING_STATUS', false);
        });
    },
    countAmdal({ commit }) {
      axios.get(`${API_URL}?keyword=AMDAL`)
        .then(response => {
          commit('COUNT_AMDAL', response.data.data.length);
        });
    },
    countUklupl({ commit }) {
      axios.get(`${API_URL}?keyword=UKL-UPL`)
        .then(response => {
          commit('COUNT_UKLUPL', response.data.data.length);
        });
    },
    countAll({ commit }) {
      axios.get(`${API_URL}`)
        .then(response => {
          commit('COUNT_ALL', response.data.data.length);
        });
    },
  },
};

export default announcements;
