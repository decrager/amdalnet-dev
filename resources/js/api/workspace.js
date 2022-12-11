import request from '@/utils/request';
import Resource from '@/api/resource';

class WorkspaceResource extends Resource {
  constructor() {
    super('workspace');
  }

  sessionInit() {
    return request({
      url: '/' + this.uri + '/session/init',
      method: 'get',
    });
  }

  importTemplate(resource) {
    return request({
      url: '/' + this.uri + '/template/import',
      method: 'post',
      data: resource,
      params: { _method: 'POST' },
    });
  }

  getConfig(id, filename, createTime, version) {
    return request({
      url: '/' + this.uri + '/config/' + id,
      params: { filename, createTime, version },
      method: 'get',
    });
  }
}

export { WorkspaceResource as default };
