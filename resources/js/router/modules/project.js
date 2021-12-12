import Layout from '@/layout';

const projectRoutes = {
  path: '/project',
  component: Layout,
  redirect: '/project/',
  meta: { title: 'project', icon: 'layout-fluid', permissions: ['view menu project'] },
  alwaysShow: true,
  children: [
    {
      path: '',
      component: () => import('@/views/project'),
      name: 'listProject',
      meta: { title: 'listProject', icon: 'apps-sort', permissions: ['view menu project'] },
    },
    // {
    //   path: 'post',
    //   component: () => import('@/views/project'),
    //   name: 'projectPost',
    //   meta: { title: 'listProjectPost', icon: 'documentation', permissions: ['view menu project post submission'] },
    // },
    // {
    //   path: 'process',
    //   component: () => import('@/views/project'),
    //   name: 'projectProcess',
    //   meta: { title: 'listProjectProcess', icon: 'documentation', permissions: ['view menu project on process'] },
    // },
    // {
    //   path: 'issued',
    //   component: () => import('@/views/project'),
    //   name: 'projectIssued',
    //   meta: { title: 'listProjectIssued', icon: 'documentation', permissions: ['view menu project issued'] },
    // },
    {
      path: 'create',
      component: () => import('@/views/project/Create'),
      name: 'createProject',
      hidden: true,
      meta: { title: 'addProject', icon: 'apps-sort', noCache: true },
    },
    {
      path: 'publish',
      component: () => import('@/views/project/Publish'),
      name: 'publishProject',
      hidden: true,
      meta: {
        title: 'publishProject',
        icon: 'megaphone',
        noCache: true,
        breadcrumb: false,
      },
      props: true,
    },
    {
      path: 'workspace/:id(\\d+)',
      component: () => import('@/views/workspace/Edit'),
      name: 'editWorkspace',
      hidden: true,
      meta: { title: 'editWorkspace', icon: 'edit', noCache: true },
      props: true,
    },
  ],
};

export default projectRoutes;
