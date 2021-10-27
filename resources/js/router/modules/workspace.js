import Layout from '@/layout';

const workspaceRoutes = {
  path: '/workspace',
  component: Layout,
  redirect: '/workspace/list',
  alwaysShow: true,
  meta: { title: 'Workspace', icon: 'layout' },
  children: [
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/workspace/Edit'),
      name: 'Edit Workspace',
      hidden: true,
      meta: { title: 'Edit Workspace', icon: 'edit', noCache: true },
    },
    {
      path: 'list',
      component: () => import('@/views/workspace/List'),
      name: 'Workspace List',
      meta: { title: 'WorkspaceList', icon: 'list' },
    },
  ],
};

export default workspaceRoutes;
