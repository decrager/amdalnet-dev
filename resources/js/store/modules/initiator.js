import Resource from '@/api/resource';
const initiatorResource = new Resource('initiators');

const workflow = {
  namespace: true,
  state: {
    initiator: {},
    isPemerintah: false,
  },
  mutations: {
    SET_INITIATOR(state, payload) {
      state.initiator = payload;
    },
    SET_IS_PEMERINTAH(state, payload) {
      state.isPemerintah = payload;
    },
  },
  actions: {
    async getInitiator({ commit }, payload) {
      const { data } = await initiatorResource.list({ email: payload });
      commit('SET_INITIATOR', data);

      if (data[0].user_type === 'Pemerintah') {
        commit('SET_IS_PEMERINTAH', true);
      }
    },
  },
};

export default workflow;
