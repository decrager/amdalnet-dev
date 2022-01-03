import Layout from '@/layout';

const scopingRoutes = {
  path: '/scoping',
  component: Layout,
  redirect: '/scoping',
  meta: { title: 'scoping', icon: 'example', permissions: ['view menu scoping'] },
  children: [
    {
      path: '',
      component: () => import('@/views/project'),
      name: 'scopingProject',
      meta: { title: 'scopingProject', icon: 'example', permissions: ['view menu scoping'] },
    },
  ],
};

export default scopingRoutes;
