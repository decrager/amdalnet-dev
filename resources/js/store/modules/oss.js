import Resource from '@/api/resource';
const ossNibResource = new Resource('ossNibs');

const oss = {
  namespace: true,
  state: {
    ossByNib: [],
  },
  mutations: {
    SET_OSS_BY_NIB(state, payload) {
      state.ossByNib = payload;
    },
  },
  actions: {
    async getOssByKbli({ commit }, payload) {
      const data = await ossNibResource.list({ nib: payload });
      commit('SET_OSS_BY_NIB', data);
    },
  },
};

export default oss;
