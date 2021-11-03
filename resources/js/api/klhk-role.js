import request from '@/utils/request';

export function fetchInitiatorByEmail(email) {
  return request({
    url: '/initiators',
    method: 'get',
    params: {
      email: email,
    },
  });
}
