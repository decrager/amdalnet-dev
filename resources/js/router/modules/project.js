import Layout from '@/layout';

const projectRoutes = {
  path: '/project',
  component: Layout,
  redirect: '/project',
  alwaysShow: true,
  meta: { title: 'project', icon: 'zip', roles: ['initiator', 'editor'] },
  children: [
    {
      path: '',
      component: () => import('@/views/project'),
      name: 'project',
      meta: { title: 'listProject', icon: 'documentation' },
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
