const workflow = {
  namespace: true,
  state: {
    step: 0,
  },
  mutations: {
    SET_STEP(state, payload) {
      state.step = payload;
    },
  },
  actions: {
    async getStep({ commit }, payload) {
      commit('SET_STEP', payload);
    },
  },
};

export default workflow;
