import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/feedbacks',
    method: 'get',
    params: query,
  });
}
