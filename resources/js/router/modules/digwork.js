import Layout from '@/layout';

const digWorkRoutes = {
  path: '/digWorks',
  component: Layout,
  redirect: '/digWorks',
  meta: {
    title: 'digWork',
    icon: 'excel',
    permissions: ['view menu digital workspace'],
  },
  children: [
    {
      path: '',
      component: () => import('@/views/project'),
      name: 'digWorkProject',
      meta: { title: 'digWorkProject', icon: 'excel' },
    },
  ],
};

export default digWorkRoutes;
