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
      name: 'editWorkspace',
      hidden: true,
      meta: { title: 'Edit Workspace', icon: 'edit', noCache: true },
    },
    {
      path: 'list',
      component: () => import('@/views/workspace/List'),
      name: 'listWorkspace',
      meta: { title: 'Workspace List', icon: 'list' },
    },
  ],
};

export default workspaceRoutes;
