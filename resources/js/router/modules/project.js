import Layout from '@/layout';

const projectRoutes = {
  path: '/project',
  component: Layout,
  redirect: '/project/pre',
  meta: { title: 'project', icon: 'zip', permissions: ['view menu project'] },
  children: [
    {
      path: 'pre',
      component: () => import('@/views/project'),
      name: 'projectPre',
      meta: { title: 'listProjectPre', icon: 'documentation', permissions: ['view menu project pre submission'] },
    },
    {
      path: 'post',
      component: () => import('@/views/project'),
      name: 'projectPost',
      meta: { title: 'listProjectPost', icon: 'documentation', permissions: ['view menu project post submission'] },
    },
    {
      path: 'process',
      component: () => import('@/views/project'),
      name: 'projectProcess',
      meta: { title: 'listProjectProcess', icon: 'documentation', permissions: ['view menu project on process'] },
    },
    {
      path: 'issued',
      component: () => import('@/views/project'),
      name: 'projectIssued',
      meta: { title: 'listProjectIssued', icon: 'documentation', permissions: ['view menu project issued'] },
    },
    {
      path: 'create',
      component: () => import('@/views/project/Create'),
      name: 'createProject',
      hidden: true,
      meta: { title: 'addProject', icon: 'documentation', noCache: true },
    },
    {
      path: 'publish',
      component: () => import('@/views/project/Publish'),
      name: 'publishProject',
      hidden: true,
      meta: {
        title: 'publishProject',
        icon: 'documentation',
        noCache: true,
        breadcrumb: false,
      },
      props: true,
    },
  ],
};

export default projectRoutes;
