import axios from 'axios';

const announcements = {
  namespace: true,
  state: {
    amdals: [],
    uklupls: [],
  },
  mutations: {
    SET_AMDAL(state, payload) {
      state.amdals = payload;
    },
    SET_UKLUPL(state, payload) {
      state.uklupls = payload;
    },
  },
  actions: {
    getAmdal({ commit }) {
      axios.get(`/api/announcements?project=AMDAL`)
        .then(response => {
          commit('SET_AMDAL', response.data.data);
        });
    },
    getUklupl({ commit }) {
      axios.get(`/api/announcements?project=UKL-UPL`)
        .then(response => {
          commit('SET_UKLUPL', response.data.data);
        });
    },
  },
};

export default announcements;
