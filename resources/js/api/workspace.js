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
}

export { WorkspaceResource as default };
